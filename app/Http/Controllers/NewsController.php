<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ImageProcessor;

class NewsController extends Controller
{
    /**
     * Display a listing of public news
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 10);
        
        $news = News::with(['admin:id,name,username,photo'])
                    ->where('status', 'active')
                    ->orderBy('published_at', 'desc')
                    ->paginate($perPage);
        
        // Transform the collection to add media URLs and other attributes
        $news->getCollection()->transform(function ($item) {
            $item->media_urls = $item->media_urls;
            $item->time_ago = $item->time_ago;
            $item->category_display = ucfirst($item->category);
            return $item;
        });

        return response()->json([
            'success' => true,
            'data' => $news
        ]);
    }

    /**
     * Store a newly created news item (admin only)
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
            'media' => 'nullable|array|max:10',
            'media.*' => 'file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:50000', // 50MB max
            'featured' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if admin is authenticated
        $admin = auth()->guard('admin')->user();
        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Admin authentication required'
            ], 401);
        }

        // Create news item
        $news = News::create([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category ?? 'general',
            'author' => $admin->id,
            'status' => 'active', // Default to active
            'featured' => $request->featured ?? false,
            'published_at' => $request->published_at ?? now(),
        ]);

        // Handle media uploads
        if ($request->hasFile('media')) {
            $mediaPaths = [];
            $disk = env('FILESYSTEM_DISK', 'public');

            foreach ($request->file('media') as $file) {
                try {
                    // Check if file is an image
                    if (strpos($file->getMimeType(), 'image') !== false) {
                        // Process image with compression and WebP conversion
                        $path = ImageProcessor::processImage($file, 'news');
                        if ($path) {
                            $mediaPaths[] = $path;
                        }
                    } else {
                        // For non-image files (videos), store normally
                        $path = $file->store('news/media', $disk);
                        $mediaPaths[] = $path;
                    }
                } catch (\Exception $e) {
                    // Log error and continue with other files
                    \Log::error('Failed to store news media file', [
                        'file_name' => $file->getClientOriginalName(),
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            if (!empty($mediaPaths)) {
                $news->media = $mediaPaths;
                $news->save();
            }
        }

        $news->load(['admin:id,name,username,photo']);
        $news->media_urls = $news->media_urls;
        $news->time_ago = $news->time_ago;

        return response()->json([
            'success' => true,
            'message' => 'News created successfully',
            'data' => $news
        ], 201);
    }

    /**
     * Display the specified news item
     */
    public function show(News $news)
    {
        // Ensure the news item is active
        if ($news->status !== 'active') {
            abort(404, 'News not found');
        }

        // Increment views count
        $news->increment('views_count');

        $news->load(['admin:id,name,username,photo']);
        $news->media_urls = $news->media_urls;
        $news->time_ago = $news->time_ago;

        // Get related news (same category, active, excluding current)
        $relatedNews = News::where('category', $news->category)
                    ->where('id', '!=', $news->id)
                    ->where('status', 'active')
                    ->orderBy('published_at', 'desc')
                    ->limit(4)
                    ->get();
        
        $relatedNews->transform(function ($item) {
            $item->media_urls = $item->media_urls;
            $item->time_ago = $item->time_ago;
            return $item;
        });

        return view('news_detail', compact('news', 'relatedNews'));
    }

    /**
     * Update the specified news item
     */
    public function update(Request $request, News $news): JsonResponse
    {
        $admin = auth()->guard('admin')->user();
        
        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Admin authentication required'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'category' => 'sometimes|required|string|max:100',
            'status' => 'sometimes|required|in:draft,pending,active,archived',
            'featured' => 'sometimes|required|boolean',
            'published_at' => 'sometimes|required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $news->update($request->only(['title', 'content', 'category', 'status', 'featured', 'published_at']));

        // Handle media updates
        if ($request->hasFile('media')) {
            $mediaPaths = $news->media ?? [];
            $disk = env('FILESYSTEM_DISK', 'public');

            foreach ($request->file('media') as $file) {
                try {
                    // Check if file is an image
                    if (strpos($file->getMimeType(), 'image') !== false) {
                        // Process image with compression and WebP conversion
                        $path = ImageProcessor::processImage($file, 'news');
                        if ($path) {
                            $mediaPaths[] = $path;
                        }
                    } else {
                        // For non-image files (videos), store normally
                        $path = $file->store('news/media', $disk);
                        $mediaPaths[] = $path;
                    }
                } catch (\Exception $e) {
                    // Log error and continue with other files
                    \Log::error('Failed to store news media file', [
                        'file_name' => $file->getClientOriginalName(),
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $news->media = $mediaPaths;
            $news->save();
        }

        $news->load(['admin:id,name,username,photo']);
        $news->media_urls = $news->media_urls;
        $news->time_ago = $news->time_ago;

        return response()->json([
            'success' => true,
            'message' => 'News updated successfully',
            'data' => $news
        ]);
    }

    /**
     * Remove the specified news item
     */
    public function destroy(News $news): JsonResponse
    {
        $admin = auth()->guard('admin')->user();
        
        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Admin authentication required'
            ], 401);
        }

        // Delete associated media files
        if ($news->media) {
            foreach ($news->media as $mediaFile) {
                Storage::disk('public')->delete($mediaFile);
            }
        }

        $news->delete();

        return response()->json([
            'success' => true,
            'message' => 'News deleted successfully'
        ]);
    }


}
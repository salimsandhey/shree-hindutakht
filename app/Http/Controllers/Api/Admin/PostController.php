<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Helpers\ImageProcessor;

class PostController extends Controller
{
    /**
     * Display a listing of posts with pagination
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 10);
        
        $posts = Post::with(['admin:id,name,username,email,role', 'member:id,member_id,name,email,photo'])
                    ->orderBy('created_at', 'desc')
                    ->paginate($perPage);
        
        // Handle potential issues with admin relationship
        $posts->getCollection()->transform(function ($post) {
            // If admin relationship is null but admin_id exists, 
            // it means the admin was deleted
            if ($post->admin_id && !$post->admin) {
                $post->admin = [
                    'id' => $post->admin_id,
                    'name' => 'Deleted Admin',
                    'email' => 'deleted@example.com'
                ];
            }
            
            // Handle member relationship similarly
            if ($post->member_id && !$post->member) {
                $post->member = [
                    'id' => $post->member_id,
                    'member_id' => 'DELETED',
                    'name' => 'Deleted Member',
                    'email' => 'deleted@example.com',
                    'photo' => null
                ];
            }
            
            return $post;
        });
        
        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    /**
     * Store a newly created post
     */
    public function store(Request $request): JsonResponse
    {
        \Log::info('PostController@store called START', [
            'request_id' => uniqid(),
            'request_data' => $request->all(),
            'request_headers' => $request->headers->all(),
            'admin_id' => auth()->guard('admin')->id(),
            'timestamp' => now()->toISOString(),
            'user_agent' => $request->header('User-Agent'),
            'ip_address' => $request->ip(),
            'referer' => $request->header('Referer')
        ]);
        
        // Additional duplicate prevention - check for very recent identical posts
        $admin = auth()->guard('admin')->user();
        if ($admin) {
            $recentIdenticalPost = Post::where('admin_id', $admin->id)
                ->where('content', $request->content)
                ->where('created_at', '>', now()->subMinutes(1)) // Within last minute
                ->first();
                
            if ($recentIdenticalPost) {
                \Log::warning('PostController@store potential duplicate post detected', [
                    'recent_post_id' => $recentIdenticalPost->id,
                    'admin_id' => $admin->id,
                    'content' => $request->content
                ]);
                
                // Return the existing post instead of creating a new one
                $recentIdenticalPost->load(['admin', 'member']);
                return response()->json([
                    'success' => true,
                    'message' => 'Post created successfully',
                    'data' => $recentIdenticalPost
                ]);
            }
        }
        
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'media' => 'nullable|array',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi,webm|max:51200', // 50MB max
        ]);

        if ($validator->fails()) {
            \Log::warning('PostController@store validation failed', [
                'errors' => $validator->errors()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if admin is authenticated
        if (!$admin) {
            \Log::warning('PostController@store admin not authenticated');
            return response()->json([
                'success' => false,
                'message' => 'Admin authentication required'
            ], 401);
        }
    
        \Log::info('PostController@store creating post for admin', [
            'admin_id' => $admin->id,
            'admin_name' => $admin->name
        ]);
    
        // Create post with proper timezone handling
        // We need to manually insert to ensure correct timezone for published_at
        $postId = DB::table('posts')->insertGetId([
            'admin_id' => $admin->id,
            'content' => $request->content,
            'created_by_admin' => true,
            'status' => 'active',
            'published_at' => DB::raw('NOW()'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    
        \Log::info('PostController@store post created', [
            'post_id' => $postId,
            'content' => $request->content,
            'admin_id' => $admin->id
        ]);
    
        $post = Post::find($postId);

        // Handle media uploads with fallback mechanism
        if ($request->hasFile('media')) {
            \Log::info('PostController@store handling media uploads', [
                'file_count' => count($request->file('media'))
            ]);
            
            $mediaPaths = [];
            $disk = env('FILESYSTEM_DISK', 'hostinger');
            
            foreach ($request->file('media') as $file) {
                try {
                    // Check if file is an image
                    if (strpos($file->getMimeType(), 'image') !== false) {
                        // Process image with compression and WebP conversion
                        $path = ImageProcessor::processImage($file);
                        if ($path) {
                            $mediaPaths[] = $path;
                            \Log::info('PostController@store image processed and stored', [
                                'file_name' => $file->getClientOriginalName(),
                                'stored_path' => $path,
                                'disk_used' => $disk
                            ]);
                        }
                    } else {
                        // For non-image files (videos), store normally
                        $path = $file->store('posts/media', $disk);
                        $mediaPaths[] = $path;
                        \Log::info('PostController@store media file stored', [
                            'file_name' => $file->getClientOriginalName(),
                            'stored_path' => $path,
                            'disk_used' => $disk
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('PostController@store failed to store media file', [
                        'file_name' => $file->getClientOriginalName(),
                        'error' => $e->getMessage(),
                        'disk_attempted' => $disk
                    ]);
                    
                    // Fallback to public disk
                    try {
                        if (strpos($file->getMimeType(), 'image') !== false) {
                            // Process image with compression and WebP conversion
                            $path = ImageProcessor::processImage($file);
                        } else {
                            // For non-image files (videos), store normally
                            $path = $file->store('posts/media', env('FILESYSTEM_DISK', 'hostinger'));
                        }
                        
                        if ($path) {
                            $mediaPaths[] = $path;
                            \Log::info('PostController@store media file stored with fallback', [
                                'file_name' => $file->getClientOriginalName(),
                                'stored_path' => $path,
                                'disk_used' => 'hostinger (fallback)'
                            ]);
                        }
                    } catch (\Exception $fallbackException) {
                        \Log::error('PostController@store failed to store media file with fallback', [
                            'file_name' => $file->getClientOriginalName(),
                            'error' => $fallbackException->getMessage()
                        ]);
                    }
                }
            }
            
            if (!empty($mediaPaths)) {
                $post->media = $mediaPaths;
                $post->save();
            }
        }

        // Load relationships and return the post with media
        $post->load(['admin', 'member']);

        \Log::info('PostController@store completed', [
            'post_id' => $post->id,
            'success' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'data' => $post
        ]);
    }

    /**
     * Display the specified post
     */
    public function show(Post $post): JsonResponse
    {
        $post->load(['admin', 'member']);
        
        return response()->json([
            'success' => true,
            'data' => $post
        ]);
    }

    /**
     * Update the specified post
     */
    public function update(Request $request, Post $post): JsonResponse
    {
        Log::info('Post update request received', [
            'post_id' => $post->id,
            'all_request_data' => $request->all(),
            'remove_media' => $request->remove_media,
            'has_remove_media' => $request->has('remove_media'),
            'media_files' => $request->file('media')
        ]);

        $validator = Validator::make($request->all(), [
            'content' => 'sometimes|required|string',
            'media' => 'nullable|array',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi,webm|max:51200', // 50MB max
            'remove_media' => 'nullable|array',
            'remove_media.*' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update content if provided
        if ($request->has('content')) {
            $post->content = $request->content;
        }

        // Handle media removal
        if ($request->has('remove_media')) {
            $mediaToRemove = $request->remove_media;
            Log::info('Media to remove:', ['media_to_remove' => $mediaToRemove]);
            
            $currentMedia = $post->media ?? [];
            Log::info('Current media:', ['current_media' => $currentMedia]);
            
            // Remove specified media from the array
            $updatedMedia = array_filter($currentMedia, function($mediaPath) use ($mediaToRemove) {
                // Check if this media should be removed
                foreach ($mediaToRemove as $removeUrl) {
                    Log::info('Checking media for removal', [
                        'media_path' => $mediaPath,
                        'remove_url' => $removeUrl
                    ]);
                    
                    // Extract the path from the URL - handle both asset() and url() formats
                    $pathFromUrl = str_replace(url('storage/'), '', $removeUrl);
                    $pathFromAsset = str_replace(asset('storage/'), '', $removeUrl);
                    
                    // Also handle the case where there might be a leading slash
                    $pathFromUrlTrimmed = ltrim($pathFromUrl, '/');
                    $pathFromAssetTrimmed = ltrim($pathFromAsset, '/');
                    
                    Log::info('Path comparisons', [
                        'direct_match' => $mediaPath === $removeUrl,
                        'path_from_url' => $mediaPath === $pathFromUrl,
                        'path_from_asset' => $mediaPath === $pathFromAsset,
                        'path_from_url_trimmed' => $mediaPath === $pathFromUrlTrimmed,
                        'path_from_asset_trimmed' => $mediaPath === $pathFromAssetTrimmed,
                        'media_path' => $mediaPath,
                        'remove_url' => $removeUrl,
                        'path_from_url_result' => $pathFromUrl,
                        'path_from_asset_result' => $pathFromAsset
                    ]);
                    
                    // Check all possible matches including trimmed versions
                    if ($mediaPath === $removeUrl || 
                        $mediaPath === $pathFromUrl || 
                        $mediaPath === $pathFromAsset ||
                        $mediaPath === $pathFromUrlTrimmed ||
                        $mediaPath === $pathFromAssetTrimmed) {
                        // Delete the file from storage with fallback mechanism
                        $disk = env('FILESYSTEM_DISK', 'public');
                        try {
                            if (Storage::disk($disk)->exists($mediaPath)) {
                                Storage::disk($disk)->delete($mediaPath);
                                Log::info('Deleted file from storage', [
                                    'file_path' => $mediaPath,
                                    'disk_used' => $disk
                                ]);
                            } else {
                                Log::info('File not found in storage', [
                                    'file_path' => $mediaPath,
                                    'disk_checked' => $disk
                                ]);
                                
                                // Fallback to public disk
                                if (Storage::disk(env('FILESYSTEM_DISK', 'public'))->exists($mediaPath)) {
                                    Storage::disk(env('FILESYSTEM_DISK', 'public'))->delete($mediaPath);
                                    Log::info('Deleted file from fallback storage', [
                                        'file_path' => $mediaPath,
                                        'disk_used' => 'public (fallback)'
                                    ]);
                                }
                            }
                        } catch (\Exception $e) {
                            Log::error('Failed to delete media file', [
                                'file_path' => $mediaPath,
                                'error' => $e->getMessage(),
                                'disk_attempted' => $disk
                            ]);
                            
                            // Fallback to public disk
                            try {
                                if (Storage::disk(env('FILESYSTEM_DISK', 'public'))->exists($mediaPath)) {
                                    Storage::disk(env('FILESYSTEM_DISK', 'public'))->delete($mediaPath);
                                    Log::info('Deleted file from fallback storage after error', [
                                        'file_path' => $mediaPath,
                                        'disk_used' => 'public (fallback)'
                                    ]);
                                }
                            } catch (\Exception $fallbackException) {
                                Log::error('Failed to delete media file from fallback disk', [
                                    'file_path' => $mediaPath,
                                    'error' => $fallbackException->getMessage()
                                ]);
                            }
                        }
                        return false; // Remove this media
                    }
                }
                return true; // Keep this media
            });
            
            // Reindex array and update post
            $post->media = array_values($updatedMedia);
            Log::info('Updated media array', ['updated_media' => $post->media]);
        }

        // Handle new media uploads with fallback mechanism
        if ($request->hasFile('media')) {
            $mediaPaths = $post->media ?? [];
            $disk = env('FILESYSTEM_DISK', 'public');
            
            foreach ($request->file('media') as $file) {
                try {
                    // Check if file is an image
                    if (strpos($file->getMimeType(), 'image') !== false) {
                        // Process image with compression and WebP conversion
                        $path = ImageProcessor::processImage($file);
                        if ($path) {
                            $mediaPaths[] = $path;
                            Log::info('New image processed and uploaded', [
                                'file_path' => $path,
                                'disk_used' => $disk
                            ]);
                        }
                    } else {
                        // For non-image files (videos), store normally
                        $path = $file->store('posts/media', $disk);
                        $mediaPaths[] = $path;
                        Log::info('New media uploaded', [
                            'file_path' => $path,
                            'disk_used' => $disk
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to store media file', [
                        'file_name' => $file->getClientOriginalName(),
                        'error' => $e->getMessage(),
                        'disk_attempted' => $disk
                    ]);
                    
                    // Fallback to public disk
                    try {
                        if (strpos($file->getMimeType(), 'image') !== false) {
                            // Process image with compression and WebP conversion
                            $path = ImageProcessor::processImage($file);
                        } else {
                            // For non-image files (videos), store normally
                            $path = $file->store('posts/media', env('FILESYSTEM_DISK', 'public'));
                        }
                        
                        if ($path) {
                            $mediaPaths[] = $path;
                            Log::info('New media uploaded with fallback', [
                                'file_path' => $path,
                                'disk_used' => 'public (fallback)'
                            ]);
                        }
                    } catch (\Exception $fallbackException) {
                        Log::error('Failed to store media file with fallback', [
                            'file_name' => $file->getClientOriginalName(),
                            'error' => $fallbackException->getMessage()
                        ]);
                    }
                }
            }
            
            $post->media = $mediaPaths;
        }

        $post->save();
        Log::info('Post saved', ['post_id' => $post->id, 'media' => $post->media]);

        $post->load(['admin', 'member']);

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully',
            'data' => $post
        ]);
    }

    /**
     * Remove the specified post
     */
    public function destroy(Post $post): JsonResponse
    {
        // Delete media files with fallback mechanism
        if ($post->media) {
            $disk = env('FILESYSTEM_DISK', 'public');
            foreach ($post->media as $path) {
                try {
                    if (Storage::disk($disk)->exists($path)) {
                        Storage::disk($disk)->delete($path);
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to delete media file from primary disk', [
                        'file_path' => $path,
                        'error' => $e->getMessage(),
                        'disk_attempted' => $disk
                    ]);
                    
                    // Fallback to public disk
                    try {
                        if (Storage::disk(env('FILESYSTEM_DISK', 'public'))->exists($path)) {
                            Storage::disk(env('FILESYSTEM_DISK', 'public'))->delete($path);
                        }
                    } catch (\Exception $fallbackException) {
                        Log::error('Failed to delete media file from fallback disk', [
                            'file_path' => $path,
                            'error' => $fallbackException->getMessage()
                        ]);
                    }
                }
            }
        }
        
        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully'
        ]);
    }
}
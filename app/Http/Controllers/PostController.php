<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\PostComment;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Get posts feed
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 10);
        
        // Determine the authenticated user (member or admin)
        $user = auth('api')->user();
        
        // This check is now handled by the middleware, but we'll keep it as a safety measure
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        $query = Post::active()
            ->with(['member:id,name,photo,member_id', 'admin:id,name,username,photo', 'comments.member:id,name,photo'])
            ->withCount(['likes', 'comments'])
            ->recent();

        // Load pinned posts first, then regular posts
        $pinnedPosts = (clone $query)->pinned()->take(3)->get();
        $regularPosts = (clone $query)->where('is_pinned', false)->paginate($perPage);

        // Add user interaction data
        $posts = $regularPosts->getCollection()->merge($pinnedPosts)->map(function ($post) use ($user) {
            $post->is_liked = $post->isLikedBy($user->id);
            $post->media_urls = $post->media_urls;
            $post->time_ago = $post->time_ago;
            $post->share_link = $post->share_link;
            return $post;
        });

        // Add cache control headers to prevent caching
        return response()->json([
            'success' => true,
            'data' => [
                'posts' => $posts,
                'pagination' => [
                    'current_page' => $regularPosts->currentPage(),
                    'last_page' => $regularPosts->lastPage(),
                    'per_page' => $regularPosts->perPage(),
                    'total' => $regularPosts->total(),
                ]
            ]
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate')
          ->header('Pragma', 'no-cache')
          ->header('Expires', '0');
    }

    /**
     * Create a new post (admin only)
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:2000',
            'media' => 'nullable|array|max:5',
            'media.*' => 'file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:50000', // 50MB max
            'type' => 'nullable|in:text,image,video,mixed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // This endpoint should only be accessible to admins via admin routes
        // For member posts, they should use a different endpoint
        return response()->json([
            'success' => false,
            'message' => 'Use admin API endpoint for post creation'
        ], 403);
    }

    /**
     * Get a specific post
     */
    public function show(Post $post): JsonResponse
    {
        // Determine the authenticated user (member or admin)
        $user = auth('api')->user();
        
        // This check is now handled by the middleware, but we'll keep it as a safety measure
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        $post->load([
            'member:id,name,photo,member_id',
            'admin:id,name,username,photo',
            'comments' => function ($query) {
                $query->topLevel()->approved()->with(['member:id,name,photo', 'replies.member:id,name,photo'])->orderBy('created_at', 'desc');
            }
        ]);
        
        $post->is_liked = $post->isLikedBy($user->id);
        $post->media_urls = $post->media_urls;
        $post->time_ago = $post->time_ago;
        $post->share_link = $post->share_link;

        // Add cache control headers to prevent caching
        return response()->json([
            'success' => true,
            'data' => $post
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate')
          ->header('Pragma', 'no-cache')
          ->header('Expires', '0');
    }

    /**
     * Toggle like on a post
     */
    public function toggleLike(Post $post): JsonResponse
    {
        $member = auth('api')->user();
        
        // This check is now handled by the middleware, but we'll keep it as a safety measure
        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }
        
        \Log::info('Toggle like request', [
            'post_id' => $post->id,
            'member_id' => $member->id,
            'current_likes' => $post->likes_count
        ]);
        
        // Check if the member has already liked this post
        $existingLike = PostLike::where('post_id', $post->id)
            ->where('member_id', $member->id)
            ->first();

        if ($existingLike) {
            \Log::info('Removing existing like', [
                'like_id' => $existingLike->id,
                'post_id' => $post->id,
                'member_id' => $member->id
            ]);
            
            // Unlike the post
            $existingLike->delete();
            $post->decrementLikes();
            
            $isLiked = false;
            $message = 'Post unliked successfully';
        } else {
            \Log::info('Creating new like', [
                'post_id' => $post->id,
                'member_id' => $member->id
            ]);
            
            // Like the post
            PostLike::create([
                'post_id' => $post->id,
                'member_id' => $member->id,
            ]);
            $post->incrementLikes();
            
            $isLiked = true;
            $message = 'Post liked successfully';
        }
        
        // Refresh the post to get the latest count
        $post->refresh();
        
        \Log::info('Like toggle completed', [
            'post_id' => $post->id,
            'member_id' => $member->id,
            'is_liked' => $isLiked,
            'likes_count' => $post->likes_count,
            'total_post_likes' => PostLike::where('post_id', $post->id)->count()
        ]);

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'is_liked' => $isLiked,
                'likes_count' => $post->likes_count
            ]
        ]);
    }

    /**
     * Add comment to a post
     */
    public function addComment(Request $request, Post $post): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|max:500',
            'parent_id' => 'nullable|exists:post_comments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $member = auth('api')->user();
        
        // This check is now handled by the middleware, but we'll keep it as a safety measure
        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }
        
        // Additional duplicate prevention - check for very recent identical comments
        $recentIdenticalComment = PostComment::where('post_id', $post->id)
            ->where('member_id', $member->id)
            ->where('comment', $request->comment)
            ->where('created_at', '>', now()->subMinutes(1)) // Within last minute
            ->first();
            
        if ($recentIdenticalComment) {
            \Log::warning('PostController@addComment potential duplicate comment detected', [
                'recent_comment_id' => $recentIdenticalComment->id,
                'member_id' => $member->id,
                'post_id' => $post->id,
                'comment' => $request->comment
            ]);
            
            // Return the existing comment instead of creating a new one
            $recentIdenticalComment->load('member:id,name,photo');
            $recentIdenticalComment->time_ago = $recentIdenticalComment->time_ago;
            
            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully',
                'data' => $recentIdenticalComment
            ], 201);
        }
        
        $comment = PostComment::create([
            'post_id' => $post->id,
            'member_id' => $member->id,
            'comment' => $request->comment,
            'parent_id' => $request->parent_id,
            'is_approved' => true, // Auto-approve comments
        ]);

        $post->incrementComments();
        
        $comment->load('member:id,name,photo');
        $comment->time_ago = $comment->time_ago;

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
            'data' => $comment
        ], 201);
    }

    /**
     * Get comments for a post
     */
    public function getComments(Request $request, Post $post): JsonResponse
    {
        $perPage = $request->get('per_page', 10);
        
        // This check is now handled by the middleware, but we'll keep it as a safety measure
        $member = auth('api')->user();
        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }
        
        $comments = PostComment::where('post_id', $post->id)
            ->topLevel()
            ->approved()
            ->with(['member:id,name,photo', 'replies.member:id,name,photo'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $comments->getCollection()->transform(function ($comment) {
            $comment->time_ago = $comment->time_ago;
            $comment->replies->transform(function ($reply) {
                $reply->time_ago = $reply->time_ago;
                return $reply;
            });
            return $comment;
        });

        return response()->json([
            'success' => true,
            'data' => $comments
        ]);
    }

    /**
     * Share a post (increment share count)
     */
    public function sharePost(Post $post): JsonResponse
    {
        // This check is now handled by the middleware, but we'll keep it as a safety measure
        $member = auth('api')->user();
        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }
        
        $post->incrementShares();

        return response()->json([
            'success' => true,
            'message' => 'Post shared successfully',
            'data' => [
                'share_link' => $post->share_link,
                'shares_count' => $post->fresh()->shares_count
            ]
        ]);
    }

    /**
     * Update a post (member can only update their own posts)
     */
    public function update(Request $request, Post $post): JsonResponse
    {
        $member = auth('api')->user();
        
        if ($post->member_id !== $member->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this post'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $post->update([
            'content' => $request->content,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully',
            'data' => $post->fresh()
        ]);
    }

    /**
     * Delete a post (member can only delete their own posts)
     */
    public function destroy(Post $post): JsonResponse
    {
        $member = auth('api')->user();
        
        if ($post->member_id !== $member->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this post'
            ], 403);
        }

        // Delete associated media files
        if ($post->media) {
            foreach ($post->media as $mediaFile) {
                Storage::disk('public')->delete($mediaFile);
            }
        }

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully'
        ]);
    }
}
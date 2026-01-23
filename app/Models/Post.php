<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Admin;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'admin_id',
        'content',
        'media',
        'type',
        'visibility',
        'is_pinned',
        'is_featured',
        'status',
        'likes_count',
        'comments_count',
        'shares_count',
        'published_at',
        'created_by_admin',
    ];

    protected $casts = [
        'media' => 'array',
        'is_pinned' => 'boolean',
        'is_featured' => 'boolean',
        'created_by_admin' => 'boolean',
        'published_at' => 'datetime',
        'visibility' => 'string',
    ];

    protected $with = [];

    // Append media_urls to the model's array form
    protected $appends = ['media_urls'];

    // Relationships
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class)->with('member:id,name,photo');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    // Helper Methods
    public function getMediaUrlsAttribute()
    {
        if (!$this->media) {
            return [];
        }

        $disk = env('FILESYSTEM_DISK', 'public');
        
        return collect($this->media)->map(function ($media) use ($disk) {
            // For hostinger disk, we need to generate the correct URL
            if ($disk === 'hostinger') {
                // When using hostinger disk, files are stored in public_html/storage
                // So the URL should be directly accessible
                return url('storage/' . $media);
            }
            
            // For other disks, use Laravel's storage URL generation
            try {
                return \Storage::disk($disk)->url($media);
            } catch (\Exception $e) {
                // Fallback to asset helper
                return asset('storage/' . $media);
            }
        })->filter()->toArray();
    }

    public function getTimeAgoAttribute()
    {
        return $this->published_at->diffForHumans();
    }

    public function getShareLinkAttribute()
    {
        return url('/post/' . $this->id);
    }

    public function isLikedBy($memberId)
    {
        return $this->likes()->where('member_id', $memberId)->exists();
    }

    public function incrementLikes()
    {
        $this->increment('likes_count');
    }

    public function decrementLikes()
    {
        $this->decrement('likes_count');
    }

    public function incrementComments()
    {
        $this->increment('comments_count');
    }

    public function decrementComments()
    {
        $this->decrement('comments_count');
    }

    public function incrementShares()
    {
        $this->increment('shares_count');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'media',
        'author',
        'category',
        'status',
        'views_count',
        'published_at',
        'featured',
    ];

    protected $casts = [
        'media' => 'array',
        'published_at' => 'datetime',
        'featured' => 'boolean',
    ];

    // Append media_urls to the model's array form
    protected $appends = ['media_urls'];

    // Relationship with admin who created the news
    public function admin(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Admin::class, 'author');
    }

    public function getAuthorNameAttribute()
    {
        if ($this->author && $this->admin) {
            return $this->admin->name;
        }
        return 'Admin';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('published_at', 'desc');
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
        return url('/news/' . $this->id);
    }
}
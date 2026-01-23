<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Services\AvatarService;

class Member extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'member_id',
        'name',
        'email',
        'password',
        'phone',
        'address',
        'photo',
        'date_of_birth',
        'gender',
        'status',
        'joined_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'joined_at' => 'datetime',
        'date_of_birth' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($member) {
            if (!$member->member_id) {
                $member->member_id = 'HT' . str_pad(random_int(1, 999999), 6, '0', STR_PAD_LEFT);
            }
            if (!$member->joined_at) {
                $member->joined_at = now();
            }
        });
        
        static::created(function ($member) {
            // Generate avatar if no photo was uploaded during registration
            if (!$member->photo) {
                $avatarPath = AvatarService::generateSvgAvatar($member->name);
                // Don't update the model to avoid infinite loop, just ensure avatar exists
            }
        });
        
        // Handle cascading deletes
        static::deleting(function ($member) {
            // Delete related data
            $member->postComments()->delete();
            $member->postLikes()->delete();
            $member->eventRsvps()->delete();
            $member->notifications()->delete();
            
            // Delete photo file if exists
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
        });
    }

    // JWT Methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return ['type' => 'member'];
    }

    // Mutators
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    // Relationships
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function postLikes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }

    public function postComments(): HasMany
    {
        return $this->hasMany(PostComment::class);
    }

    public function eventRsvps(): HasMany
    {
        return $this->hasMany(EventRsvp::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    // Helper Methods
    public function getFullPhotoUrlAttribute()
    {
        if ($this->photo) {
            // Check if photo file actually exists
            if (\Storage::disk('public')->exists($this->photo)) {
                return asset('storage/' . $this->photo);
            } else {
                // Photo record exists but file is missing, clean up
                $this->update(['photo' => null]);
            }
        }
        
        // Generate avatar if no photo exists or photo file is missing
        return $this->getAvatarUrl();
    }
    
    public function getAvatarUrl()
    {
        // Check if we already have a generated avatar
        $avatarPath = 'avatars/avatar_' . md5($this->name . $this->id) . '.svg';
        
        if (!\Storage::disk('public')->exists($avatarPath)) {
            // Generate new avatar
            try {
                $avatarPath = AvatarService::generateSvgAvatar($this->name);
            } catch (\Exception $e) {
                // Fallback to a simple data URL avatar
                return $this->generateSimpleAvatar();
            }
        }
        
        return asset('storage/' . $avatarPath);
    }
    
    private function generateSimpleAvatar()
    {
        $initials = collect(explode(' ', $this->name))
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->take(2)
            ->join('');
        
        $colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FECA57'];
        $color = $colors[strlen($this->name) % count($colors)];
        
        return 'data:image/svg+xml,' . urlencode(
            '<svg width="128" height="128" viewBox="0 0 128 128" xmlns="http://www.w3.org/2000/svg">' .
                '<rect width="128" height="128" fill="' . $color . '"/>' .
                '<text x="50%" y="50%" dy="0.35em" text-anchor="middle" fill="white" font-family="Arial, sans-serif" font-size="48" font-weight="bold">' . $initials . '</text>' .
            '</svg>'
        );
    }

    public function hasLikedPost($postId)
    {
        return $this->postLikes()->where('post_id', $postId)->exists();
    }

    public function hasRsvpedToEvent($eventId)
    {
        return $this->eventRsvps()->where('event_id', $eventId)->exists();
    }

    public function getUnreadNotificationsCount()
    {
        return $this->notifications()->where('is_read', false)->count();
    }
}
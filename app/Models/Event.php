<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'location',
        'featured_image',
        'event_date',
        'registration_deadline',
        'max_participants',
        'status',
        'is_featured',
        'interested_count',
        'going_count',
        'additional_info',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'registration_deadline' => 'datetime',
        'is_featured' => 'boolean',
        'additional_info' => 'array',
    ];

    // Relationships
    public function rsvps(): HasMany
    {
        return $this->hasMany(EventRsvp::class);
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming')->where('event_date', '>=', now()->startOfDay());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Helper Methods
    public function getMemberRsvp($memberId)
    {
        return $this->rsvps()->where('member_id', $memberId)->first();
    }

    public function incrementInterested()
    {
        $this->increment('interested_count');
    }

    public function incrementGoing()
    {
        $this->increment('going_count');
    }

    public function decrementInterested()
    {
        $this->decrement('interested_count');
    }

    public function decrementGoing()
    {
        $this->decrement('going_count');
    }
}

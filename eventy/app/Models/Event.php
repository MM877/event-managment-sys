<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'description',
        'country_id',
        'city',
        'image',
        'user_id',
        'start_date',
        'end_date',
        'location',
        'num_tickets'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Get the user that owns the event.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the country of the event.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the city of the event.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function attendings()
    {
        return $this->hasMany(Attending::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function savedEvents()
    {
        return $this->hasMany(SavedEvent::class);
    }

    /**
     * The tags that belong to the event.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    // Define the attendees relationship
    public function attendees()
    {
        return $this->belongsToMany(User::class, 'event_user', 'event_id', 'user_id')
                    ->withTimestamps();
    }

    // Define tickets relationship
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    
    // Get remaining tickets count
    public function getRemainingTicketsAttribute()
    {
        $totalTickets = $this->num_tickets ?: 0;
        $bookedTickets = $this->tickets()->where('status', 'booked')->sum('quantity');
        
        return max(0, $totalTickets - $bookedTickets);
    }
}

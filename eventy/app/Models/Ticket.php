<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'quantity',
        'status',
        'ticket_code',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            $ticket->ticket_code = strtoupper(uniqid('TKT-'));
        });
    }

    /**
     * Get the event that owns the ticket.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user that owns the ticket.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gallery extends Model
{
    use HasFactory;
    
    protected $fillable = ['event_id', 'image', 'caption', 'user_id'];

    /**
     * Get the user that owns the gallery item.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the event that the gallery item belongs to.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}

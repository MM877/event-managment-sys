<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'country_id'];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get the country that owns the city.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    
}

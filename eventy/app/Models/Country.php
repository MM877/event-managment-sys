<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Get the cities for the country.
     */
    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }   
}

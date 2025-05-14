<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\User;
use App\Models\Country;
use App\Models\Tag;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        
        $this->createCountries();
        $this->createCities();
        $this->createTags();
    }

    public function createCountries()
    {
        $countries = [
            ['name' => 'United States'],
            ['name' => 'Canada'],
            ['name' => 'United Kingdom'],
            ['name' => 'Australia'],
            ['name' => 'New Zealand'],
            ['name' => 'South Africa'],
            ['name' => 'India'],
            ['name' => 'Brazil'],
        ];

        foreach ($countries as $country) {
            Country::create($country);
        }
    }
    
    public function createCities()
    {
        $cities = [
            // United States
            ['name' => 'New York', 'country_id' => 1],
            ['name' => 'Los Angeles', 'country_id' => 1],
            ['name' => 'Chicago', 'country_id' => 1],
            ['name' => 'Houston', 'country_id' => 1],
            
            // Canada
            ['name' => 'Toronto', 'country_id' => 2],
            ['name' => 'Vancouver', 'country_id' => 2],
            ['name' => 'Montreal', 'country_id' => 2],
            
            // United Kingdom
            ['name' => 'London', 'country_id' => 3],
            ['name' => 'Manchester', 'country_id' => 3],
            ['name' => 'Edinburgh', 'country_id' => 3],
            
            // Australia
            ['name' => 'Sydney', 'country_id' => 4],
            ['name' => 'Melbourne', 'country_id' => 4],
            ['name' => 'Brisbane', 'country_id' => 4],
            
            // New Zealand
            ['name' => 'Auckland', 'country_id' => 5],
            ['name' => 'Wellington', 'country_id' => 5],
            
            // South Africa
            ['name' => 'Cape Town', 'country_id' => 6],
            ['name' => 'Johannesburg', 'country_id' => 6],
            
            // India
            ['name' => 'Mumbai', 'country_id' => 7],
            ['name' => 'Delhi', 'country_id' => 7],
            ['name' => 'Bangalore', 'country_id' => 7],
            
            // Brazil
            ['name' => 'Rio de Janeiro', 'country_id' => 8],
            ['name' => 'São Paulo', 'country_id' => 8],
            ['name' => 'Brasília', 'country_id' => 8],
        ];
        
        foreach ($cities as $city) {
            City::create($city);
        }
    }

    public function createTags()
    {
        $tags = [
            ['name' => 'Music', 'slug' => 'music'],
            ['name' => 'Art', 'slug' => 'art'],
            ['name' => 'Technology', 'slug' => 'technology'],
            ['name' => 'Sports', 'slug' => 'sports'],
            ['name' => 'Food', 'slug' => 'food'],
            ['name' => 'Fashion', 'slug' => 'fashion'],
            ['name' => 'Education', 'slug' => 'education'],
            ['name' => 'Business', 'slug' => 'business'],
            ['name' => 'Health', 'slug' => 'health'],
            ['name' => 'Entertainment', 'slug' => 'entertainment'],
            ['name' => 'Travel', 'slug' => 'travel'],
            ['name' => 'Science', 'slug' => 'science'],
        ];
        
        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}

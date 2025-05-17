<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use App\Models\Tag;
use App\Models\Country;
use App\Models\City;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TestEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find or create a test user
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        
        // Get a country and city
        $country = Country::first() ?? Country::create(['name' => 'United States']);
        $city = City::first() ?? City::create(['name' => 'New York', 'country_id' => $country->id]);
        
        // Create test events with different ticket availability
        $this->createUpcomingEvent($user, $country, $city);
        $this->createFullyBookedEvent($user, $country, $city);
        $this->createPastEvent($user, $country, $city);
    }
    
    private function createUpcomingEvent($user, $country, $city)
    {
        $event = Event::create([
            'name' => 'Test Event - Tickets Available',
            'title' => 'Book Me: Available Tickets',
            'description' => 'This is a test event with available tickets that you can book. It starts tomorrow and ends in a week.',
            'country_id' => $country->id,
            'city' => $city->name,
            'user_id' => $user->id,
            'start_date' => Carbon::tomorrow(),
            'end_date' => Carbon::now()->addWeek(),
            'location' => '123 Test Street, Test City',
            'num_tickets' => 100,
        ]);
        
        // Attach some tags
        $tags = Tag::inRandomOrder()->limit(3)->get();
        $event->tags()->attach($tags);
    }
    
    private function createFullyBookedEvent($user, $country, $city)
    {
        $event = Event::create([
            'name' => 'Test Event - Fully Booked',
            'title' => 'Sold Out Event',
            'description' => 'This is a test event that is fully booked. All tickets have been sold.',
            'country_id' => $country->id,
            'city' => $city->name,
            'user_id' => $user->id,
            'start_date' => Carbon::now()->addDays(3),
            'end_date' => Carbon::now()->addDays(4),
            'location' => '456 Test Avenue, Test City',
            'num_tickets' => 0,
        ]);
        
        // Attach some tags
        $tags = Tag::inRandomOrder()->limit(3)->get();
        $event->tags()->attach($tags);
    }
    
    private function createPastEvent($user, $country, $city)
    {
        $event = Event::create([
            'name' => 'Test Event - Past Event',
            'title' => 'Past Event',
            'description' => 'This is a test event that has already ended. It occurred last week.',
            'country_id' => $country->id,
            'city' => $city->name,
            'user_id' => $user->id,
            'start_date' => Carbon::now()->subWeeks(2),
            'end_date' => Carbon::now()->subWeek(),
            'location' => '789 Test Boulevard, Test City',
            'num_tickets' => 50,
        ]);
        
        // Attach some tags
        $tags = Tag::inRandomOrder()->limit(3)->get();
        $event->tags()->attach($tags);
    }
} 
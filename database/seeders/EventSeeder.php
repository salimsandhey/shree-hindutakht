<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create upcoming events
        Event::create([
            'title' => 'Weekly Community Meeting',
            'description' => 'Join us for our weekly community meeting where we discuss upcoming events and community matters.',
            'location' => 'Community Center Hall',
            'event_date' => now()->addDays(2)->setTime(18, 0, 0),
            'status' => 'upcoming',
            'is_featured' => true,
            'interested_count' => 0,
            'going_count' => 0,
        ]);

        Event::create([
            'title' => 'Festival Preparation',
            'description' => 'Help us prepare for the upcoming festival. All volunteers welcome!',
            'location' => 'Temple Premises',
            'event_date' => now()->addDays(7)->setTime(10, 0, 0),
            'status' => 'upcoming',
            'is_featured' => false,
            'interested_count' => 0,
            'going_count' => 0,
        ]);

        Event::create([
            'title' => 'Youth Program',
            'description' => 'Special program for youth members of our community.',
            'location' => 'Youth Center',
            'event_date' => now()->addDays(14)->setTime(15, 0, 0),
            'status' => 'upcoming',
            'is_featured' => true,
            'interested_count' => 0,
            'going_count' => 0,
        ]);

        $this->command->info('Events seeded successfully!');
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create sample news items
        \App\Models\News::create([
            'title' => 'Hindu Takht Celebrates Traditional Festival',
            'content' => '<p>The Hindu Takht community came together to celebrate the annual traditional festival with prayers, cultural performances, and community gatherings. The event brought together over 500 members from different regions, strengthening bonds and promoting unity among the community.</p><p>The celebration included traditional music, dance performances, and a grand feast that lasted throughout the day. Elders shared wisdom and stories from ancient texts, while children participated in various cultural activities designed to preserve our heritage.</p>',
            'category' => 'events',
            'status' => 'active',
            'featured' => true,
            'published_at' => now()->subDays(2),
        ]);

        \App\Models\News::create([
            'title' => 'New Community Center Inaugurated',
            'content' => '<p>The new community center was officially inaugurated yesterday with a grand ceremony attended by local leaders and community members. The facility includes meeting rooms, a library, and spaces for cultural activities.</p><p>The center aims to serve as a hub for community engagement, educational programs, and cultural preservation. It features modern amenities while maintaining traditional architectural elements that reflect our heritage.</p>',
            'category' => 'infrastructure',
            'status' => 'active',
            'featured' => true,
            'published_at' => now()->subDays(5),
        ]);

        \App\Models\News::create([
            'title' => 'Spiritual Discourse Series Announced',
            'content' => '<p>A series of spiritual discourses will be held every weekend starting next month. Renowned scholars and spiritual leaders will share insights from ancient scriptures and their relevance in modern life.</p><p>The series aims to provide spiritual guidance and foster deeper understanding of our traditions. Sessions will be interactive, allowing participants to engage in meaningful discussions and ask questions about spiritual practices.</p>',
            'category' => 'spiritual',
            'status' => 'active',
            'featured' => false,
            'published_at' => now()->subDays(1),
        ]);

        \App\Models\News::create([
            'title' => 'Charity Drive for Needy Families',
            'content' => '<p>The Hindu Takht community launched a charity drive to support families in need during the upcoming festive season. Volunteers collected donations of food, clothing, and essential supplies.</p><p>The initiative reflects our commitment to seva (selfless service) and caring for those less fortunate. Over 100 families will benefit from this charitable effort, bringing joy and support during the holidays.</p>',
            'category' => 'charity',
            'status' => 'active',
            'featured' => false,
            'published_at' => now()->subDays(3),
        ]);

        \App\Models\News::create([
            'title' => 'Heritage Preservation Workshop',
            'content' => '<p>A workshop on preserving cultural heritage was conducted for young members of the community. Participants learned about traditional crafts, languages, and customs passed down through generations.</p><p>The workshop emphasized the importance of maintaining our cultural identity in a rapidly changing world. Hands-on activities included traditional art forms, storytelling sessions, and demonstrations of cultural practices.</p>',
            'category' => 'education',
            'status' => 'active',
            'featured' => false,
            'published_at' => now()->subDays(7),
        ]);
    }
}

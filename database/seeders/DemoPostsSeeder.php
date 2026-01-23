<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Member;
use App\Models\Admin;
use App\Models\PostComment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoPostsSeeder extends Seeder
{
    /**
     * Run the database seeds for testing lazy loading and infinite scroll.
     */
    public function run()
    {
        // Sample image URLs for testing (using Unsplash and other free sources)
        $sampleImages = [
            'https://images.unsplash.com/photo-1584464491033-06628f3a6b7b?w=800&h=600',
            'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600',
            'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=800&h=600',
            'https://images.unsplash.com/photo-1614624532983-4ce03382d63d?w=800&h=600',
            'https://images.unsplash.com/photo-1603793303277-ed67787545de?w=800&h=600',
            'https://images.unsplash.com/photo-1582879293889-ea95309ba5b6?w=800&h=600',
            'https://images.unsplash.com/photo-1515486191131-d71ee5c0ce74?w=800&h=600',
            'https://images.unsplash.com/photo-1604264849633-67b1ea2ce0a4?w=800&h=600',
            'https://images.unsplash.com/photo-1626398441231-62bf2894cd0b?w=800&h=600',
            'https://images.unsplash.com/photo-1484252804875-0d0ae73150c0?w=800&h=600',
            'https://images.unsplash.com/photo-1578674473253-27e1ac64ad5c?w=800&h=600',
            'https://images.unsplash.com/photo-1586524147853-b5d1c9c96ee7?w=800&h=600',
            'https://images.unsplash.com/photo-1560707303-4e980ce876ad?w=800&h=600',
            'https://images.unsplash.com/photo-1524531673037-e5a13bbf02b8?w=800&h=600',
            'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600',
            'https://images.unsplash.com/photo-1609012243712-3a9b544f8a96?w=800&h=600',
            'https://images.unsplash.com/photo-1597149508236-3623cf122f40?w=800&h=600',
            'https://images.unsplash.com/photo-1545456820-7b2bb8dc79e9?w=800&h=600',
            'https://images.unsplash.com/photo-1578652122117-e0d31700b8b7?w=800&h=600',
            'https://images.unsplash.com/photo-1582879293889-ea95309ba5b6?w=800&h=600',
        ];

        // Get existing members
        $members = Member::take(5)->get();

        if ($members->isEmpty()) {
            $this->command->warn('No members found. Please run MemberSeeder first.');
            return;
        }

        // Demo post content for Hindu/spiritual theme
        $postContents = [
            "🙏 Jai Shree Ram! Wishing everyone a blessed day filled with peace and devotion.",
            "✨ Today's morning prayers were especially peaceful. The divine energy is strong! 🕉️",
            "📿 Remember to chant your mantras today. The power of Om is infinite! 🔆",
            "🌅 Beautiful sunrise darshan at the temple this morning. Blessed to witness such divine beauty!",
            "🙌 Community service is the highest form of worship. Volunteered at the local shelter today.",
            "🌺 Offering flowers and prayers to Lord Ganesha for removing all obstacles from our path.",
            "📖 Reading from the Bhagavad Gita brings such wisdom and clarity to life's challenges.",
            "🎭 Excited for tomorrow's cultural program! Our community performances are always amazing.",
            "🍃 Nature is God's temple. Spent time in meditation by the sacred river today.",
            "🔔 Temple bells ringing in the evening create such a peaceful atmosphere for prayer.",
            "🪔 Lighting diyas for the evening aarti. The glow fills our hearts with warmth.",
            "🎨 Beautiful rangoli created by our community members for today's celebration!",
            "🥳 Happy to announce our upcoming festival celebrations! Everyone is invited.",
            "🎵 Devotional music practice session was wonderful. Music truly connects us to the divine.",
            "🌟 Grateful for this beautiful community that supports each other in spiritual growth.",
            "🏛️ Temple renovation project is progressing well. Thank you to all volunteers!",
            "📚 Children's Sunday school had an amazing session learning about our heritage.",
            "🤝 Unity in diversity - our community celebrates all festivals with equal joy!",
            "🌱 Planted tulsi saplings in the temple garden. Green initiatives for mother earth!",
            "💫 Meditation session under the stars was truly transcendent. Inner peace achieved!",
            "🎪 Annual community fair planning is underway. Lots of exciting activities planned!",
            "🍯 Preparing prasadam for tomorrow's special pooja. Cooking with love and devotion.",
            "🎯 Youth group organized a successful charity drive. Proud of our young members!",
            "🌙 Full moon meditation was incredibly powerful. The lunar energy was amazing!",
            "🎊 Congratulations to all students who excelled in their exams. Education is divine knowledge!",
            "🕊️ Peace prayer session for world harmony. Let's spread love and understanding.",
            "🎭 Drama group is rehearsing for the upcoming mythology performance. Talented artists!",
            "🌸 Spring festival preparations are in full swing. Colors of joy everywhere!",
            "🧘‍♀️ Yoga classes have been incredibly popular. Mind, body, and soul wellness!",
            "🎨 Art workshop for children was a huge success. Creativity flows through our community!",
        ];

        $this->command->info('Creating demo posts for testing optimizations...');

        // Create 50 posts for comprehensive testing
        for ($i = 1; $i <= 50; $i++) {
            $isAdminPost = false; // No admin posts
            $member = $members->random();
            
            // Randomly assign number of images (0-4 images per post)
            $imageCount = rand(0, 4);
            $postImages = [];
            
            if ($imageCount > 0) {
                // Get random images
                $selectedImages = collect($sampleImages)->random($imageCount)->toArray();
                $postImages = $selectedImages;
            }

            $post = Post::create([
                'member_id' => $member->id,
                'content' => $postContents[($i - 1) % count($postContents)],
                'media' => $postImages,
                'type' => $imageCount > 0 ? 'image' : 'text',
                'is_pinned' => $i <= 3, // Pin first 3 posts
                'is_featured' => $i <= 5, // Feature first 5 posts
                'status' => 'active',
                'comments_count' => rand(0, 20),
                'shares_count' => rand(0, 15),
                'published_at' => now()->subMinutes(rand(1, 10080)), // Random time in last week
                'created_at' => now()->subMinutes(rand(1, 10080)),
                'updated_at' => now()->subMinutes(rand(1, 10080)),
            ]);

            // Create some comments for each post
            $commentCount = rand(0, min(10, $post->comments_count));
            for ($k = 0; $k < $commentCount; $k++) {
                $commentTexts = [
                    "🙏 Beautiful post! Very inspiring.",
                    "Jai Shree Ram! 🚩",
                    "Thanks for sharing this wisdom 🌟",
                    "Om Namah Shivaya 🕉️",
                    "Such a peaceful message 🙏",
                    "Blessed to be part of this community ✨",
                    "Amazing photos! 📸",
                    "This made my day better 😊",
                    "Hare Krishna! 🎵",
                    "Divine blessings to all 🌺",
                ];

                PostComment::create([
                    'post_id' => $post->id,
                    'member_id' => $members->random()->id,
                    'comment' => $commentTexts[rand(0, count($commentTexts) - 1)],
                    'is_approved' => true,
                    'created_at' => now()->subMinutes(rand(1, 5000)),
                ]);
            }

            $this->command->info("Created post {$i}/50 with {$imageCount} images");
        }

        $this->command->info('✅ Demo posts created successfully!');
        $this->command->info('📊 Summary:');
        $this->command->info('   - 50 total posts created');
        $this->command->info('   - 50 member posts');
        $this->command->info('   - Random 0-4 images per post');
        $this->command->info('   - Realistic comments');
        $this->command->info('   - Perfect for testing lazy loading and infinite scroll!');
    }
}
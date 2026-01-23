<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\DefaultAdminSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SystemMemberSeeder::class,
            TestMembersSeeder::class, // Add test members first
            AppSettingsSeeder::class,
            DefaultAdminSeeder::class, // Add default admin user
            DemoPostsSeeder::class, // Add demo posts for testing
            EventSeeder::class, // Add events for testing
            DonationSettingsSeeder::class, // Add donation settings
            NewsSeeder::class, // Add news for testing
        ]);

        $this->command->info('🎉 All seeders completed successfully!');
        $this->command->info('🚀 You can now test the optimizations with demo data!');
    }
}
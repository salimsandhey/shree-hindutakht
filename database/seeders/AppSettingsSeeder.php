<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AppSetting;

class AppSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'key' => 'image_compression_quality',
                'value' => '80',
                'type' => 'integer',
                'description' => 'Image compression quality percentage (1-100)',
                'is_public' => false,
            ],
            [
                'key' => 'max_post_media_files',
                'value' => '5',
                'type' => 'integer',
                'description' => 'Maximum number of media files per post',
                'is_public' => true,
            ],
            [
                'key' => 'max_media_file_size',
                'value' => '50',
                'type' => 'integer',
                'description' => 'Maximum media file size in MB',
                'is_public' => true,
            ],
            [
                'key' => 'donation_bank_name',
                'value' => 'State Bank of India',
                'type' => 'string',
                'description' => 'Bank name for donations',
                'is_public' => true,
            ],
            [
                'key' => 'donation_account_name',
                'value' => 'Shree Hindutakht',
                'type' => 'string',
                'description' => 'Account name for donations',
                'is_public' => true,
            ],
            [
                'key' => 'donation_account_number',
                'value' => '1234567890',
                'type' => 'string',
                'description' => 'Account number for donations',
                'is_public' => true,
            ],
            [
                'key' => 'donation_ifsc_code',
                'value' => 'SBIN0001234',
                'type' => 'string',
                'description' => 'IFSC code for donations',
                'is_public' => true,
            ],
            [
                'key' => 'donation_upi_id',
                'value' => 'shree.hindutakht@paytm',
                'type' => 'string',
                'description' => 'UPI ID for donations',
                'is_public' => true,
            ],
            [
                'key' => 'app_primary_color',
                'value' => '#F47216',
                'type' => 'string',
                'description' => 'Primary color theme for the app',
                'is_public' => true,
            ],
            [
                'key' => 'posts_per_page',
                'value' => '10',
                'type' => 'integer',
                'description' => 'Number of posts to load per page',
                'is_public' => true,
            ],
            [
                'key' => 'enable_post_approval',
                'value' => 'false',
                'type' => 'boolean',
                'description' => 'Whether posts need admin approval before showing',
                'is_public' => false,
            ],
            [
                'key' => 'enable_comment_approval',
                'value' => 'false',
                'type' => 'boolean',
                'description' => 'Whether comments need approval before showing',
                'is_public' => false,
            ],
        ];

        foreach ($settings as $setting) {
            AppSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}

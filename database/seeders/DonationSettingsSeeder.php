<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DonationSetting;

class DonationSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create default donation settings
        DonationSetting::create([
            'bank_name' => 'Test Bank a',
            'account_name' => 'Test Account',
            'account_number' => '9876543210',
            'ifsc_code' => 'TEST0009876',
            'branch' => 'Test Branch',
            'upi_id' => 'test.upi@paytm',
            'message' => 'This is a test donation message.',
            'qr_code_path' => 'donations/yIWX1fqwZR62p83jYE6hcrECeD4ySAnDymZDUYAv.jpg',
        ]);
    }
}
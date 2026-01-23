<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TestMembersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $testMembers = [
            [
                'name' => 'Salim Ahmed',
                'email' => 'salim@hindutakht.com',
                'phone' => '+91-9876543210',
                'address' => 'Mumbai, Maharashtra',
            ],
            [
                'name' => 'Rajesh Kumar',
                'email' => 'rajesh@hindutakht.com',
                'phone' => '+91-9876543211',
                'address' => 'Delhi, India',
            ],
            [
                'name' => 'Priya Sharma',
                'email' => 'priya@hindutakht.com',
                'phone' => '+91-9876543212',
                'address' => 'Pune, Maharashtra',
            ],
            [
                'name' => 'Amit Patel',
                'email' => 'amit@hindutakht.com',
                'phone' => '+91-9876543213',
                'address' => 'Ahmedabad, Gujarat',
            ],
            [
                'name' => 'Sunita Devi',
                'email' => 'sunita@hindutakht.com',
                'phone' => '+91-9876543214',
                'address' => 'Varanasi, Uttar Pradesh',
            ]
        ];

        foreach ($testMembers as $index => $memberData) {
            Member::updateOrCreate(
                ['email' => $memberData['email']],
                [
                    'name' => $memberData['name'],
                    'password' => bcrypt('password123'),
                    'member_id' => 'HT' . str_pad($index + 2, 6, '0', STR_PAD_LEFT), // Start from HT000002
                    'phone' => $memberData['phone'],
                    'address' => $memberData['address'],
                    'status' => 'active',
                    'email_verified_at' => now(),
                    'joined_at' => now()->subDays(rand(1, 365)),
                ]
            );
        }

        $this->command->info('✅ Test members created successfully!');
    }
}
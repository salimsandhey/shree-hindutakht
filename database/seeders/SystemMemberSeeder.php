<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;

class SystemMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a system member for admin posts
        Member::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Shree Hindutakht Official',
                'email' => 'official@hindutakht.com',
                'password' => bcrypt('system123'),
                'member_id' => 'HT000001',
                'status' => 'active',
                'email_verified_at' => now(),
                'joined_at' => now(),
            ]
        );
    }
}

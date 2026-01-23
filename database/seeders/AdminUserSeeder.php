<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create admin user
        Admin::create([
            'name' => 'Shree Hindutakht Official',
            'username' => 'Shree Hindutakht Official',
            'email' => 'admin@hindutakht.com',
            'password' => 'admin123', // Will be hashed by the model
            'role' => 'super_admin',
            'is_active' => true,
            'is_verified' => true,
        ]);
    }
}
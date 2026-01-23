<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update the existing admin user
        DB::table('admins')
            ->where('email', 'admin@hindutakht.com')
            ->update([
                'name' => 'Shree Hindutakht Official',
                'username' => 'Shree Hindutakht Official',
                'password' => Hash::make('admin123'),
                'role' => 'super_admin',
                'is_active' => true,
                'is_verified' => true,
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No need to revert this change
    }
};

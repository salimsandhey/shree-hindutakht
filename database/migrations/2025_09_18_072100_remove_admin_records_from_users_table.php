<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Remove all users with admin roles
        DB::table('users')->where('role', 'super_admin')->delete();
        DB::table('users')->where('role', 'admin')->delete();
        DB::table('users')->where('role', 'moderator')->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No need to restore deleted records
        // The migration is destructive and cannot be rolled back
    }
};

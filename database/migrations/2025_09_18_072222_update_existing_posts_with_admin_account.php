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
        // Get the default admin user
        $admin = DB::table('admins')->where('email', 'admin@hindutakht.com')->first();
        
        if ($admin) {
            // Update all existing posts to be associated with this admin
            DB::table('posts')
                ->where('admin_id', '!=', $admin->id)
                ->orWhereNull('admin_id')
                ->update([
                    'admin_id' => $admin->id,
                    'created_by_admin' => true
                ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Reset all posts to have null admin_id and created_by_admin = false
        DB::table('posts')->update([
            'admin_id' => null,
            'created_by_admin' => false
        ]);
    }
};

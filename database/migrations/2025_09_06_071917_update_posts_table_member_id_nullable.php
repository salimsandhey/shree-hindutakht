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
        // Check if we're using SQLite
        if (DB::getDriverName() === 'sqlite') {
            // SQLite doesn't support MODIFY COLUMN, so we'll skip this migration for SQLite
            return;
        }
        
        // Use raw SQL to modify the column without doctrine/dbal
        DB::statement('ALTER TABLE posts MODIFY COLUMN member_id BIGINT UNSIGNED NULL');
        
        // Update the foreign key constraint
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['member_id']);
            $table->foreign('member_id')->references('id')->on('members')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Check if we're using SQLite
        if (DB::getDriverName() === 'sqlite') {
            // SQLite doesn't support MODIFY COLUMN, so we'll skip this migration for SQLite
            return;
        }
        
        // Revert back to NOT NULL
        DB::statement('ALTER TABLE posts MODIFY COLUMN member_id BIGINT UNSIGNED NOT NULL');
        
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['member_id']);
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
        });
    }
};
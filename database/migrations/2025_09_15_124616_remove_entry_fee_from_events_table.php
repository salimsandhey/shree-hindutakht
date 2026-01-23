<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if the column exists before trying to drop it
        if (Schema::hasColumn('events', 'entry_fee')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropColumn('entry_fee');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->decimal('entry_fee', 8, 2)->default(0);
        });
    }
};
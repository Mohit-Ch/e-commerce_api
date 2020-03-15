<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add Enum for user table
         //DB::statement("ALTER TABLE `courses` ADD COLUMN `type` enum('Course','Sports', 'Skills','Personal Interest','NA') NOT NULL");
         Schema::table('users', function (Blueprint $table) {
            $table->enum('usertype', ['Admin','User'])->default("User");
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::table('users', function (Blueprint $table) {
            // $table->dropColumn('type');
             });
    }
}

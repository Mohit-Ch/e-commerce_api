<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApiTokenToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('api_token', 60)->after('password')->unique()->nullable()->default(null);;
            $table->string('photo', 255)->nullable();
            $table->string('user_Name', 255)->nullable();
            $table->integer('phone_no')->length(10);
            $table->integer('is_active')->length(1)->default(0);
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
            $table->dropColumn('api_token' )->nullable()->change();
            $table->dropColumn('photo')->nullable()->change();
            $table->dropColumn('user_Name')->nullable()->change();
        });
    }
}

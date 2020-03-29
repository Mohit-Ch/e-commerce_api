<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Alterpromocodetable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('promocode', function (Blueprint $table) {
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('promocode', function (Blueprint $table) {
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlteritemDetailsAttributestable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('item_details_attributes', function (Blueprint $table) {
            $table->string('AttributeValue')->nullable();
           
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

        Schema::table('item_details_attributes', function (Blueprint $table) {   
            $table->dropColumn('AttributeValue');
            });
    }
}

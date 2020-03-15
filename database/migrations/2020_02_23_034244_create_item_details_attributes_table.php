<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemDetailsAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_details_attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('itemDetail_id')->unsigned();
            $table->string('AttributeKey')->nullable();
            $table->integer('type')->nullable();
            $table->foreign('itemDetail_id')->references('id')->on('item_deatil');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_details_attributes');
    }
}

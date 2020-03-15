<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemImageContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_image_content', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('itemDetail_id')->unsigned();
            $table->string('description');
            $table->string('imageURL');
            $table->string('size')->nullable();
            $table->string('type');
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
        Schema::dropIfExists('item_image_content');
    }
}

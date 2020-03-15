<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemEditionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_edition', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('itemDetail_id')->unsigned();
            $table->string('itemEditionName');
            $table->decimal('price');
            $table->integer('quantity')->nullable();
            $table->string('remark')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('item_edition');
    }
}

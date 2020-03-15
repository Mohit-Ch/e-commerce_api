<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemDeatilTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_deatil', function (Blueprint $table) {
            $table->increments('id');
            $table->string('itemName')->nullable();
            $table->string('aboutItem')->nullable();
            $table->integer('category_id')->unsigned();
            $table->integer('subcategory_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned(); 
            $table->decimal('width')->nullable();
            $table->decimal('height')->nullable();
            $table->decimal('length')->nullable();
            $table->decimal('weight')->nullable(); 
            $table->integer('dimensionUnit')->nullable();
            $table->integer('wtUnit')->nullable(); 
            $table->foreign('category_id')->references('id')->on('category');
            $table->foreign('subcategory_id')->references('id')->on('subcategory');
            $table->foreign('user_id')->references('id')->on('users'); 
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
        Schema::dropIfExists('item_deatil');
    }
}

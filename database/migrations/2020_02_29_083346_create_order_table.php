<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_no',20);
            $table->integer('user_id')->nullable();
            $table->integer('promocode_id')->unsigned();
            $table->string('description')->nullable();
            $table->enum('type', ['user','gest']);
            $table->enum('status',['placed','conformed','cancelled','delivered']);
            $table->decimal('product_amount',8,2)->default(0);
            $table->decimal('Discount',8,2)->default(0);
            $table->decimal('actual_amount',8,2)->default(0);
            $table->integer('address_id')->nullable();           
            $table->foreign('promocode_id')->references('id')->on('promocode');
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
        Schema::dropIfExists('order');
    }
}

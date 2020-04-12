<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderdetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('order_detail', function (Blueprint $table) {
            $table->integer('quantity');
            $table->decimal('price');
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

        Schema::table('order_detail', function (Blueprint $table) {
            $table->dropColumn('quantity');
            $table->dropColumn('price');
            });
    }
}

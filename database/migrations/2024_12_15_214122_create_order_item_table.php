<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('data_id')->unsigned();
            $table->integer('order_id')->unsigned();

            $table->foreign('data_id', 'fk_order_items_datas')
                ->references('id')
                ->on('datas')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
            $table->foreign('order_id', 'fk_order_items_orders')
                ->references('id')
                ->on('orders')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
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
        Schema::dropIfExists('order_items');
    }
};

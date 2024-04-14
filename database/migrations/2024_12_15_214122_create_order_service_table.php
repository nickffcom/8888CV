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
        Schema::create('order_services', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('ref_id')->unsigned()->nullable();
            $table->string('code')->nullable();
            $table->integer('price_buy');
            $table->integer('user_id')->unsigned();
            $table->integer("type")->unsigned()->nullable()->default(MAIN_SHOP);
            
            $table->foreign('user_id', 'fk_order_service_users')
            ->references('id')
            ->on('users')
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
        Schema::dropIfExists('order_service');
    }
};

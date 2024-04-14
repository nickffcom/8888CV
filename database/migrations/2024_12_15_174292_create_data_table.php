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
        Schema::create('datas', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->json('attr')->nullable();
            $table->integer("service_id")->unsigned()->nullable();
            $table->integer('status')->comment('1: còn , 0 là hết')->default(1);
            
            $table->foreign('service_id')
                  ->references('id')
                  ->on('services');
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
        Schema::dropIfExists('data');
    }
};

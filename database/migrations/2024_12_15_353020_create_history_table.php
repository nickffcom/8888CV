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
        Schema::create('historys', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('action_id')->nullable();
            $table->string('content');
            $table->string('type');
            $table->integer('total_money');
            $table->string('action_content')->nullable();
            $table->integer('user_id')->unsigned();
            
            $table->foreign('user_id', 'fk_history_users')
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
        Schema::dropIfExists('history');
    }
};

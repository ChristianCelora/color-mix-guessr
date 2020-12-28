<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained();
            $table->integer('number');
            $table->unsignedBigInteger('solution');
            $table->string('user_guess')->nullable();
            $table->integer('score')->nullable();
        });

        Schema::table('steps', function (Blueprint $table) {
            $table->foreign('solution')->references('id')->on('colors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('steps');
    }
}

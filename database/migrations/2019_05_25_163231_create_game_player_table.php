<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamePlayerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_player', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('world_killed')->default(0);
            $table->tinyInteger('player_killed')->nullable();
            $table->time('killed_at')->nullable();
            $table->unsignedInteger('game_id');
            $table->foreign('game_id')->references('id')->on('games');
            $table->unsignedInteger('player_id');
            $table->foreign('player_id')->references('id')->on('players');
            $table->unsignedInteger('other_player_id')->nullable();
            $table->foreign('other_player_id')->references('id')->on('players');
            $table->unsignedInteger('means_of_death_id')->nullable();
            $table->foreign('means_of_death_id')->references('id')->on('means_of_death');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_player');
    }
}

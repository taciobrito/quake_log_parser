<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kills', function (Blueprint $table) {
            $table->increments('id');
            $table->time('killed_at')->nullable();
            $table->enum('type_killer', ['player', 'world'])->default('player');
            $table->unsignedInteger('game_id');
            $table->foreign('game_id')->references('id')->on('games');
            $table->unsignedInteger('player_killed_id');
            $table->foreign('player_killed_id')->references('id')->on('players');
            $table->unsignedInteger('player_killer_id')->nullable();
            $table->foreign('player_killer_id')->references('id')->on('players');
            $table->unsignedInteger('means_of_death_id');
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
        Schema::dropIfExists('kills');
    }
}

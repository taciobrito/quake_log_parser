<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kill extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
		'world_killed', 'killed_at', 'game_id', 'player_kill_id', 'player_killed_id', 'means_of_death_id',
  ];

  /**
	* return data game in this kill
  */
  public function game() {
  	return $this->belongsTo('App\Models\Game');
  }

  /**
	* return data player kill in this kill
  */
  public function player_kill() {
  	return $this->belongsTo('App\Models\Player', 'player_kill_id');
  }

  /**
	* return data player killed in this kill
  */
  public function player_killed() {
  	return $this->belongsTo('App\Models\Player', 'player_killed_id');
  }

  /**
	* return mean of death in this kill
  */
  public function mean_of_death() {
  	return $this->belongsTo('App\Models\MeanOfDeath', 'means_of_death_id');
  }
}

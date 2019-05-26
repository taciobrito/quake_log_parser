<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamePlayer extends Model
{
  protected $table = 'game_player';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
		'world_killed', 'killed_at', 'player_killed', 'game_id', 'player_id', 'other_player_id', 'means_of_death_id',
  ];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

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
  	return $this->belongsTo('App\Models\Player', $this->player_killed == true ? 'other_player_id' : 'player_id');
  }

  /**
	* return data player killed in this kill
  */
  public function player_killed() {
  	return $this->belongsTo('App\Models\Player', $this->player_killed == true ? 'player_id' : 'other_player_id');
  }

  /**
	* return mean of death in this kill
  */
  public function mean_of_death() {
  	return $this->belongsTo('App\Models\MeanOfDeath', 'means_of_death_id');
  }
}

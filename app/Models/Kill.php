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
    'killed_at', 'type_killer', 'game_id', 'player_killed_id', 'player_killer_id', 'means_of_death_id',
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
  public function player_killer() {
  	return $this->belongsTo('App\Models\Player', 'player_killer_id');
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
  	return $this->belongsTo('App\Models\MeansOfDeath', 'means_of_death_id');
  }
}

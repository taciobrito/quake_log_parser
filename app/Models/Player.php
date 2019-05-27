<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
  ];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

  /**
	* return all killeds this player
  */
  public function killeds() {
  	return $this->hasMany('App\Models\Kill', 'player_killer_id');
  }

  /**
	* return all deaths this player
  */
  public function deaths() {
  	return $this->hasMany('App\Models\Kill', 'player_killed_id');
  }

  /**
  * return games list of this player
  */
  public function games() {
    return $this->belongsToMany('App\Models\Game', 'game_player', 'player_id', 'game_id');
  }
}

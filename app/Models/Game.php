<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'description', 'start', 'end',
  ];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

  /**
	* return kills list of this game
  */
  public function kills() {
  	return $this->hasMany('App\Models\Kill');
  }

  /**
  * return players list of this game
  */
  public function players() {
    return $this->belongsToMany('App\Models\Player', 'game_player', 'game_id', 'player_id');
  }
}

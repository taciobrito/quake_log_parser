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
  	return $this->hasMany('App\Models\Kill', 'player_kill_id');
  }

  /**
	* return all deaths this player
  */
  public function deaths() {
  	return $this->hasMany('App\Models\Kill', 'player_killed_id');
  }
}

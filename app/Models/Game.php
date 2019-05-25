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
	* return kills list of this game
  */
  public function kills() {
  	return $this->hasMany('App\Models\Kill');
  }
}

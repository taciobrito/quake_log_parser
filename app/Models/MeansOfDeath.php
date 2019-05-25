<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeansOfDeath extends Model
{
	/*
	* table name this model on database
	*/
	protected $table = 'means_of_death';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'description',
  ];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

  /**
	* return all deaths of this mean
  */
  public function kills() {
  	return $this->hasMany('App\Models\Kill', 'means_of_death_id');
  }
}

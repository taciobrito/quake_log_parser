<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Player;
use App\Models\Kill;
use App\Models\MeansOfDeath;

class HomeController extends Controller
{
  public function index() {
  	return view('index');
  }

  public function log() {
  	$log = file(public_path('logs/games.log'));

  	foreach($log as $idx => $row) {
  		echo $idx .' => '. str_replace('<world>', '_world_', $row);
  		echo '<br />';
  	}
  }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;

class ApiController extends Controller
{
  public function kills() {
  	// retorna todos os players do banco de dados com seus registros de kills e deaths
    $players = Player::with(['killeds', 'deaths'])->get();
    // inicia o kills como array vazio
    $kills = [];
    // percorre a coleção de players
    foreach ($players as $player) {
    	$killeds = count($player->killeds);
    	$deaths = count($player->deaths->where('type_killer', '=', 'world'));
    	$kills[] = [
    		'name' => $player->name,
    		'kills' => ($killeds - $deaths),
    	];
    }
    return response()->json($kills, 200);
  }
}

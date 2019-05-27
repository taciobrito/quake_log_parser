<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Player;

class HomeController extends Controller
{
	// retorna a view responsável por listar o rankink dos players
  public function index() {
  	return view('index');
  }

  // retorna os detalhes de todos os games de forma resumida
  public function games() {
    // retorna todos os games do banco de dados
    $games = Game::with(['players', 'kills.player_killer', 'kills.player_killed'])->get();
    // inicia um array vazio
    $detalhes = [];
    // percorre a coleção de games
    foreach ($games as $key => $game) {
      // inicia um array vazio de kills
      $kills = [];
      // verifica se há kills no game
      if (count($game->kills) > 0) {
        // percorre a coleção de kills
        foreach ($game->kills as $kill) {
          // verifica se existe kill
          if ($kill != null) {
            // verifica se houve um matador
            if ($kill->player_killer_id != null) {
              // verifica se existe player matador no array de kills
              if (!isset($kills[$kill->player_killer->name])) {
                // adiciona player matador no array de kills com valor incial de 1 kill
                $kills[$kill->player_killer->name] = 1;
              } else {
                // adiciona mais um kill para o player
                $kills[$kill->player_killer->name]++;
              }
            }
            // verifica se existe player morto no array de kills
            if (!isset($kills[$kill->player_killed->name])) {
              // adiciona player morto no array de kills com valor incial de 0 kills
              $kills[$kill->player_killed->name] = 0;
            } else {
              // subtrai um kill do player, caso o matador seja o world
              if ($kill->type_killer == 'world') {
                $kills[$kill->player_killed->name]--;
              }
            }
          }
        }
      }
      $detalhes[str_replace(' ', '_', strtolower($game->description))] = [
        'total_kills' => count($game->kills),
        'players' => $game->players->map(function ($player) {
          return $player->name;
        }),
        'kills' => $kills,
      ];
    }
    dd($detalhes);
  }
}

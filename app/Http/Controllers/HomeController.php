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

  // retorna o relatório de mortes agrupados por seu motivo
  public function relatorio() {
    $games = Game::with(['kills.mean_of_death'])->get();
    $report = [];
    foreach ($games as $key => $game) {
      $kills_by_means = [];
      foreach ($game->kills as $kill) {
        if(!isset($kills_by_means[$kill->mean_of_death->description])) {
          $kills_by_means[$kill->mean_of_death->description] = 1;
        } else {
          $kills_by_means[$kill->mean_of_death->description]++;
        }
      }
      $report[] = [
        'game' => $game->description,
        'kills_by_means' => $this->array_sort($kills_by_means, 'kills_by_means', SORT_DESC),
      ];
    }

    return view('relatorio', compact('report'));
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

  function array_sort($array, $on, $order=SORT_ASC)
  {
    $new_array = array();
    $sortable_array = array();
    if (count($array) > 0) {
      foreach ($array as $k => $v) {
        if (is_array($v)) {
          foreach ($v as $k2 => $v2) {
            if ($k2 == $on) {
              $sortable_array[$k] = $v2;
            }
          }
        } else {
          $sortable_array[$k] = $v;
        }
      }
      switch ($order) {
        case SORT_ASC:
          asort($sortable_array);
        break;
        case SORT_DESC:
          arsort($sortable_array);
        break;
      }
      foreach ($sortable_array as $k => $v) {
        $new_array[$k] = $array[$k];
      }
    }
    return $new_array;
  }
}

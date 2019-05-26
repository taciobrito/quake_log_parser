<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Player;
use App\Models\GamePlayer;
use App\Models\MeansOfDeath;

class HomeController extends Controller
{
	// Atributo para receber o arquivo de log
	private $log;
	// Atributo para receber os dados dos games extraídos do log
	private $games;
	// Atributo para receber a lista de players extraídos do log
	private $players;
	// Atributo que indexa o game que está sendo registrado no momento
	private $idx_game;

	function __construct() {
		// inicializa como array vazio
 		$this->games = [];
 		// inicializa como array vazio
 		$this->players = [];
		// inicia como valor 0 (zero), pois não foi percorrido nenhum game
		$this->idx_game = 0;
	}

	// retorna a view responsável por listar o rankink dos players
  public function index() {
  	return view('index');
  }

  // responsável por iniciar o processo de extração dos dados do log
  public function log() {
  	// atribui o arquivo games.log como array
  	$this->log = file(public_path('logs/games.log'));

  	echo "<pre>";
  	// percorre os dados do log linha por linha
  	foreach($this->log as $idx => $row) {
  		// chama a função responsável por registrar o início de cada game e retorna true ou false
  		$isGameStart = $this->gameStart($idx, $row);
  		$isGameEnd = $this->gameEnd($idx, $row);
  		// verifica se é uma linha de início ou fim do game
  		if (!$isGameStart && !$isGameEnd) {
  			// caso verdadeira a condição:
  			$players = $this->getPlayers($idx, $row);
  			if(count($players) > 0) print_r($players);
  		}
  	}
  	print_r($this->games);
  	echo "<pre>";
  }
  // Fim log()

  // função responsável por registrar o início de cada game
  private function gameStart($idx, $row) {
  	// retorna um array de ocorrência caso seja o log que inicia um game
  	preg_match('/InitGame/', $row, $match);
  	// verifica se inicia o jogo
  	if (count($match) > 0) {
  		// seta o índice do game corrente
  		// seta o índice do game corrente
  		$this->idx_game++;
  		// cria no array de games, um novo game com seus dados iniciais
  		$this->games['game_'.$this->idx_game]['game'] = [
  			'description' => 'Game '.$this->idx_game,
  			'start' => $this->getTime($row),
  		];
  		// chama função responsável por registrar o fim do game
  		$this->gameEnd($idx);
  	}
  }
  // Fim gameStart()

  // função responsável pro registrar o fim do game
  private function gameEnd($idx, $row = null) {
  	// verifica se foi enviada uma linha do log
  	if ($row) {
  		// verifica se nessa linha há a ocorrência que finaliza o game
  		preg_match('/ShutdownGame/', $row, $match);
  		// verifica se finaliza o game
  		if(count($match) > 0) {
  			// seta a hora que finalizou o game
  			$this->games['game_'.$this->idx_game]['game']['end'] = $this->getTime($row);
  		}
  	} else {
  		// verifica se existe um game anterior ao atual
	  	if (isset($this->games['game_'.($this->idx_game -1)])) {
	  		// verifica se o game anterior possui a hora em que foi finalizado
	  		if (!isset($this->games['game_'.($this->idx_game -1)]['game']['end'])) {
	  			// caso não tenha finalizado, seta a hora que finalizou o game anterior
	  			$this->games['game_'.($this->idx_game -1)]['game']['end'] = $this->getTime($this->log[($idx -1)]);
	  		}
	  	}
  	}
  }
  // Fim gameEnd()

  // função que retorna a hora registrada da linha do log
  private function getTime($row) {
  	// resgata a hora registrada na linha do log
  	preg_match('/\d?\d:\d\d/', $row, $match);
  	// retorna a hora do log ou nulo, caso não tenha sido encontrada
  	return $match[0] ?? null;
  }
  // Fim getTime()

  // função que retorna o nome do(s) players caso exista na linha do log
  private function getPlayers($idx, $row) {
  	// :\s([^:]+)\skilled\s(.*?)\sby\s[a-zA-Z_]+
  	preg_match('/:\s([^:]+)\skilled\s(.*?)\sby\s[a-zA-Z_]+/', $row, $match_players);
  	if (count($match_players) > 0) {
  		if ($match_players[1] == '<world>') {
  			return [
  				$match_players[2],
  			];
  		} else {
  			return [
  				$match_players[1],
  				$match_players[2],
  			];
  		}
  	} else {
  		preg_match('/\sn\\\[a-zA-Z_\s]+/', $row, $match_players);
  		if (count($match_players) > 0) {
  			return [
  				str_replace('n\\', '', $match_players),
  			];
  		}
  	}
  	return [];
  }
  // Fim getPlayers()
}

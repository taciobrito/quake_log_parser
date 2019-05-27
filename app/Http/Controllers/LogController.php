<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Player;
use App\Models\Kill;
use App\Models\MeansOfDeath;

class LogController extends Controller
{
	// Atributo para receber o arquivo de log
	private $log;
	// Atributo para receber os dados dos games extraídos do log
	private $games;
	// Atributo para receber a lista de players extraídos do log
	private $players;
	// Atributo para receber a lista de means of death do banco de dados
	private $means_of_death;
	// Atributo que indexa o game que está sendo registrado no momento
	private $idx_game;

	function __construct() {
		// inicializa como array vazio
 		$this->games = [];
 		// inicializa como array vazio
 		$this->players = [];
 		// recebe os dados do banco 
 		$this->means_of_death = MeansOfDeath::all();
		// inicia como valor 0 (zero), pois não foi percorrido nenhum game
		$this->idx_game = 0;
	}

  // responsável por iniciar o processo de extração dos dados do log
  public function index() {
  	// atribui o arquivo games.log como array
  	$this->log = file(public_path('logs/games.log'));

  	// echo "<pre>";
  	// percorre os dados do log linha por linha
  	foreach($this->log as $idx => $row) {
  		// chama a função responsável por registrar o início de cada game e retorna true ou false
  		$isGameStart = $this->gameStart($idx, $row);
  		$isGameEnd = $this->gameEnd($idx, $row);
  		// verifica se é uma linha de início ou fim do game
  		if (!$isGameStart && !$isGameEnd) {
  			// caso verdadeira a condição:
  			// resgata os players encontrados
  			$players = $this->getPlayersLog($idx, $row);
  			if(count($players) > 0) {
  				// percorre o array de players
  				foreach ($players as $player) {
  					// verifica se o player existe no array global de players, caso não, adiciona
  					if (!isset($this->players[$player])) {
  						$this->players[$player] = Player::create(['name' => $player]);
  					}
  					// verifica se o player existe no array de players do game, caso não, adiciona
  					if (!isset($this->games['game_'.$this->idx_game]['players'][$player])) {
  						$this->games['game_'.$this->idx_game]['players'][$player] = $this->players[$player]->id;
  					}
  				}
  			}
  			$kill = $this->getKill($row);
  			if ($kill) {
  				array_push($this->games['game_'.$this->idx_game]['kills'], $kill);
  			}
  		}
  	}
  	// echo "<pre>";
  }
  // Fim log()
  // função responsável por registrar o início de cada game
  private function gameStart($idx, $row) {
  	// retorna um array de ocorrência caso seja o log que inicia um game
  	preg_match('/InitGame/', $row, $match);
  	// verifica se inicia o jogo
  	if (count($match) > 0) {
  		// seta o índice do game corrente
  		$this->idx_game++;
  		// cria no array de games, um novo game com seus dados iniciais
  		$this->games['game_'.$this->idx_game] = [
  			'game' => [
	  			'description' => 'Game '.$this->idx_game,
	  			'start' => $this->getTime($row),
  			],
  			'players' => [],
  			'kills' => [],
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
  			// chama função que salva o game
  			$this->saveGame($this->games['game_'.$this->idx_game]);
  		}
  	} else {
  		// verifica se existe um game anterior ao atual
	  	if (isset($this->games['game_'.($this->idx_game -1)])) {
	  		// verifica se o game anterior possui a hora em que foi finalizado
	  		if (!isset($this->games['game_'.($this->idx_game -1)]['game']['end'])) {
	  			// caso não tenha finalizado, seta a hora que finalizou o game anterior
	  			$this->games['game_'.($this->idx_game -1)]['game']['end'] = $this->getTime($this->log[($idx -1)]);
	  			// chama função que salva o game
	  			$this->saveGame($this->games['game_'.($this->idx_game -1)]);
	  		}
	  	}
  	}
  }
  // Fim gameEnd()

  // função que retorna o nome do(s) players caso exista na linha do log
  private function getPlayersLog($idx, $row) {
  	// :\s([^:]+)\skilled\s(.*?)\sby\s[a-zA-Z_]+
  	preg_match('/:\s([^:]+)\skilled\s(.*?)\sby\s[a-zA-Z_]+/', $row, $match_players);
  	if (count($match_players) > 0) {
  		if ($match_players[1] == '<world>') {
  			return [
  				trim($match_players[2]),
  			];
  		} else {
  			return [
  				trim($match_players[1]),
  				trim($match_players[2]),
  			];
  		}
  	} else {
  		preg_match('/\sn\\\[a-zA-Z_\s]+/', $row, $match_players);
  		if (count($match_players) > 0) {
  			return [
  				trim(str_replace('n\\', '', $match_players[0])),
  			];
  		}
		}
	}
  // Fim getPlayersLog()
  
  // função que retorna o kill registrado da linha do log
  private function getKill($row) {
  	// regex que resgata o registro de kill
  	preg_match('/:\s([^:]+)\skilled\s(.*?)\sby\s[a-zA-Z_]+/', $row, $match);
  	// verifica se encontrou o kill
  	// : <world> killed Isgalamido by MOD_TRIGGER_HURT
  	if (count($match) > 0) {
  		// chama a função que resgata quem matou e atribui a variavel $killer
			$killer = trim($this->getKiller($match[0]));
  		$kill = [
  			'killed_at' => $this->getTime($row),
  			'type_killer' => $this->getTypeKiller($killer),
  			'player_killed_id' => $this->players[trim($this->getKilled($match[0]))]->id,
  			'means_of_death_id' => $this->getMeansOfDeathFromDB(trim($this->getMeansOfDeath($match[0])))->id,
  		];
  		if ($kill['type_killer'] == 'player') {
  			$kill['player_killer_id'] = $this->players[$killer]->id;
  		}
  	}
  	return $kill ?? null;
  }
  // Fim getKill()

  // função que retorna o killer
  private function getKiller($kill) {
  	// regex que resgata o player que matou
  	preg_match('/(?<=:\s)(.*?)(?=\skilled)/', $kill, $match);
  	// verifica se encontrou o killer
  	return $match[0] ?? null;
  }
  // Fim getKiller()

  // função que retorna o killed
  private function getKilled($kill) {
  	// regex que resgata o player que morreu
  	preg_match('/(?<=killed\s)(.*?)(?=\sby)/', $kill, $match);
  	// verifica se encontrou o killer
  	return $match[0] ?? null;
  }
  // Fim getKilled()

  // função que retorna o MeansOfDeath
  private function getMeansOfDeath($kill) {
  	// regex que resgata o motivo da morte
  	preg_match('/(?<=by\s)(.*?)(?=$)/', $kill, $match);
  	// verifica se encontrou o killer
  	return $match[0] ?? null;
  }
  // Fim getMeansOfDeath()

  // função que retorna o MeansOfDeath do banco de dados
  private function getMeansOfDeathFromDB($description) {
  	// retorna conforme a descrição do registro
  	return $this->means_of_death->firstWhere('description', $description);
  }
  // Fim getMeansOfDeathFromDB()

  private function getTypeKiller($killer) {
  	return $killer == '<world>' ? 'world' : 'player';
  }

  // função que retorna a hora registrada da linha do log
  private function getTime($row) {
  	// resgata a hora registrada na linha do log
  	preg_match('/\d?\d:\d\d/', $row, $match);
  	// retorna a hora do log ou nulo, caso não tenha sido encontrada
  	return $match[0] ?? null;
  }
  // Fim getTime()

  // função que salva o game
  private function saveGame($data) {
  	// cria o game no banco de dados
  	$game = Game::create($data['game']);
  	// associa os players do game registrando no banco de dados
  	$game->players()->sync($data['players']);
  	// percorre o array de kills
  	foreach ($data['kills'] as $kill) {
  		// seta o id do game no kill
  		$kill['game_id'] = $game->id;
  		// cria um registro na tabela de kill
  		Kill::create($kill);
  	}

  	echo date('H:i') .' - '. $game->description .' criado com sucesso!';
  	echo '<br />';
  }
  // Fim saveGame()
}

<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Http\Controllers\LogController;

class LogTest extends TestCase
{
    /**
     * Testa se é retornado um array contendo um player a partir do log ClientUserinfoChanged
     * @return void
     */
    public function testGetOnePlayerLog()
    {
    	$log = new LogController();
    	$players = $log->getPlayersLog(1, '21:15 ClientUserinfoChanged: 2 n\Isgalamido\t\0\model\uriel/zael\hmodel\uriel/zael\g_redteam\\g_blueteam\\c1\5\c2\5\hc\100\w\0\l\0\tt\0\tl\0');
      $this->assertEquals($players, ['Isgalamido']);
    }

    /**
     * Testa se é retornado um array contendo dois players a partir do log Kill
     * @return void
     */
    public function testGetTwoPlayersLog()
    {
    	$log = new LogController();
    	$players = $log->getPlayersLog(1, '22:06 Kill: 2 3 7: Isgalamido killed Mocinha by MOD_ROCKET_SPLASH');
      $this->assertEquals($players, ['Isgalamido', 'Mocinha']);
    }

    /**
     * Verifica se quem matou foi um player
     * @return void
     */
    public function testTypeKiller()
    {
    	$log = new LogController();
    	$row = '22:06 Kill: 2 3 7: Isgalamido killed Mocinha by MOD_ROCKET_SPLASH';
    	preg_match('/:\s([^:]+)\skilled\s(.*?)\sby\s[a-zA-Z_]+/', $row, $match);
    	$killer = trim($log->getKiller($match[0]));
    	$type_killer = $log->getTypeKiller($killer);
      $this->assertEquals($type_killer, 'player');
    }

    /**
     * Verifica se retorna null ao ler o log de kill
     * @return void
     */
    public function testLogKillIsNull()
    {
    	$log = new LogController();
    	$row = '21:15 ClientUserinfoChanged: 2 n\Isgalamido\t\0\model\uriel/zael\hmodel\uriel/zael\g_redteam\\g_blueteam\\c1\5\c2\5\hc\100\w\0\l\0\tt\0\tl\0';
      $this->assertNull($log->getKill($row));
    }

    /**
     * Verifica se a expressao regex é valida conforme seu resultado
     * @return void
     */
    public function testRegExpIsValidWithResult()
    {
    	$log = new LogController();
    	$row = '22:06 Kill: 2 3 7: Isgalamido killed Mocinha by MOD_ROCKET_SPLASH';
    	preg_match('/:\s([^:]+)\skilled\s(.*?)\sby\s[a-zA-Z_]+/', $row, $match);
    	$regex = '/(?<=:\s)(.*?)(?=\skilled)/';
      $this->assertRegExp($regex, $match[0]);
    }
}

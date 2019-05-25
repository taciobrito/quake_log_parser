<?php

use Illuminate\Database\Seeder;

class MeansOfDeathSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      foreach ($this->data() as $data) {
      	\App\Models\MeansOfDeath::create([
      		'description' => $data,
      	]);
      }
    }

    public function data()
    {
    	return [
    		'MOD_UNKNOWN',
				'MOD_SHOTGUN',
				'MOD_GAUNTLET',
				'MOD_MACHINEGUN',
				'MOD_GRENADE',
				'MOD_GRENADE_SPLASH',
				'MOD_ROCKET',
				'MOD_ROCKET_SPLASH',
				'MOD_PLASMA',
				'MOD_PLASMA_SPLASH',
				'MOD_RAILGUN',
				'MOD_LIGHTNING',
				'MOD_BFG',
				'MOD_BFG_SPLASH',
				'MOD_WATER',
				'MOD_SLIME',
				'MOD_LAVA',
				'MOD_CRUSH',
				'MOD_TELEFRAG',
				'MOD_FALLING',
				'MOD_SUICIDE',
				'MOD_TARGET_LASER',
				'MOD_TRIGGER_HURT',
				'MISSIONPACK',
				'MOD_NAIL',
				'MOD_CHAINGUN',
				'MOD_PROXIMITY_MINE',
				'MOD_KAMIKAZE',
				'MOD_JUICED',
				'MOD_GRAPPLE',
    	];
    }
}

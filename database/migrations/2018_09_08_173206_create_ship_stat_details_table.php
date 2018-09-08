<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipStatDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ship_stat_details', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('type', 9);
            
            $table->unsignedInteger('max_xp');
            $table->unsignedInteger('damage_to_buildings');
            
            /*main_battery*/
            $table->smallInteger('main_battery_max_frags_battle');
            $table->unsignedInteger('main_battery_frags');
            $table->unsignedInteger('main_battery_hits');
            $table->unsignedInteger('main_battery_shots');
            
            $table->unsignedInteger('suppressions_count');
            $table->unsignedInteger('max_damage_scouting');
            $table->unsignedInteger('art_agro');
            $table->unsignedInteger('ships_spotted');
            
            /*second_battery*/
            $table->smallInteger('second_battery_max_frags_battle');
            $table->unsignedInteger('second_battery_frags');
            $table->unsignedInteger('second_battery_hits');
            $table->unsignedInteger('second_battery_shots');
            
            $table->unsignedInteger('xp');
            $table->unsignedInteger('survived_battles');
            $table->unsignedInteger('dropped_capture_points');
            $table->unsignedInteger('max_damage_dealt_to_buildings');
            $table->unsignedInteger('torpedo_agro');
            $table->unsignedInteger('draws');
            $table->unsignedInteger('battles_since_510');
            $table->unsignedInteger('planes_killed');
            $table->unsignedInteger('battles');
            $table->smallInteger('max_ships_spotted');
            $table->unsignedInteger('team_capture_points');
            $table->unsignedInteger('frags');
            $table->unsignedInteger('damage_scouting');
            $table->unsignedInteger('max_total_agro');
            $table->unsignedInteger('max_frags_battle');
            $table->unsignedInteger('capture_points');
	
            /*ramming*/
            $table->smallInteger('ramming_max_frags_battle');
            $table->unsignedInteger('ramming_frags');
            
            /*torpedoes*/
            $table->smallInteger('torpedoes_max_frags_battle');
            $table->unsignedInteger('torpedoes_frags');
            $table->unsignedInteger('torpedoes_hits');
            $table->unsignedInteger('torpedoes_shots');

            /*aircraft*/
            $table->smallInteger('aircraft_max_frags_battle');
            $table->unsignedInteger('aircraft_frags');
            
            $table->unsignedInteger('survived_wins');
            $table->unsignedInteger('max_damage_dealt');
            $table->unsignedInteger('wins');
            $table->unsignedInteger('losses');
            $table->unsignedInteger('damage_dealt');
            $table->smallInteger('max_planes_killed');
            $table->unsignedInteger('max_suppressions_count');
            $table->unsignedInteger('team_dropped_capture_points');
            $table->unsignedInteger('battles_since_512');
            
            /* GENERIC*/
            $table->timestamp('last_battle_time');
            $table->unsignedInteger('account_id')->index();
            $table->timestamp('wg_updated_at');
            $table->unsignedInteger('ship_id')->index();
            
            $table->foreign('account_id')->references('id')->on('players');
            //TODO:  $table->foreign('ship_id')
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ship_stat_details');
    }
}

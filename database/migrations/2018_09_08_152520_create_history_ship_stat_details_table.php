<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryShipStatDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_ship_stat_details', function (Blueprint $table) {
            $table->increments('id');
            
            /* GENERIC*/
            $table->unsignedInteger('account_id')->index();
            $table->unsignedInteger('ship_id')->index();
            $table->string('type', 9)->index();
            $table->date('date')->index();

            $table->timestamp('last_battle_time')->nullable();
            $table->timestamp('wg_updated_at')->nullable();
            
            
            $table->unsignedInteger('max_xp')->nullable();
            $table->unsignedInteger('damage_to_buildings')->nullable();
            
            /*main_battery*/
            $table->smallInteger('main_battery_max_frags_battle')->nullable();
            $table->unsignedInteger('main_battery_frags')->nullable();
            $table->unsignedInteger('main_battery_hits')->nullable();
            $table->unsignedInteger('main_battery_shots')->nullable();
            
            $table->unsignedInteger('suppressions_count')->nullable();
            $table->unsignedInteger('max_damage_scouting')->nullable();
            $table->unsignedInteger('art_agro')->nullable();
            $table->unsignedInteger('ships_spotted')->nullable();
            
            /*second_battery*/
            $table->smallInteger('second_battery_max_frags_battle')->nullable();
            $table->unsignedInteger('second_battery_frags')->nullable();
            $table->unsignedInteger('second_battery_hits')->nullable();
            $table->unsignedInteger('second_battery_shots')->nullable();
            
            $table->unsignedInteger('xp')->nullable();
            $table->unsignedInteger('survived_battles')->nullable();
            $table->unsignedInteger('dropped_capture_points')->nullable();
            $table->unsignedInteger('max_damage_dealt_to_buildings')->nullable();
            $table->unsignedInteger('torpedo_agro')->nullable();
            $table->unsignedInteger('draws')->nullable();
            $table->unsignedInteger('battles_since_510')->nullable();
            $table->unsignedInteger('planes_killed')->nullable();
            $table->unsignedInteger('battles')->nullable();
            $table->smallInteger('max_ships_spotted')->nullable();
            $table->unsignedInteger('team_capture_points')->nullable();
            $table->unsignedInteger('frags')->nullable();
            $table->unsignedInteger('damage_scouting')->nullable();
            $table->unsignedInteger('max_total_agro')->nullable();
            $table->unsignedInteger('max_frags_battle')->nullable();
            $table->unsignedInteger('capture_points')->nullable();
	
            /*ramming*/
            $table->smallInteger('ramming_max_frags_battle')->nullable();
            $table->unsignedInteger('ramming_frags')->nullable();
            
            /*torpedoes*/
            $table->smallInteger('torpedoes_max_frags_battle')->nullable();
            $table->unsignedInteger('torpedoes_frags')->nullable();
            $table->unsignedInteger('torpedoes_hits')->nullable();
            $table->unsignedInteger('torpedoes_shots')->nullable();

            /*aircraft*/
            $table->smallInteger('aircraft_max_frags_battle')->nullable();
            $table->unsignedInteger('aircraft_frags')->nullable();
            
            $table->unsignedInteger('survived_wins')->nullable();
            $table->unsignedInteger('max_damage_dealt')->nullable();
            $table->unsignedInteger('wins')->nullable();
            $table->unsignedInteger('losses')->nullable();
            $table->unsignedInteger('damage_dealt')->nullable();
            $table->smallInteger('max_planes_killed')->nullable();
            $table->unsignedInteger('max_suppressions_count')->nullable();
            $table->unsignedInteger('team_dropped_capture_points')->nullable();
            $table->unsignedInteger('battles_since_512')->nullable();
            
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
        Schema::dropIfExists('history_ship_stat_details');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ship_stats', function (Blueprint $table) {
            $table->increments('id');
            
//            Win Rate: 76.47%
//            Avg Damage: 59310
//            Avg frags: 0.82
//            Avg. experience
//            Avg. planes Destroyed
            
            /* GENERIC*/
            
            $table->unsignedInteger('account_id')->index();
            $table->unsignedInteger('ship_id')->index();
            
            $table->unsignedInteger('battles')->nullable();
            $table->timestamp('last_battle_time')->nullable();
            $table->timestamp('wg_updated_at')->nullable();
            $table->unsignedInteger('distance')->nullable();
            
            
            //"club"
            
            //"oper_div"
//            $table->decimal(        'oper_div_wr', 5, 2)->nullable();
//            $table->unsignedInteger('oper_div_pr')->nullable();
//            $table->unsignedInteger('oper_div_wtr')->nullable();
//            $table->unsignedInteger('oper_div_battles')->nullable();
//            $table->timestamp(      'oper_div_last_battle_time')->nullable();
//            $table->unsignedInteger('oper_div_ship_stat_details_id')->nullable()->index();
            
            //"oper_div_hard"
            
            //"oper_solo"
//            $table->decimal(        'oper_solo_wr', 5, 2)->nullable();
//            $table->unsignedInteger('oper_solo_pr')->nullable();
//            $table->unsignedInteger('oper_solo_wtr')->nullable();
//            $table->unsignedInteger('oper_solo_battles')->nullable();
//            $table->timestamp(      'oper_solo_last_battle_time')->nullable();
//            $table->unsignedInteger('oper_solo_ship_stat_details_id')->nullable()->index();
            
            //"pve"
            $table->decimal(        'pve_wr', 5, 2)->nullable();
            $table->unsignedInteger('pve_pr')->nullable();
            $table->unsignedInteger('pve_wtr')->nullable();
            $table->unsignedInteger('pve_battles')->nullable();
            $table->timestamp(      'pve_last_battle_time')->nullable();
            $table->unsignedInteger('pve_ship_stat_details_id')->nullable()->index();
            
            //"pve_div2"
            $table->decimal(        'pve_div2_wr', 5, 2)->nullable();
            $table->unsignedInteger('pve_div2_pr')->nullable();
            $table->unsignedInteger('pve_div2_wtr')->nullable();
            $table->unsignedInteger('pve_div2_battles')->nullable();
            $table->timestamp(      'pve_div2_last_battle_time')->nullable();
            $table->unsignedInteger('pve_div2_ship_stat_details_id')->nullable()->index();
            
            //"pve_div3"
            $table->decimal(        'pve_div3_wr', 5, 2)->nullable();
            $table->unsignedInteger('pve_div3_pr')->nullable();
            $table->unsignedInteger('pve_div3_wtr')->nullable();
            $table->unsignedInteger('pve_div3_battles')->nullable();
            $table->timestamp(      'pve_div3_last_battle_time')->nullable();
            $table->unsignedInteger('pve_div3_ship_stat_details_id')->nullable()->index();
            
            //"pve_solo"
            $table->decimal(        'pve_solo_wr', 5, 2)->nullable();
            $table->unsignedInteger('pve_solo_pr')->nullable();
            $table->unsignedInteger('pve_solo_wtr')->nullable();
            $table->unsignedInteger('pve_solo_battles')->nullable();
            $table->timestamp(      'pve_solo_last_battle_time')->nullable();
            $table->unsignedInteger('pve_solo_ship_stat_details_id')->nullable()->index();
            
            //"pvp"
            $table->decimal(        'pvp_wr', 5, 2)->nullable();
            $table->unsignedInteger('pvp_pr')->nullable();
            $table->unsignedInteger('pvp_wtr')->nullable();
            $table->unsignedInteger('pvp_battles')->nullable();
            $table->timestamp(      'pvp_last_battle_time')->nullable();
            $table->unsignedInteger('pvp_ship_stat_details_id')->nullable()->index();
            
            //"pvp_div2"
            $table->decimal(        'pvp_div2_wr', 5, 2)->nullable();
            $table->unsignedInteger('pvp_div2_pr')->nullable();
            $table->unsignedInteger('pvp_div2_wtr')->nullable();
            $table->unsignedInteger('pvp_div2_battles')->nullable();
            $table->timestamp(      'pvp_div2_last_battle_time')->nullable();
            $table->unsignedInteger('pvp_div2_ship_stat_details_id')->nullable()->index();
            
            //"pvp_div3"
            $table->decimal(        'pvp_div3_wr', 5, 2)->nullable();
            $table->unsignedInteger('pvp_div3_pr')->nullable();
            $table->unsignedInteger('pvp_div3_wtr')->nullable();
            $table->unsignedInteger('pvp_div3_battles')->nullable();
            $table->timestamp(      'pvp_div3_last_battle_time')->nullable();
            $table->unsignedInteger('pvp_div3_ship_stat_details_id')->nullable()->index();
            
            //"pvp_solo"
            $table->decimal(        'pvp_solo_wr', 5, 2)->nullable();
            $table->unsignedInteger('pvp_solo_pr')->nullable();
            $table->unsignedInteger('pvp_solo_wtr')->nullable();
            $table->unsignedInteger('pvp_solo_battles')->nullable();
            $table->timestamp(      'pvp_solo_last_battle_time')->nullable();
            $table->unsignedInteger('pvp_solo_ship_stat_details_id')->nullable()->index();
            
            //"rank_div2"
            
            //"rank_div3"
            
            //"rank_solo"
            $table->decimal(        'rank_solo_wr', 5, 2)->nullable();
            $table->unsignedInteger('rank_solo_pr')->nullable();
            $table->unsignedInteger('rank_solo_wtr')->nullable();
            $table->unsignedInteger('rank_solo_battles')->nullable();
            $table->timestamp(      'rank_solo_last_battle_time')->nullable();
            $table->unsignedInteger('rank_solo_ship_stat_details_id')->nullable()->index();
            
            
            $table->foreign('account_id')->references('id')->on('players');
            //TODO:  $table->foreign('ship_id')
            
//            $table->foreign('oper_div_ship_stat_details_id')->references('id')->on('ship_stat_details');
//            $table->foreign('oper_solo_ship_stat_details_id')->references('id')->on('ship_stat_details');
            
            $table->foreign('pve_ship_stat_details_id')->references('id')->on('ship_stat_details');
            $table->foreign('pve_div2_ship_stat_details_id')->references('id')->on('ship_stat_details');
            $table->foreign('pve_div3_ship_stat_details_id')->references('id')->on('ship_stat_details');
            $table->foreign('pve_solo_ship_stat_details_id')->references('id')->on('ship_stat_details');
            
            $table->foreign('pvp_div2_ship_stat_details_id')->references('id')->on('ship_stat_details');
            $table->foreign('pvp_div3_ship_stat_details_id')->references('id')->on('ship_stat_details');
            $table->foreign('pvp_solo_ship_stat_details_id')->references('id')->on('ship_stat_details');
            
            $table->foreign('rank_solo_ship_stat_details_id')->references('id')->on('ship_stat_details');

            
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
        Schema::dropIfExists('ship_stats');
    }
}

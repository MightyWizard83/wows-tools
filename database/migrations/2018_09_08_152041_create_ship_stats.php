<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipStats extends Migration
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
            
            //"club"
            
            //"oper_div"
            $table->unsignedInteger('oper_div_pr')->nullable();
            $table->unsignedInteger('oper_div_wtr')->nullable();
            $table->unsignedInteger('oper_div_battles')->nullable();
            $table->timestamp('oper_div_last_battle_time')->nullable();
            $table->unsignedInteger('oper_div_ship_stat_details_id')->nullable()->index();
            
            //"oper_div_hard"
            
            //"oper_solo"
            $table->unsignedInteger('oper_solo_pr')->nullable();
            $table->unsignedInteger('oper_solo_wtr')->nullable();
            $table->unsignedInteger('oper_solo_battles')->nullable();
            $table->timestamp(      'oper_solo_last_battle_time')->nullable();
            $table->unsignedInteger('oper_solo_ship_stat_details_id')->nullable()->index();
            
            //"pve"
            $table->unsignedInteger('pve_pr')->nullable();
            $table->unsignedInteger('pve_wtr')->nullable();
            $table->unsignedInteger('pve_battles')->nullable();
            $table->timestamp(      'pve_last_battle_time')->nullable();
            $table->unsignedInteger('pve_ship_stat_details_id')->nullable()->index();
            
            //"pve_div2"
            $table->unsignedInteger('pve_div2_pr')->nullable();
            $table->unsignedInteger('pve_div2_wtr')->nullable();
            $table->unsignedInteger('pve_div2_battles')->nullable();
            $table->timestamp(      'pve_div2_last_battle_time')->nullable();
            $table->unsignedInteger('pve_div2_ship_stat_details_id')->nullable()->index();
            
            //"pve_div3"
            $table->unsignedInteger('pve_div3_pr')->nullable();
            $table->unsignedInteger('pve_div3_wtr')->nullable();
            $table->unsignedInteger('pve_div3_battles')->nullable();
            $table->timestamp(      'pve_div3_last_battle_time')->nullable();
            $table->unsignedInteger('pve_div3_ship_stat_details_id')->nullable()->index();
            
            //"pve_solo"
            $table->unsignedInteger('pve_solo_pr')->nullable();
            $table->unsignedInteger('pve_solo_wtr')->nullable();
            $table->unsignedInteger('pve_solo_battles')->nullable();
            $table->timestamp(      'pve_solo_last_battle_time')->nullable();
            $table->unsignedInteger('pve_solo_ship_stat_details_id')->nullable()->index();
            
            //"pvp_div2"
            $table->unsignedInteger('pvp_div2_pr')->nullable();
            $table->unsignedInteger('pvp_div2_wtr')->nullable();
            $table->unsignedInteger('pvp_div2_battles')->nullable();
            $table->timestamp(      'pvp_div2_last_battle_time')->nullable();
            $table->unsignedInteger('pvp_div2_ship_stat_details_id')->nullable()->index();
            
            //"pvp_div3"
            $table->unsignedInteger('pvp_div3_pr')->nullable();
            $table->unsignedInteger('pvp_div3_wtr')->nullable();
            $table->unsignedInteger('pvp_div3_battles')->nullable();
            $table->timestamp(      'pvp_div3_last_battle_time')->nullable();
            $table->unsignedInteger('pvp_div3_ship_stat_details_id')->nullable()->index();
            
            //"pvp_solo"
            $table->unsignedInteger('pvp_solo_pr')->nullable();
            $table->unsignedInteger('pvp_solo_wtr')->nullable();
            $table->unsignedInteger('pvp_solo_battles')->nullable();
            $table->timestamp(      'pvp_solo_last_battle_time')->nullable();
            $table->unsignedInteger('pvp_solo_ship_stat_details_id')->nullable()->index();
            
            //"rank_div2"
            
            //"rank_div3"
            
            //"rank_solo"
            $table->unsignedInteger('rank_solo_pr')->nullable();
            $table->unsignedInteger('rank_solo_wtr')->nullable();
            $table->unsignedInteger('rank_solo_battles')->nullable();
            $table->timestamp(      'rank_solo_last_battle_time')->nullable();
            $table->unsignedInteger('rank_solo_ship_stat_details_id')->nullable()->index();
            
            /* GENERIC*/
            $table->timestamp('last_battle_time');
            $table->unsignedInteger('account_id')->index();
            $table->unsignedInteger('distance');
            $table->timestamp('wg_updated_at');
            $table->unsignedInteger('battles');
            $table->unsignedInteger('ship_id')->index();
            
            
            $table->foreign('account_id')->references('id')->on('players');
            //TODO:  $table->foreign('ship_id')
            
            
            
            $table->foreign('oper_div_ship_stat_details_id')->references('id')->on('ship_stat_details');
            $table->foreign('oper_solo_ship_stat_details_id')->references('id')->on('ship_stat_details');
            
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
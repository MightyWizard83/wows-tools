<?php

namespace App\Http\Controllers;

use App\Player;
use App\ShipStat;
use App\ShipStatDetail;
use \Wargaming\API;
use Illuminate\Support\Facades\App;

class WgAPIController extends Controller
{
    
    protected $api;
    protected $ratingsExpected;
    
    public function __construct(API $api)
    {
        $this->api = $api;
        $this->ratingsExpected = App::getFacadeRoot()->make('RatingsExpected');
    }
    
    /**
     * Sync the player stats.
     *
     * @param  int  $id
     * @return Response
     */
    public function syncPlayer($id)
    {
        //TODO

    }
    
    /**
     * Sync the player stats.
     *
     * @param  int  $id
     * @return Response
     */
    public function syncPlayerTest($id)
    {
        try {

            $realm = 'eu';
            
            $ratingsExpected = app()->make('RatingsExpected');
 
            $account_id = $id;

            
            $accountData = $this->api->get('wows/account/info', 
                        ['account_id'=>$account_id, 
                            'extra'=> '']
                        );
            
            //todo check if player found
            if (!isset($accountData->{$account_id})) {
                abort(404);
            }
            
            $player = Player::byRealm($realm)->byAccountId($account_id)->firstOrCreate(['realm' => $realm, 'id' => $account_id]);
            if ($player->wasRecentlyCreated === true) {
                $player = Player::byRealm($realm)->byAccountId($account_id)->first();
            }
            
            $last_battle_time = $accountData->{$account_id}->last_battle_time;
            $logout_at = $accountData->{$account_id}->logout_at;
            $wg_stats_updated_at = $accountData->{$account_id}->stats_updated_at;
            $wg_updated_at = $accountData->{$account_id}->updated_at;        
            
            $player->hidden_profile     = $accountData->{$account_id}->hidden_profile;
            $player->karma              = 0;
            $player->last_battle_time   = new \DateTime(("@$last_battle_time"));
            $player->leveling_points    = $accountData->{$account_id}->leveling_points;
            $player->leveling_tier      = $accountData->{$account_id}->leveling_tier;
            $player->logout_at          = new \DateTime(("@$logout_at"));
            $player->nickname           = $accountData->{$account_id}->nickname;
            $player->wg_stats_updated_at = new \DateTime(("@$wg_stats_updated_at"));
            $player->wg_updated_at      = new \DateTime(("@$wg_updated_at"));
            
            $data = $this->api->get('wows/ships/stats', 
                        ['account_id'=>$account_id, 
                            'extra'=> 'club,oper_div,oper_div_hard,oper_solo,pve,pve_div2,pve_div3,pve_solo,pvp_div2,pvp_div3,pvp_solo,rank_div2,rank_div3,rank_solo']
                        );
            
            foreach ($data->{$account_id} as $ship_stats) {
                
                $ship_expected_stats = $ratingsExpected[''.$ship_stats->ship_id];
                
                $shipStat = ShipStat::byAccountId($account_id)->byShipId($ship_stats->ship_id)->firstOrCreate(['account_id' => $account_id, 'ship_id' => $ship_stats->ship_id]);
                if ($shipStat->wasRecentlyCreated === true) {
                    $shipStat = ShipStat::byAccountId($account_id)->byShipId($ship_stats->ship_id)->first();
                }
                
                $last_battle_time = $ship_stats->last_battle_time;
                $wg_updated_at = $ship_stats->updated_at;  
                
                $shipStat->last_battle_time     =    new \DateTime(("@$last_battle_time"));
                $shipStat->distance             =    $ship_stats->distance;
                $shipStat->wg_updated_at        =    new \DateTime(("@$wg_updated_at"));
                $shipStat->battles             =    $ship_stats->battles;


                $shipPR = $this->computeShipPR($ship_stats, $ship_expected_stats);

                echo "<h2>".$ship_stats->ship_id." PR: " . round($shipPR["pr"]) . "</h2>";
                echo "<strong>Win Rate: " . round($shipPR["wr"], 2) . "%</strong><br />\n";
                echo "<strong>Avg Damage: " . round($shipPR["avgDamage"]) . "</strong><br />\n";
                echo "<strong>Avg frags: " . round($shipPR["avgFrags"],2) . "</strong><br />\n";
                
                
                $shipStat->save();
            }
            
            $player->save();

        } catch (Exception $e) {
                die($e->getMessage());
        }

    }
    
    private function computeShipPR($account_stats, $ship_expected_stats)
    {

        //TODO: Fix Division by zero
        $battles = $account_stats->pvp->battles > 0 ? $account_stats->pvp->battles : 1;
        $damage_dealt = $account_stats->pvp->damage_dealt;
        $wins = $account_stats->pvp->wins;
        $frags = $account_stats->pvp->frags;
        
        $average_damage_dealt = ($damage_dealt / $battles);
        $average_win_rate = (100 * $wins / $battles);
        $average_frags = ($frags / $battles);

        /**
         * Step 1 - ratios:
         * rDmg = actualDmg/expectedDmg
         * rWins = actualWins/expectedWins
         * rFrags = actualFrags/expectedFrags
         **/ 

        $rDmg = ($average_damage_dealt /  $ship_expected_stats['average_damage_dealt']);
        $rFrags = $average_frags / ($ship_expected_stats['average_frags']);
        $rWins = $average_win_rate / $ship_expected_stats['win_rate'];
        
        /** 
         * Step 2 - normalization:
         * nDmg = max(0, (rDmg - 0.4) / (1 - 0.4))
         * nFrags = max(0, (rFrags - 0.1) / (1 - 0.1))
         * nWins = max(0, (rWins - 0.7) / (1 - 0.7)) 
         */

        $nDmg = max(0, ($rDmg - 0.4) / (1 - 0.4));
        $nFrags = max(0, ($rFrags - 0.1) / (1 - 0.1));
        $nWins = max(0, ($rWins - 0.7) / (1 - 0.7));
        
        /** 
         * Step 3 - PR value:
         * PR =  700*nDMG + 300*nFrags + 150*nWins
         **/
        
        $pr = 700 * $nDmg + 300 * $nFrags + 150 * $nWins;
        
        return array( "pr" => $pr, "wr" => $average_win_rate, "avgDamage" => $average_damage_dealt, "avgFrags" => $average_frags);
    }
}

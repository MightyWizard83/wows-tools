<?php

namespace App\Console\Commands;

use App\Clan;
use App\Player;
use App\ShipStat;
use App\ShipStatDetail;
use App\HistoryShipStat;
use App\HistoryShipStatDetail;
use \Wargaming\API;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class SyncPlayer extends Command
{
    
    protected $api;
    protected $ratingsExpected;
    protected $syncedStats;
    protected $syncedApiStats;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'WgApi:SyncPlayer {account_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(API $api)
    {
        parent::__construct();
        
        $this->api = $api;
        $this->ratingsExpected = App::getFacadeRoot()->make('RatingsExpected');
        $this->syncedStats = array(
//            "club",
//        "oper_div",
//        "oper_div_hard",
//        "oper_solo",
        "pve",
        "pve_div2",
        "pve_div3",
        "pve_solo",
        "pvp",
        "pvp_div2",
        "pvp_div3",
        "pvp_solo",
//        "rank_div2",
//        "rank_div3",
        "rank_solo");

        $this->syncedApiStats = array(
//            "club",
//        "oper_div",
//        "oper_div_hard",
//        "oper_solo",
        "pve",
        "pve_div2",
        "pve_div3",
        "pve_solo",
//        "pvp",
        "pvp_div2",
        "pvp_div3",
        "pvp_solo",
//        "rank_div2",
//        "rank_div3",
        "rank_solo");
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $account_id = $this->argument('account_id');
        
        try {
            
            Log::channel('WgApi')->info('syncPlayerTest START '.$account_id);

            $realm = 'eu';
            
            $ratingsExpected = app()->make('RatingsExpected');

            
            $accountData = $this->api->get('wows/account/info', 
                        ['account_id'=>$account_id, 
                            'extra'=> '']
                        );
            
            //todo check if player found
            if (!isset($accountData->{$account_id})) {
                Log::channel('WgApi')->error('player not found '.$account_id);
                return;
            }
            
            $player = Player::byRealm($realm)->byAccountId($account_id)->firstOrCreate(['realm' => $realm, 'id' => $account_id]);
            if ($player->wasRecentlyCreated === true) {
                Log::channel('WgApi')->info('Created player '.$realm.' '.$account_id);
                $player = Player::byRealm($realm)->byAccountId($account_id)->first();
            }
            
            $last_battle_time = $accountData->{$account_id}->last_battle_time;
            $logout_at = $accountData->{$account_id}->logout_at;
            $wg_stats_updated_at = $accountData->{$account_id}->stats_updated_at;
            $wg_updated_at = $accountData->{$account_id}->updated_at;        
            
            if ($player->nickname <> $accountData->{$account_id}->nickname) {
                Log::channel('WgApi')->info('Nickname Change detected '.$player->nickname.' '.$accountData->{$account_id}->nickname);
                //TODO Hook
                
            }
            
            $player->hidden_profile     = $accountData->{$account_id}->hidden_profile;
            $player->karma              = 0;
            $player->last_battle_time   = new \DateTime(("@$last_battle_time"));
            $player->leveling_points    = $accountData->{$account_id}->leveling_points;
            $player->leveling_tier      = $accountData->{$account_id}->leveling_tier;
            $player->logout_at          = new \DateTime(("@$logout_at"));
            $player->nickname           = $accountData->{$account_id}->nickname;
            $player->wg_stats_updated_at = new \DateTime(("@$wg_stats_updated_at"));
            $player->wg_updated_at      = new \DateTime(("@$wg_updated_at"));
            
            $api_ship_data_start = round(microtime(true) * 1000);
            $api_ship_data = $this->api->get('wows/ships/stats', 
                        ['account_id'=>$account_id, 
                            'extra'=> implode(",",$this->syncedApiStats)]
                        );
            $api_ship_data_time = round(microtime(true) * 1000) - $api_ship_data_start;
                       
            Log::channel('WgApi')->info('API-CALL wows/ships/stats '.$api_ship_data_time.' ms '.
                    $account_id.' '.
                    implode(",",$this->syncedApiStats));
            
            Log::channel('WgApi')->debug(print_r($api_ship_data,true));
            
            //ITERATE SHIPS
            foreach ($api_ship_data->{$account_id} as $api_ship_stats) {
                
                Log::channel('WgApi')->info('ShipStat '.$account_id.' '.$api_ship_stats->ship_id);
                
                $shipRatings = $this->computeShipRatings($api_ship_stats, $ratingsExpected);
                Log::channel('WgApi')->debug('Computed Ship Rating '.print_r($shipRatings,true));
                
                $shipStat = ShipStat::byAccountId($account_id)->byShipId($api_ship_stats->ship_id)->firstOrCreate(['account_id' => $account_id, 'ship_id' => $api_ship_stats->ship_id]);
                if ($shipStat->wasRecentlyCreated === true) {
                    Log::channel('WgApi')->info('Created ShipStat '.$account_id.' '.$api_ship_stats->ship_id);
                    $shipStat = ShipStat::byAccountId($account_id)->byShipId($api_ship_stats->ship_id)->first();
                }
                
                $last_battle_time = $api_ship_stats->last_battle_time;  //Last game START time
                $wg_updated_at = $api_ship_stats->updated_at;           //Last game END time
                
                $shipStat->battles              =    $api_ship_stats->battles;
                $shipStat->last_battle_time     =    new \DateTime(("@$last_battle_time")); //Last game START time
                $shipStat->wg_updated_at        =    new \DateTime(("@$wg_updated_at"));    //Last game END time
                $shipStat->distance             =    $api_ship_stats->distance;
                
                //Iterate "pve", "pve_div2", "pve_div3", "pve_solo", "pvp", "pvp_div2", "pvp_div3", "pvp_solo", "rank_solo" etc...
                foreach ($this->syncedStats as $type) {
                    
                    //Skip the type where we did not play any battles
                    if ($api_ship_stats->$type->battles > 0) {

                        $shipStatDetail = ShipStatDetail::byAccountId($account_id)->byShipId($api_ship_stats->ship_id)->byType($type)->firstOrCreate(['account_id' => $account_id, 'ship_id' => $api_ship_stats->ship_id, 'type' => $type]);
                        if ($shipStatDetail->wasRecentlyCreated === true) {
                            $shipStatDetail = ShipStatDetail::byAccountId($account_id)->byShipId($api_ship_stats->ship_id)->byType($type)->first();
                        }
                        
                        if ($shipStatDetail->battles <> $api_ship_stats->$type->battles && $shipStatDetail->battles > 0) {
                            Log::channel('WgApi')->info('New battles detected '.$shipStatDetail->battles.' '.$api_ship_stats->$type->battles);
                            //TODO Hook
                            
                            $shipStatDetail->last_battle_time   =    new \DateTime(("@$last_battle_time"));
                            $shipStatDetail->wg_updated_at      =    new \DateTime(("@$wg_updated_at")); 
                        }

                        $this->updateShipStatDetail($shipStatDetail, $api_ship_stats->$type);
                        $this->updateShipStat($type, $shipStat, $shipStatDetail, $shipRatings[$type], $api_ship_stats->$type);

                        $shipStatDetail->save();
                    }
                }
                
                
                
                //HISTORY LOGIC
                $histDate = $shipStat->last_battle_time->format("Y-m-d");
                $historyShipStat = HistoryShipStat::byAccountId($account_id)->byShipId($api_ship_stats->ship_id)->byDate($histDate)->firstOrCreate(['account_id' => $account_id, 'ship_id' => $api_ship_stats->ship_id, 'date' => $histDate]);
                if ($historyShipStat->wasRecentlyCreated === true) {
                    Log::channel('WgApi')->info('Created HistoryShipStat '.$account_id.' '.$api_ship_stats->ship_id.' '.$histDate);
                    $historyShipStat = HistoryShipStat::byAccountId($account_id)->byShipId($api_ship_stats->ship_id)->byDate($histDate)->first();
                }
                
                if ($shipStat->battles <> $historyShipStat->battles) {
                    Log::channel('WgApi')->info('History ShipStats Change Detected. Battles:'.$shipStat->battles. ' '.$historyShipStat->battles);
                    
                    $historyShipStat->battles              =    $api_ship_stats->battles;
                    $historyShipStat->last_battle_time     =    new \DateTime(("@$last_battle_time")); //Last game START time
                    $historyShipStat->wg_updated_at        =    new \DateTime(("@$wg_updated_at"));    //Last game END time
                    $historyShipStat->distance             =    $api_ship_stats->distance;
                }
                //END HISTORY LOGIC
                
                $historyShipStat ->save();
                $shipStat->save();
            }
            
            
            //Update Player and Sync Clan Info
            $this->syncClanInfo($player, $account_id);
            
            
            $player->save();

        } catch (\Exception $e) {
            Log::channel('WgApi')->error('syncPlayerTest ERROR '.$account_id.' '.$e->getMessage());
            Log::channel('WgApi')->error(print_r($e,true));
                die($e->getMessage());
        }
        
        Log::channel('WgApi')->info('syncPlayerTest END '.$account_id);
    }
    
    private function syncClanInfo(&$player, $account_id) {
        //Sync Clan Info
        $apiDateStart = round(microtime(true) * 1000);
        $data = $this->api->get('wows/clans/accountinfo', 
                    ['account_id'=>$account_id, 
                        'extra'=> 'clan']
                    );
        $apiDateEnd = round(microtime(true) * 1000);

        Log::channel('WgApi')->info('API-CALL wows/clans/accountinfo '.($apiDateEnd-$apiDateStart).' ms '.$account_id);
        Log::channel('WgApi')->debug(print_r($data,true));

        $api_accountinfo_clans = $data->{$account_id};

        if ($player->clan_clan_id <> $api_accountinfo_clans->clan_id) {
            Log::channel('WgApi')->info('Clan change detected '.$api_accountinfo_clans->clan_id.' '.$player->clan_id);
            //TODO Hook

            $created_at = $api_accountinfo_clans->clan->created_at; 

            $clan = Clan::byId($api_accountinfo_clans->clan->clan_id)
                    ->firstOrCreate(['id'   => $api_accountinfo_clans->clan->clan_id,
                        'wg_created_at'     => new \DateTime(("@$created_at")), 
                        'members_count'     => $api_accountinfo_clans->clan->members_count,
                        'name'              => $api_accountinfo_clans->clan->name,
                        'tag'               => $api_accountinfo_clans->clan->tag]);
            if ($clan->wasRecentlyCreated === true) {
                Log::channel('WgApi')->info('New Clan created '.$api_accountinfo_clans->clan->clan_id.' ['.$api_accountinfo_clans->clan->tag.'] '.$api_accountinfo_clans->clan->name);
                //TODO Hook

            }

        }

        if ($player->clan_role <> $api_accountinfo_clans->role) {
            Log::channel('WgApi')->info('Role change detected '.$api_accountinfo_clans->role.' '.$player->clan_role);
            //TODO Hook

        }

        $joined_at = $api_accountinfo_clans->joined_at;           

        $player->clan_clan_id            = $api_accountinfo_clans->clan_id;
        $player->clan_joined_at          = new \DateTime(("@$joined_at"));
        $player->clan_role               = $api_accountinfo_clans->role;
        //End  CLAN LOGIC
    }
    
    private function computeShipRatings($api_ship_stats, $ratingsExpected) {
        $shipRatings = array();
        $ship_expected_stats = array();
        
        if (!array_key_exists(''.$api_ship_stats->ship_id, $ratingsExpected)) {
            //We do not have the stats for this ship. Skip this.
            Log::channel('WgApi')->error('missing ratings for Ship '.$api_ship_stats->ship_id);
            
        } else {
            $ship_expected_stats = $ratingsExpected[''.$api_ship_stats->ship_id];
            
            if (empty($ship_expected_stats['average_damage_dealt']) || empty($ship_expected_stats['average_frags']) || empty($ship_expected_stats['win_rate']) ) {
                //Ship stats are epty
                Log::channel('WgApi')->error('empty ratings for Ship '.$api_ship_stats->ship_id);

            }
        }

        //Iterate "pve", "pve_div2", "pve_div3", "pve_solo", "pvp", "pvp_div2", "pvp_div3", "pvp_solo", "rank_solo" etc...
        foreach ($this->syncedStats as $type) {
            //Skip the type where we did not play any battles
            if ($api_ship_stats->$type->battles > 0) {
                $shipRatings[$type] = $this->computeShipRating($api_ship_stats->$type, $ship_expected_stats);
            }
        }
        
        return $shipRatings;
        
    }
    
    private function updateShipStat($type, &$shipStat, $shipStatDetail, $shipRating, $wg_ship_stats_type) {
        $shipStat->{$type.'_wr'}                = $shipRating["wr"];
        $shipStat->{$type.'_pr'}                = $shipRating["pr"];
        $shipStat->{$type.'_wtr'}               = null;                 //TODO
        $shipStat->{$type.'_battles'}           = $wg_ship_stats_type->battles;
        $shipStat->{$type.'_last_battle_time'}  = $shipStatDetail->last_battle_time; 
        $shipStat->{$type.'_ship_stat_details_id'} = $shipStatDetail->id;
    }
    
    private function updateShipStatDetail(&$shipStatDetail, $wg_ship_stats_type) {
      
        $shipStatDetail->max_xp                 = $wg_ship_stats_type->max_xp;
        $shipStatDetail->damage_to_buildings    = isset($wg_ship_stats_type->damage_to_buildings) ? $wg_ship_stats_type->damage_to_buildings : null;
        
        /*main_battery*/
        $shipStatDetail->main_battery_max_frags_battle  = $wg_ship_stats_type->main_battery->max_frags_battle;
        $shipStatDetail->main_battery_frags             = $wg_ship_stats_type->main_battery->frags;
        $shipStatDetail->main_battery_hits              = $wg_ship_stats_type->main_battery->hits;
        $shipStatDetail->main_battery_shots             = $wg_ship_stats_type->main_battery->shots;

        $shipStatDetail->suppressions_count     = isset($wg_ship_stats_type->suppressions_count) ? $wg_ship_stats_type->suppressions_count : null;        
        $shipStatDetail->max_damage_scouting    = $wg_ship_stats_type->max_damage_scouting;
        $shipStatDetail->art_agro               = $wg_ship_stats_type->art_agro;
        $shipStatDetail->ships_spotted          = $wg_ship_stats_type->ships_spotted;

        /*second_battery*/
        $shipStatDetail->second_battery_max_frags_battle        = $wg_ship_stats_type->second_battery->max_frags_battle;
        $shipStatDetail->second_battery_frags                   = $wg_ship_stats_type->second_battery->frags;
        $shipStatDetail->second_battery_hits                    = $wg_ship_stats_type->second_battery->hits;
        $shipStatDetail->second_battery_shots                   = $wg_ship_stats_type->second_battery->shots;

        $shipStatDetail->xp                             = $wg_ship_stats_type->xp;
        $shipStatDetail->survived_battles               = $wg_ship_stats_type->survived_battles;
        $shipStatDetail->dropped_capture_points         = isset($wg_ship_stats_type->dropped_capture_points) ? $wg_ship_stats_type->dropped_capture_points : null;
        $shipStatDetail->max_damage_dealt_to_buildings  = isset($wg_ship_stats_type->max_damage_dealt_to_buildings) ? $wg_ship_stats_type->max_damage_dealt_to_buildings : null;
        $shipStatDetail->torpedo_agro                   = $wg_ship_stats_type->torpedo_agro;
        $shipStatDetail->draws                          = $wg_ship_stats_type->draws;
        $shipStatDetail->battles_since_510              = isset($wg_ship_stats_type->battles_since_510) ? $wg_ship_stats_type->battles_since_510 : null;
        $shipStatDetail->planes_killed                  = $wg_ship_stats_type->planes_killed;
        $shipStatDetail->battles                        = $wg_ship_stats_type->battles;
        $shipStatDetail->max_ships_spotted              = $wg_ship_stats_type->max_ships_spotted;
        $shipStatDetail->team_capture_points            = $wg_ship_stats_type->team_capture_points;
        $shipStatDetail->frags                          = $wg_ship_stats_type->frags;
        $shipStatDetail->damage_scouting                = $wg_ship_stats_type->damage_scouting;
        $shipStatDetail->max_total_agro                 = $wg_ship_stats_type->max_total_agro;
        $shipStatDetail->max_frags_battle               = $wg_ship_stats_type->max_frags_battle;
        $shipStatDetail->capture_points                 = isset($wg_ship_stats_type->capture_points) ? $wg_ship_stats_type->capture_points : null;

        /*ramming*/
        $shipStatDetail->ramming_max_frags_battle           = $wg_ship_stats_type->ramming->max_frags_battle;
        $shipStatDetail->ramming_frags                      = $wg_ship_stats_type->ramming->frags;
        

        /*torpedoes*/
        $shipStatDetail->torpedoes_max_frags_battle         = $wg_ship_stats_type->torpedoes->max_frags_battle;
        $shipStatDetail->torpedoes_frags                    = $wg_ship_stats_type->torpedoes->frags;
        $shipStatDetail->torpedoes_hits                     = $wg_ship_stats_type->torpedoes->hits;
        $shipStatDetail->torpedoes_shots                    = $wg_ship_stats_type->torpedoes->shots;

        /*aircraft*/
        $shipStatDetail->aircraft_max_frags_battle          = $wg_ship_stats_type->aircraft->max_frags_battle;
        $shipStatDetail->aircraft_frags                     = $wg_ship_stats_type->aircraft->frags;

        
        $shipStatDetail->survived_wins          = $wg_ship_stats_type->survived_wins;
        $shipStatDetail->max_damage_dealt       = $wg_ship_stats_type->max_damage_dealt;
        $shipStatDetail->wins                   = $wg_ship_stats_type->wins;
        $shipStatDetail->losses                 = $wg_ship_stats_type->losses;
        $shipStatDetail->damage_dealt           = $wg_ship_stats_type->damage_dealt;
        $shipStatDetail->max_planes_killed      = $wg_ship_stats_type->max_planes_killed;
        $shipStatDetail->max_suppressions_count = isset($wg_ship_stats_type->max_suppressions_count) ? $wg_ship_stats_type->max_suppressions_count : null;
        $shipStatDetail->team_dropped_capture_points = $wg_ship_stats_type->team_dropped_capture_points;
        $shipStatDetail->battles_since_512      = isset($wg_ship_stats_type->battles_since_512) ? $wg_ship_stats_type->battles_since_512 : null;


//                /* GENERIC*/
//                $table->timestamp('last_battle_time');            //Inferred in the outer logic
//                $table->unsignedInteger('account_id')->index();
//                $table->timestamp('wg_updated_at');               //Inferred in the outer logic
//                $table->unsignedInteger('ship_id')->index();
//
//                $table->foreign('account_id')->references('id')->on('players');        
    }
    
    private function computeShipRating($account_stats, $ship_expected_stats)
    {
        $battles = $account_stats->battles;
        $damage_dealt = $account_stats->damage_dealt;
        $wins = $account_stats->wins;
        $frags = $account_stats->frags;
        
        $average_damage_dealt = ($damage_dealt / $battles);
        $average_win_rate = (100 * $wins / $battles);
        $average_frags = ($frags / $battles);

        $pr = null;
        
        if (!empty($ship_expected_stats)) {
        
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
            
        }
        
        return array( "pr" => $pr,  "wr" => $average_win_rate,  "avgDamage" => $average_damage_dealt, "avgFrags" => $average_frags);
    }
}

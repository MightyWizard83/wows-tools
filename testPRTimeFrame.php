<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require __DIR__ . '/vendor/autoload.php';

// API Instance where demo is your application_id
//$api = new Wargaming\API('268c4563cd5f273c94aca7b3faf2cc57'); //WOWS TOOLS TEST
//
//// Test how it works
//try {
//	$data = $api->get('wgn/clans/list', ['search'=>'PSQD']);        
//    
//	// Display info about WoT Clan PSQD
//	var_dump($data);
//	
//} catch (Exception $e) {
//
//	die($e->getMessage());
//	
//}


$api = new Wargaming\API('268c4563cd5f273c94aca7b3faf2cc57', Wargaming\LANGUAGE_ENGLISH, 'api.worldofwarships.eu'); //WOWS TOOLS TEST


try {

    echo "<h1>Personal Rating - Overall PR proof of concept</h1>\n";
    
    //$ship_id = 4289640432; //OMAHA
    //$ship_id = 3763255280; //INDIANAPOLIS
    //$ship_id = 4255037136; //ATAGO
    $ship_id = 4183766992; //QUEEN ELIZABETH
    
    
    $string = file_get_contents("ratings-expected.json");
    $ratingsExpected = json_decode($string, true);

    $serachString = 'MightyWizard';
    
    $accountData = $api->get('wows/account/list', ['search'=>$serachString]);

//    echo "<strong>Player account found for search: $serachString</strong><br />\n";
//    var_dump($accountData);

//        $data = $api->get('wows/account/info', 
//                        ['account_id'=>$data[0]->account_id, 'extra'=> 'statistics.oper_div,statistics.oper_div_hard,statistics.oper_solo,statistics.pve,statistics.pve_div2,statistics.pve_div3,statistics.pve_solo,statistics.pvp_div2,statistics.pvp_div3,statistics.pvp_solo,statistics.rank_div2,statistics.rank_div3,statistics.rank_solo']
//                        );
//	var_dump($data);

    $account_id = $accountData[0]->account_id;

    $data = $api->get('wows/ships/stats', 
                    ['account_id'=>$account_id, 
                        'ship_id' => $ship_id,
                        'extra'=> 'club,oper_div,oper_div_hard,oper_solo,pve,pve_div2,pve_div3,pve_solo,pvp_div2,pvp_div3,pvp_solo,rank_div2,rank_div3,rank_solo']
                    );

    $account_stats = $data->{$account_id}[0];
    $ship_expected_stats = $ratingsExpected['data'][$ship_id];
    
//    echo "<strong>Ship stats (pvp) found for for account_id: $account_id and ship_id: $ship_id</strong><br />\n";
//    var_dump($account_stats->pvp);

    echo "<br />\n";
    echo "<br />\n";

    $battles = $account_stats->pvp->battles;
    $damage_dealt = $account_stats->pvp->damage_dealt;
    $wins = $account_stats->pvp->wins;
    $frags = $account_stats->pvp->frags;
    
    $average_damage_dealt = ($damage_dealt / $battles);
    $average_win_rate = (100 * $wins / $battles);
    $average_frags = ($frags / $battles);

    echo "<strong>Player stats (pvp) for ship: $ship_id</strong><br />\n";
    echo "battles: " . $account_stats->pvp->battles . "<br />\n";
    echo "damage_dealt: " . $account_stats->pvp->damage_dealt . " (" . $average_damage_dealt . ") <br />\n";
    echo "wins: " . $account_stats->pvp->wins . " (" . $average_win_rate . "%) <br />\n";
    echo "frags: " . $account_stats->pvp->frags . " (". $average_frags . ") <br />\n";

    echo "<br />\n";
    echo "<strong>Ratings Expected for ship: $ship_id</strong><br />\n";
    echo "average_damage_dealt: ".$ship_expected_stats['average_damage_dealt']."<br />\n";
    echo "average_frags: ".$ship_expected_stats['average_frags']."<br />\n";
    echo "win_rate: ".$ship_expected_stats['win_rate']."<br />\n";
    
    
    /**
     * Step 1 - ratios:
     * rDmg = actualDmg/expectedDmg
     * rWins = actualWins/expectedWins
     * rFrags = actualFrags/expectedFrags
     **/ 
    
    $rDmg = ($average_damage_dealt /  $ship_expected_stats['average_damage_dealt']);
    $rFrags = $average_frags / ($ship_expected_stats['average_frags']);
    $rWins = $average_win_rate / $ship_expected_stats['win_rate'];
    
    echo "<br />\n";
    echo "<strong>Step 1 - ratios:</strong><br />\n";
    echo "rDmg = actualDmg/expectedDmg ($rDmg)<br />\n";
    echo "rFrags = actualFrags/expectedFrags ($rFrags)<br />\n";
    echo "rWins = actualWins/expectedWins ($rWins)<br />\n";
    
    /** 
     * Step 2 - normalization:
     * nDmg = max(0, (rDmg - 0.4) / (1 - 0.4))
     * nFrags = max(0, (rFrags - 0.1) / (1 - 0.1))
     * nWins = max(0, (rWins - 0.7) / (1 - 0.7)) 
     */
    
    $nDmg = max(0, ($rDmg - 0.4) / (1 - 0.4));
    $nFrags = max(0, ($rFrags - 0.1) / (1 - 0.1));
    $nWins = max(0, ($rWins - 0.7) / (1 - 0.7));
    
    echo "<br />\n";
    echo "<strong>Step 2 - normalization:</strong><br />\n";
    echo "nDmg = max(0, (rDmg - 0.4) / (1 - 0.4)) -> $nDmg<br />\n";
    echo "nFrags = max(0, (rFrags - 0.1) / (1 - 0.1)) -> $nFrags<br />\n";
    echo "nWins = max(0, (rWins - 0.7) / (1 - 0.7)) -> $nWins<br />\n";
    
    /** 
     * Step 3 - PR value:
     * PR =  700*nDMG + 300*nFrags + 150*nWins
     **/
    
    $pr = 700 * $nDmg + 300 * $nFrags + 150 * $nWins;
    echo "<br />\n";
    echo "<strong>Step 3 - PR value:</strong><br />\n";
    echo "PR = 700*nDMG + 300*nFrags + 150*nWins -> $pr<br />\n";
    
    echo "<br />\n";
    
    echo "<h2>PR: " . round($pr) . "</h2>";
          
} catch (Exception $e) {

	die($e->getMessage());
	
}
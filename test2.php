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

// Test how it works
try {
	$data = $api->get('wows/account/list', ['search'=>'MightyWizard']);
    
	var_dump($data);
        
        $data = $api->get('wows/account/info', 
                        ['account_id'=>$data[0]->account_id, 'extra'=> 'statistics.oper_div,statistics.oper_div_hard,statistics.oper_solo,statistics.pve,statistics.pve_div2,statistics.pve_div3,statistics.pve_solo,statistics.pvp_div2,statistics.pvp_div3,statistics.pvp_solo,statistics.rank_div2,statistics.rank_div3,statistics.rank_solo']
                        );
    
	var_dump($data);
        
        print_r($data);
	
} catch (Exception $e) {

	die($e->getMessage());
	
}
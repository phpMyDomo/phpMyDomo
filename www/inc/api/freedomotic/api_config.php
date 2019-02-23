<?php
//#############################################################################
// REQUIRED #################################################################
//#############################################################################

$api['method']='json_mixed'; //api method : json_get | json_rpc2

//api URL ----------------------------------------------------------------
$api['urls']['api']	=$this->conf['urls']['host'].':9111';
$api['urls']['www']	=$this->conf['urls']['host'].':8090';

$api['dir_admin']		='/';

//$api['urls']['view_sensor']	='/stats/{id}/log';	// (Abs.) Url to view the log/stats of a sensor . use {id}

// Set Commands Values -------------------------------------------------------
// Values used when Sending a command
$api['set']['switch']['on']			='true';
$api['set']['switch']['off']		='false';

//$api['set']['push']['on']			='true';
//$api['set']['push']['off']		='false';

//$api['set']['scene']['on']		='true';
//$api['set']['scene']['off']		='false';

//$api['set']['group']['on']		='true';
//$api['set']['group']['off']		='false';

//$api['set']['dimmer']['on']		='On';
//$api['set']['dimmer']['off']		='false';
//$api['set']['dimmer']['min']		=1;
//$api['set']['dimmer']['max']		=16;

//$api['set']['blinds']['off']		='true';		
//$api['set']['blinds']['on']		='false';		


// Actions  URLs -------------------------------------------------------------------
//$api['actions']['set']['switch']		='/things/{address}/behaviorchange/powered/{state}';
$api['actions']['set']['switch']		=array(
	'url'		=>	'/v3/things/{address}/behaviorchange/powered/{state}',
	'method'	=>	'post'
);
/*
$api['actions']['set']['scene']		=array(
	'url'		=>	'/v3/things/{address}/behaviorchange/powered/{state}',
	'method'	=>	'post'
);
*/

//$api['actions']['set']['push']			='/json.htm?type=command&param=switchlight&idx={address}&switchcmd={state}';
//$api['actions']['set']['dimmer']		='/json.htm?type=command&param=switchlight&idx={address}&switchcmd={state}';
//$api['actions']['set']['blinds']		='/json.htm?type=command&param=switchlight&idx={address}&switchcmd={state}&level=0';
//$api['actions']['set']['dim_level']		='/json.htm?type=command&param=switchlight&idx={address}&switchcmd=Set+Level&level={state}';
//$api['actions']['set']['group']			='/json.htm?type=command&param=switchscene&idx={address}&switchcmd={state}';
//$api['actions']['set']['selector']		='/json.htm?type=command&param=switchlight&idx={address}&switchcmd=Set+Level&level={state}';


//#############################################################################
// CUSTOM api_client ##########################################################
//#############################################################################

// List actions URLS -------------------------------------
$api['actions']['list']['device']		=array(
	'url'		=> '/v3/things',
	'method'	=> 'get',
);
/*
//where can we get time infos (server time, sunset and sunrise times
$api['actions']['list']['info']		=array(
	'url'		=> '/info_page',
	'method'	=> 'get',
);
//where can we get the list of amera if any
$api['actions']['list']['info']		=array(
	'url'		=> '/cam_page',
	'method'	=> 'get',
);
*/


// RECEIVED States values (auto formatted when defined)  ---------------------------------------

$api['states']['command']['switch'][false]	='off';		
$api['states']['command']['switch'][true]	='on';	

$api['states']['sensor']['door'][false]		='off';		
$api['states']['sensor']['door'][true]		='on';		
	
/*
$api['states']['scene']['scene'][false]		='off';		
$api['states']['scene']['scene'][true]		='on';		
//$api['states']['scene']['scene']['Mixed']		='mixed';		

$api['states']['command']['push']['Off']		='off';		
$api['states']['command']['push']['On']			='on';		

$api['states']['command']['dimmer']['Off']		='off';		
$api['states']['command']['dimmer']['On']		='on';		

$api['states']['command']['blinds']['Open']		='off';		
$api['states']['command']['blinds']['Closed']	='on';		

$api['states']['scene']['scene']['Off']			='off';		
$api['states']['scene']['scene']['On']			='on';		
$api['states']['scene']['scene']['Mixed']		='mixed';		

$api['states']['scene']['group']['Off']			='off';		
$api['states']['scene']['group']['On']			='on';		
$api['states']['scene']['group']['Mixed']		='mixed';		

$api['states']['sensor']['pir']['Off']			='off';		
$api['states']['sensor']['pir']['On']			='on';		

$api['states']['sensor']['bool']['Open']		='off';		
$api['states']['sensor']['bool']['Closed']		='on';		

*/

// json definition (auto formatted if defined) -------------------------------
// fields from the 'result' rows
$api['fields']['address']		="uuid";
$api['fields']['name']			="name";
//$api['fields']['battery_level']	="BatteryLevel";
//$api['fields']['signal_level']	="SignalLevel";


//json_get definition ----------------------------------------------------------
//$api['json']['status']="status";	//field with the Status of the response
//$api['json']['result']="result";	//field with the list of devices

// json received statuts value in the "status" field
//$api['json_status']['ok']	='OK';
//$api['json_status']['err']	='ERR';

?>
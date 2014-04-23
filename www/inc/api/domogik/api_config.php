<?php
//#############################################################################
// REQUIRED #################################################################
//#############################################################################

$api['method']		='json_get'; //api method : json_get | json_rpc2
$api['use_config']	=true; // use an api_domogik config from the inc/conf/ directory
//api URL -------------------------------------------------------------------
$api['urls']['api']	=$this->conf['urls']['host'].':40405';
$api['urls']['www']	=$this->conf['urls']['host'].':40404';
$api['dir_admin']	='/admin/';


// Set Commands Values -------------------------------------------------------
// Values used when Sending a command
$api['set']['switch']['on']			='on';
$api['set']['switch']['off']		='off';

//$api['set']['scene']['on']		='On';
//$api['set']['scene']['off']		='Off';

//$api['set']['group']['on']		='On';
//$api['set']['group']['off']		='Off';

$api['set']['dimmer']['on']			='on';
$api['set']['dimmer']['off']		='off';
$api['set']['dimmer']['min']		=0;
$api['set']['dimmer']['max']		=99;

$api['set']['alarm']['on']			='arm';
$api['set']['alarm']['off']			='disarm';

// Actions -------------------------------------------------------------------
$api['actions']['set']['switch']		='/command/{address}/{state}';
$api['actions']['set']['dimmer']		='/command/{address}/{state}';
$api['actions']['set']['dim_level']		='/command/{address}/dim/{state}';
//$api['actions']['set']['scene']		='/command/{address}/{state}';
//$api['actions']['set']['group']		='/command/{address}/{state}';

$api['actions']['set']['alarm']		='/command/{address}/{state}';


//#############################################################################
// CUSTOM api_client ##########################################################
//#############################################################################

// List actions URLS -------------------------------------
$api['actions']['list']['device']		='/base/feature/list';
$api['actions']['list']['stats']		='/stats/{address}/{state}/latest';
//$api['actions']['list']['scene']		='/';
//$api['actions']['list']['camera']		='/';
//$api['actions']['list']['info']		='/';
//$api['actions']['set']['scene']		='/';


// States (auto formatted if defined)  ---------------------------------------
// states (values) from the 'result' rows
//$api['states']['command']['switch']['Off']	='off';		
//$api['states']['command']['switch']['On']		='on';		


//json definition (auto formatted if defined) -------------------------------
// fields from the 'result' rows
$api['fields']['name']				="name";
//$api['fields']['battery_level']	="BatteryLevel";
//$api['fields']['signal_level']	="SignalLevel";


//json_get definition ----------------------------------------------------------
$api['json']['status']="status";	//field with the Status of the response
$api['json']['result']="feature";	//field with the list of devices

// json received statuts value in the "status" field
$api['json_status']['ok']	='OK';
$api['json_status']['err']	='ERROR';

//#############################################################################
// Devices Definitions ########################################################
//#############################################################################


$api['sensors_devices_types_ids']=array(
			'online_service.weather'=>'temp',
			'rfxcom.th'				=>'temp',
			'rfxcom.rain'			=>'rain',
			'online_service.weather'=>'temp',
			'rfxcom.humidity'		=>'hygro',
			'rfxcom.uv'				=>'uv',
			'rfxcom.rfxmeter'		=>'consum',
			'rfxcom.elec1'			=>'consum',
			'rfxcom.elec2'			=>'consum',
			'rfxcom.rfxsensor'		=>'temp',
		);
$api['switches_devices_types_ids']=array(
	'rfxcom.curtain1_harrison',
	'rfxcom.lighting1_arc_switch',
	'rfxcom.lighting1_chacon_switch',
	'rfxcom.lighting1_elro_switch',
	'rfxcom.lighting1_impuls_switch',
	'rfxcom.lighting1_waveman_switch'
);
$api['dimmers_devices_types_ids']=array(
	'rfxcom.lighting2_ac_dimmer',
	'rfxcom.lighting1_x10_dimmer',
	'rfxcom.lighting2_homeeasy_eu_dimmer',
	'rfxcom.lighting3_koppla_dimmer'
);


?>

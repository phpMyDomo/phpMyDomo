<?php
//#############################################################################
// REQUIRED #################################################################
//#############################################################################

$api['method']='custom'; //api method : json_get | json_rpc2 | json_mixed

//api URL ----------------------------------------------------------------
$api['urls']['api']	=$this->conf['urls']['host'].':5000';
$api['urls']['www']	=$this->conf['urls']['host'].':5000';
$api['dir_admin']	='';


// Set Commands Values -------------------------------------------------------
// Values used when Sending a command
$api['set']['switch']['on']			='on';
$api['set']['switch']['off']		='off';

//$api['set']['scene']['on']		='on';
//$api['set']['scene']['off']		='off';

//$api['set']['group']['on']		='on';
//$api['set']['group']['off']		='off';

$api['set']['dimmer']['on']			='15';
$api['set']['dimmer']['off']		='0';
$api['set']['dimmer']['min']		=0;
$api['set']['dimmer']['max']		=15;

// Actions -------------------------------------------------------------------
$api['actions']['set']	='/send?';
$api['actions']['list']	='/config';

//$api['messages']['list']['device']		=array('message'=>'request config');

$api['messages']['set']['switch']		=array(
	'message'	=> 'send',
	'code'		=> array(
		'location'	=> '{location}',
		'device'	=> '{device}',
		'state'		=> '{state}',
	)
);
$api['messages']['set']['dimmer'] 	=$api['messages']['set']['switch'];
$api['messages']['set']['dim_level']	=array(
	'message'	=> 'send',
	'code'		=> array(
		'location'	=> '{location}',
		'device'	=> '{device}',
//		'state'		=> 'on',
		'values'	=> array(
			'dimlevel'=> '{state}'
		)
	)
);

//$api['actions']['set']['group'] 	=$api['actions']['set']['switch'];
//$api['actions']['set']['scene'] 	=$api['actions']['set']['switch'];


//#############################################################################
// CUSTOM api_client ##########################################################
//#############################################################################

// List actions URLS -------------------------------------

//$api['actions']['list']['scene']		='/';
//$api['actions']['list']['camera']		='/';
//$api['actions']['list']['info']		='/';
//$api['actions']['set']['scene']		='/';


// States (auto formatted if defined)  ---------------------------------------
// states (values) from the 'result' rows
$api['states']['command']['switch']['off']		='off';		
$api['states']['command']['switch']['on']		='on';		

$api['states']['command']['dimmer']['off']		='off';		
$api['states']['command']['dimmer']['on']		='on';		


//json definition (auto formatted if defined) -------------------------------
// fields from the 'result' rows
//$api['fields']['name']				="name";
//$api['fields']['raw_value1']		="state";
//$api['fields']['battery_level']	="BatteryLevel";
//$api['fields']['signal_level']	="SignalLevel";


//json_get definition ----------------------------------------------------------
//$api['json']['status']="status";	//field with the Status of the response
//$api['json']['result']="config";	//field with the list of devices

// json received statuts value in the "status" field
//$api['json_status']['ok']	='OK';
//$api['json_status']['err']	='ERROR';

?>
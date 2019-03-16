<?php
//#############################################################################
// REQUIRED #################################################################
//#############################################################################

$api['method']='json_mixed'; //api method : json_get | json_rpc2 | json_mixed

//api URL ----------------------------------------------------------------
$api['urls']['api']	=$this->conf['urls']['host'].':8080';
$api['urls']['www']	=$this->conf['urls']['host'].':8080';

$api['dir_admin']	='/openhab.app';

//$api['urls']['view_sensor']	='/stats/{id}/log';	// (Abs.) Url to view the log/stats of a sensor . use {id}

// Set Commands Values -------------------------------------------------------
// Values used when Sending a command
$api['set']['switch']['on']			='ON';
$api['set']['switch']['off']		='OFF';

$api['set']['shutter']['on']		='100';
$api['set']['shutter']['off']		='0';
$api['set']['blinds']['on']			='100';
$api['set']['blinds']['off']		='0';

$api['set']['scene']['on']			='ON';
$api['set']['scene']['off']			='OFF';

$api['set']['group']['on']			='ON';
$api['set']['group']['off']			='OFF';

$api['set']['dimmer']['on']			='100';
$api['set']['dimmer']['off']		='0';
$api['set']['dimmer']['min']		=0;
$api['set']['dimmer']['max']		=100;

// Actions -------------------------------------------------------------------
$api['actions']['set']['switch']		=array(
	'url'		=>'/rest/items/{address}?type=json',
	'method'	=>'post',
	'content'	=>'{state}',
	'result_type'=>'text_state'
);
$api['actions']['set']['dimmer'] 	=$api['actions']['set']['switch'];
$api['actions']['set']['dim_level'] =$api['actions']['set']['switch'];
$api['actions']['set']['group'] 	=$api['actions']['set']['switch'];
$api['actions']['set']['scene'] 	=$api['actions']['set']['switch'];
$api['actions']['set']['shutter'] 	=$api['actions']['set']['switch'];
$api['actions']['set']['blinds'] 	=$api['actions']['set']['switch'];


//#############################################################################
// CUSTOM api_client ##########################################################
//#############################################################################

// List actions URLS -------------------------------------
$api['actions']['list']['device']		=array(
	'url'			=>'/rest/items/?type=json',
	'method'		=>'get',
	'content'		=>'',
);
$api['actions']['list']['info']		=array(
	'url'			=>'/rest/items/Date?type=json',
	'method'		=>'get',
	'content'		=>'',
);

//$api['actions']['list']['scene']		='/';
//$api['actions']['list']['camera']		='/';
//$api['actions']['list']['info']		='/';
//$api['actions']['set']['scene']		='/';


// States (auto formatted if defined)  ---------------------------------------
// states (values) from the 'result' rows
$api['states']['command']['switch']['OFF']		='off';		
$api['states']['command']['switch']['ON']		='on';		
$api['states']['command']['switch']['Undefined']='';		

$api['states']['command']['shutter']['0']		='off';		
$api['states']['command']['shutter']['100']		='on';		


$api['states']['sensor']['bool']['OFF']		='off';		
$api['states']['sensor']['bool']['ON']		='on';		
$api['states']['sensor']['bool']['OPEN']	='off';		
$api['states']['sensor']['bool']['CLOSED']	='on';		

$api['states']['scene']['group']['OFF']			='off';		
$api['states']['scene']['group']['ON']			='on';		
$api['states']['scene']['group']['Undefined']	='mixed';		
$api['states']['scene']['group']['Uninitialized']='mixed';		
$api['states']['scene']['group']['OPEN']		='off';		
$api['states']['scene']['group']['CLOSED']		='on';		


//json definition (auto formatted if defined) -------------------------------
// fields from the 'result' rows
$api['fields']['name']				="name";
$api['fields']['raw_value1']		="state";
//$api['fields']['battery_level']	="BatteryLevel";
//$api['fields']['signal_level']	="SignalLevel";


//json_get definition ----------------------------------------------------------
//$api['json']['status']="status";	//field with the Status of the response
$api['json']['result']="item";	//field with the list of devices

// json received statuts value in the "status" field
//$api['json_status']['ok']	='OK';
//$api['json_status']['err']	='ERROR';

?>
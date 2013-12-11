<?php
//#############################################################################
// REQUIRED #################################################################
//#############################################################################

$api['method']='json_mixed'; //api method : json_get | json_rpc2 | json_mixed

//api URL ----------------------------------------------------------------
$api['urls']['api']	=$this->conf['urls']['host'].':8080';
$api['urls']['www']	=$this->conf['urls']['host'].':8080';
$api['dir_admin']	='/openhab.app';


// Set Commands Values -------------------------------------------------------
// Values used when Sending a command
$api['set']['switch']['on']			='ON';
$api['set']['switch']['off']		='OFF';

//$api['set']['scene']['on']		='On';
//$api['set']['scene']['off']		='Off';

//$api['set']['group']['on']		='On';
//$api['set']['group']['off']		='Off';

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
//$api['actions']['set']['dimmer']		='/command/{address}/{state}';
//$api['actions']['set']['dim_level']	='/command/{address}/{state}';
//$api['actions']['set']['scene']		='/command/{address}/{state}';
//$api['actions']['set']['group']		='/command/{address}/{state}';


//#############################################################################
// CUSTOM api_client ##########################################################
//#############################################################################

// List actions URLS -------------------------------------
$api['actions']['list']['device']		=array(
	'url'			=>'/rest/items/?type=json',
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
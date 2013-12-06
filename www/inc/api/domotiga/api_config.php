<?php
//#############################################################################
// REQUIRED #################################################################
//#############################################################################

$api['method']='json_rpc2'; //api method : json_get | json_rpc2

//api URL ----------------------------------------------------------------
if(isset($this->conf['urls']['server_domotiga'])){
	//$api['urls']['www']		=$this->conf['urls']['server_domotiga'].'';
	$api['urls']['api']		=$this->conf['urls']['server_domotiga'].':9090';
}
else{
	//$api['urls']['www']	=$this->conf['urls']['www'].'';
	$api['urls']['api']	=$this->conf['urls']['www'].':9090';
}
//$api['urls']['admin']	=$api['urls']['www'].'/admin/';


// Set Commands Values -------------------------------------------------------
// Values used when Sending a command
$api['set']['switch']['on']			='On';
$api['set']['switch']['off']		='Off';

$api['set']['scene']['on']			='On';
$api['set']['scene']['off']			='Off';

$api['set']['group']['on']			='On';
$api['set']['group']['off']			='Off';

$api['set']['dimmer']['on']			='On';
$api['set']['dimmer']['off']		='Off';
$api['set']['dimmer']['min']		=0;
$api['set']['dimmer']['max']		=100;


// Actions -------------------------------------------------------------------
$api['actions']['set']['switch']		=array(
											'method'	=>'device.set',
											'params'	=>array(
													'device_id'	=>'{address}',
													'value'		=>'{state}'
												)
											);
$api['actions']['set']['dimmer']		=array(
											'method'	=>'device.set',
											'params'	=>array(
													'device_id'	=>'{address}',
													'value'		=>'{state}'
												)
											);
$api['actions']['set']['dim_level']		=array(
											'method'	=>'device.set',
											'params'	=>array(
													'device_id'	=>'{address}',
													'value'		=>'Dim <{state}>'
												)
											);


//#############################################################################
// CUSTOM api_client ##########################################################
//#############################################################################

// List actions URLS -------------------------------------
$api['actions']['list']['device_enabled'] =array(
											'method'	=>'device.list',
											'params'	=>array(
													'list'=>'enabled'
												)
											);
$api['actions']['get']['astro'] =array(
											'method'	=>'astro.get',
											'params'	=>array(
												)
											);

// States (auto formatted if defined)  ---------------------------------------
// Received Values
$api['states']['command']['switch']['Off']		='off';
$api['states']['command']['switch']['On']		='on';

$api['states']['command']['dimmer']['Off']		='off';
$api['states']['command']['dimmer']['On']		='on';

$api['states']['sensor']['pir']['No Motion']	='off';
$api['states']['sensor']['pir']['Dark']			='off';

$api['states']['sensor']['gas']['Idle']			='off';

$api['states']['sensor']['bool']['Online']		='on';
$api['states']['sensor']['bool']['Closed']		='off';
$api['states']['sensor']['bool']['Open']		='on';

$api['states']['security']['door']['Closed']	='off';
$api['states']['security']['door']['Open']		='on';

//$api['states']['scene']['scene']['Off']			='off';		
//$api['states']['scene']['scene']['On']			='on';		


//json definition (auto formatted if defined) -------------------------------
// fields from the 'result' rows
$api['fields']['name']				="name";
$api['fields']['address']			="device_id";
$api['fields']['raw_value1']		="value1";
$api['fields']['raw_value2']		="value2";
$api['fields']['battery_status']	="batterystatus";
//$api['fields']['battery_level']	="?";
//$api['fields']['signal_level']	="?";

?>
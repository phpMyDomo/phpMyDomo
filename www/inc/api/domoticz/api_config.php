<?php
//#############################################################################
// REQUIRED #################################################################
//#############################################################################

$api['method']='json_get'; //api method : json_get | json_rpc2

//api URL ----------------------------------------------------------------
if(isset($this->conf['urls']['server_domoticz'])){
	$api['urls']['www']		=$this->conf['urls']['server_domoticz'].':8080';
}
else{
	$api['urls']['www']	=$this->conf['urls']['www'].':8080';
}
$api['urls']['api']		=$api['urls']['www'].'/json.htm';
$api['urls']['admin']	=$api['urls']['www'];


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
$api['set']['dimmer']['min']		=1;
$api['set']['dimmer']['max']		=16;


// Actions -------------------------------------------------------------------
$api['actions']['set']['switch']		='?type=command&param=switchlight&idx={address}&switchcmd={state}';
$api['actions']['set']['dimmer']		='?type=command&param=switchlight&idx={address}&switchcmd={state}';
$api['actions']['set']['dim_level']		='?type=command&param=switchlight&idx={address}&switchcmd=Set Level&level={state}';
$api['actions']['set']['scene']			='?type=command&param=switchscene&idx={address}&switchcmd={state}';
$api['actions']['set']['group']			='?type=command&param=switchscene&idx={address}&switchcmd={state}';


//#############################################################################
// CUSTOM api_client ##########################################################
//#############################################################################

// List actions URLS -------------------------------------
$api['actions']['list']['device']		='?type=devices&filter=all&used=true&order=Name';
$api['actions']['list']['scene']		='?type=scenes';
$api['actions']['list']['camera']		='?type=cameras';
//$api['actions']['list']['switch']		='?type=command&param=getlightswitches';
$api['actions']['list']['info']			='?type=command&param=getSunRiseSet';



// States (auto formatted if defined)  ---------------------------------------
// states (values) from the 'result' rows
$api['states']['command']['switch']['Off']		='off';		
$api['states']['command']['switch']['On']		='on';		

$api['states']['command']['dimmer']['Off']		='off';		
$api['states']['command']['dimmer']['On']		='on';		

$api['states']['scene']['scene']['Off']			='off';		
$api['states']['scene']['scene']['On']			='on';		
$api['states']['scene']['scene']['Mixed']		='mixed';		

$api['states']['scene']['group']['Off']			='off';		
$api['states']['scene']['group']['On']			='on';		
$api['states']['scene']['group']['Mixed']		='mixed';		


//json definition (auto formatted if defined) -------------------------------
// fields from the 'result' rows
$api['fields']['raw_value1']	="Status";
$api['fields']['address']		="idx";
$api['fields']['name']			="Name";
$api['fields']['battery_level']	="BatteryLevel";
$api['fields']['signal_level']	="SignalLevel";

//json_get definition ----------------------------------------------------------
$api['json']['status']="status";	//field with the Status of the response
$api['json']['result']="result";	//field with the list of devices

// json received statuts value in the "status" field
$api['json_status']['ok']	='OK';
$api['json_status']['err']	='ERR';

?>
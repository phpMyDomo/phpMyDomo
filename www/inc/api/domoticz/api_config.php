<?php
//#############################################################################
// REQUIRED #################################################################
//#############################################################################

$api['method']='json_get'; //api method : json_get | json_rpc2

//api URL ----------------------------------------------------------------
$api['urls']['api']	=$this->conf['urls']['host'].':8080';
$api['urls']['www']	=$this->conf['urls']['host'].':8080';
$api['dir_admin']		='/';


// Set Commands Values -------------------------------------------------------
// Values used when Sending a command
$api['set']['switch']['on']			='On';
$api['set']['switch']['off']		='Off';

$api['set']['push']['on']			='On';
$api['set']['push']['off']			='Off';

$api['set']['scene']['on']			='On';
$api['set']['scene']['off']			='Off';

$api['set']['group']['on']			='On';
$api['set']['group']['off']			='Off';

$api['set']['dimmer']['on']			='On';
$api['set']['dimmer']['off']		='Off';
$api['set']['dimmer']['min']		=1;
$api['set']['dimmer']['max']		=16;

$api['set']['blinds']['off']		='Off';		
$api['set']['blinds']['on']			='On';		


// Actions -------------------------------------------------------------------
$api['actions']['set']['switch']		='/json.htm?type=command&param=switchlight&idx={address}&switchcmd={state}';
$api['actions']['set']['push']			='/json.htm?type=command&param=switchlight&idx={address}&switchcmd={state}';
$api['actions']['set']['dimmer']		='/json.htm?type=command&param=switchlight&idx={address}&switchcmd={state}';
$api['actions']['set']['blinds']		='/json.htm?type=command&param=switchlight&idx={address}&switchcmd={state}&level=0';
$api['actions']['set']['dim_level']		='/json.htm?type=command&param=switchlight&idx={address}&switchcmd=Set+Level&level={state}';
$api['actions']['set']['scene']			='/json.htm?type=command&param=switchscene&idx={address}&switchcmd={state}';
$api['actions']['set']['group']			='/json.htm?type=command&param=switchscene&idx={address}&switchcmd={state}';
$api['actions']['set']['selector']		='/json.htm?type=command&param=switchlight&idx={address}&switchcmd=Set+Level&level={state}';


//#############################################################################
// CUSTOM api_client ##########################################################
//#############################################################################

// List actions URLS -------------------------------------
$api['actions']['list']['device']		='/json.htm?type=devices&filter=all&used=true&order=Name';
$api['actions']['list']['scene']		='/json.htm?type=scenes';
$api['actions']['list']['camera']		='/json.htm?type=cameras';
//$api['actions']['list']['switch']		='/json.htm?type=command&param=getlightswitches';
$api['actions']['list']['info']			='/json.htm?type=command&param=getSunRiseSet';



// States (auto formatted if defined)  ---------------------------------------
// states (values) from the 'result' rows
$api['states']['command']['switch']['Off']		='off';		
$api['states']['command']['switch']['On']		='on';		

$api['states']['command']['push']['Off']		='off';		
$api['states']['command']['push']['On']		='on';		

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
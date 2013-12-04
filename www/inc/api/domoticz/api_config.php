<?php
//api URL ####################################################################
if(isset($this->conf['urls']['server_domoticz'])){
	$api['urls']['www']		=$this->conf['urls']['server_domoticz'].':8080';
}
else{
	$api['urls']['www']	=$this->conf['urls']['www'].':8080';
}
$api['urls']['api']		=$api['urls']['www'].'/json.htm';
$api['urls']['admin']	=$api['urls']['www'];


// Actions ###################################################################
// API actions URLS
$api['actions']['list']['device']		='?type=devices&filter=all&used=true&order=Name';
$api['actions']['list']['scene']		='?type=scenes';
$api['actions']['list']['camera']		='?type=cameras';
//$api['actions']['list']['switch']		='?type=command&param=getlightswitches';
$api['actions']['list']['info']			='?type=command&param=getSunRiseSet';
$api['actions']['set']['device']		='?type=command&param=switchlight&idx={id}&switchcmd={state}&level=0';
$api['actions']['set']['scene']			='?type=command&param=switchscene&idx={id}&switchcmd={state}&level=0';


// Set Commands Values #########################################################
// Values used when Sending a command
$api['set']['switch']['on']			='On';
$api['set']['switch']['off']		='Off';
$api['set']['scene']['on']			='On';
$api['set']['scene']['off']			='Off';

// Get Commands Values #########################################################
// Received Values
$api['values']['scene']['Off']		='off';			
$api['values']['scene']['On']		='on';			
$api['values']['scene']['Mixed']	='mixed';			
$api['values']['group']['Off']		='off';			
$api['values']['group']['On']		='on';			
$api['values']['group']['Mixed']	='mixed';			
$api['values']['command']['Off']	='off';			
$api['values']['command']['On']		='on';			

//json definition ##############################################################
$api['json']['status']="status";	//field with the Status of the response
$api['json']['result']="result";	//field with the list of devices

// json received statuts value in the "status" field
$api['json_status']['ok']	='OK';
$api['json_status']['err']	='ERR';

// fields from the 'result' rows
$api['fields']['id']			="idx";
$api['fields']['name']			="Name";
$api['fields']['battery_level']	="BatteryLevel";
$api['fields']['signal_level']	="SignalLevel";

?>
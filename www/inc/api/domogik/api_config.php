<?php
//api URL ###################################################################
if(isset($this->conf['urls']['server_domogik'])){
	$api['urls']['www']		=$this->conf['urls']['server_domogik'].':40404';
	$api['urls']['api']		=$this->conf['urls']['server_domogik'].':40405';
}
else{
	$api['urls']['www']	=$this->conf['urls']['www'].':40404';
	$api['urls']['api']	=$this->conf['urls']['www'].':40405';
}
$api['urls']['admin']	=$api['urls']['www'].'/admin/';


// Actions ###################################################################
// API actions URLS
$api['actions']['list']['device']		='/base/device/list';
$api['actions']['list']['stats']		='/stats/*/*/all';
//$api['actions']['list']['scene']		='?type=scenes';
//$api['actions']['list']['camera']		='?type=cameras';
//$api['actions']['list']['info']			='?type=command&param=getSunRiseSet';
$api['actions']['set']['device']		='/command/{id}/{state}';
//$api['actions']['set']['scene']			='?type=command&param=switchscene&idx={id}&switchcmd={state}&level=0';


// Set Commands Values #########################################################
// Values used when Sending a command
//$api['set']['switch']['on']			='On';
//$api['set']['switch']['off']		='Off';
//$api['set']['scene']['on']			='On';
//$api['set']['scene']['off']			='Off';


// Get Commands Values #########################################################
// Received Values
//$api['values']['scene']['Off']		='off';			
//$api['values']['scene']['On']		='on';			
//$api['values']['scene']['Mixed']	='mixed';			
//$api['values']['group']['Off']		='off';			
//$api['values']['group']['On']		='on';			
//$api['values']['group']['Mixed']	='mixed';			
//$api['values']['command']['Off']	='off';			
//$api['values']['command']['On']		='on';			


//json definition ##############################################################
$api['json']['status']="status";	//field with the Status of the response
$api['json']['result']="device";	//field with the list of devices

// json received statuts value in the "status" field
$api['json_status']['ok']	='OK';
$api['json_status']['err']	='ERROR';

// fields from the 'result' rows
$api['fields']['name']				="name";
//$api['fields']['battery_level']	="BatteryLevel";
//$api['fields']['signal_level']	="SignalLevel";

?>
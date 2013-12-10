<?php
//	phpMyDomo main config File
//	http://www.phpmydomo.org


// ##############################################################################
// Minimal Configuration  #######################################################
// ##############################################################################
//api to use : domoticz | domogik | domotiga | openhab
$conf['app']['api']			="domoticz";

// Personalize the name of your Home
$conf['app']['home_name']	="Home Sweet Home";

// language file to use
$conf['app']['lang']		="en";

// Uncomment to use another skin (defaults to the 'default' skin, if not set)
//$conf['app']['skin']		="black";

//uncomment to override the locale defined in the language file
//$conf['app']['locale']		="en_US.utf8"; 



// ##############################################################################
//	Urls  #####################################################################
// ##############################################################################
/*
URLS of remote API Server.If not set default to the same server, on the default port.
$conf['urls']['server_domoticz']	="http://10.1.100.151";
$conf['urls']['server_domogik']		="http://10.1.100.152";
$conf['urls']['server_domotiga']	="http://10.1.100.153";
$conf['urls']['server_openhab']		="http://demo.openhab.org";
*/



// ##############################################################################
// Sensors Types Units ##########################################################
// ##############################################################################
// Personalize your local units, according to your server settings
$conf['units']['temp']			="°C";
$conf['units']['hum']			="%";
$conf['units']['wind']			="m/s";
$conf['units']['rain']			="mm";
$conf['units']['baro']			="hPa";
$conf['units']['hygro']			="%";

$conf['units']['wind_speed']	="m/s";
$conf['units']['wind_gust']		="m/s";

$conf['units']['wind_temp']		="°C";
$conf['units']['wind_chill']	="°C";



// ##############################################################################
// Groups #######################################################################
// ##############################################################################
/*
Define Groups shown on the home page.
Groups are displayed in the order they are created below.

Definitions:
	- GROUP_KEY: a key to identify the group (use a-z 0-1 _ or - chars) , ie "living", "room_1", "external", etc...
	- DEVICE_IDs: Unique Id of the Device (tips: grab it from the Device page)
	- GROUP_NAME: Pretty Group Name, ie: Living Room
	- 'command' for switches shown in the group
	- 'sensor' for sensors displayed in the Group Title

Example:
	$conf['groups_names']['GROUP_KEY']			="GROUP_NAME";
	$conf['groups']['GROUP_KEY']['command'][]	="DEVICE_ID_1";	
	$conf['groups']['GROUP_KEY']['command'][]	="DEVICE_ID_2";
	$conf['groups']['GROUP_KEY']['sensor'][]	="DEVICE_ID_3";
will create a group named "GROUP_NAME" (with ID "GROUP_KEY") displaying switches for DEVICE_ID_1 and DEVICE_ID_2, and showing DEVICE_ID_3 sensor values in the group title.

Another example:
	// Groups Names ----------------------------------------------------
	$conf['groups_names']['living']		="Living Room";
	$conf['groups_names']['room1']		="John's BedRoom";
	$conf['groups_names']['outside']	="OutSide";

	// Groups (unique ids) ----------------------------------------------
	$conf['groups']['living']['command'][]	="scene_group_2_status";
	$conf['groups']['living']['command'][]	="command_switch_24_status";
	$conf['groups']['living']['command'][]	="command_switch_25_status";

	$conf['groups']['outside']['command'][]	="command_switch_27_status";
	$conf['groups']['outside']['sensor'][]	="sensor_hum_7_humidity";
	$conf['groups']['outside']['sensor'][]	="sensor_temp_7_temp";

	$conf['groups']['room1']['command'][]	="command_switch_26_status";
	$conf['groups']['room1']['command'][]	="command_switch_42_status";

*/



// ##############################################################################
// Blocks #######################################################################
// ##############################################################################
/*
Blocks are shown in the right column of the HomePage.
They are 2 user defined blocks : "weather" and "sensors".
Example:
	$conf['blocks']['weather'][]="DEVICE_ID_4";
	$conf['blocks']['weather'][]="DEVICE_ID_5";

	$conf['blocks']['sensors'][]="DEVICE_ID_7";
	$conf['blocks']['sensors'][]="DEVICE_ID_8";
will show DEVICE_ID_4 and DEVICE_ID_5 in the 'weather' block, and DEVICE_ID_4 and DEVICE_ID_5 in the 'sensors' block

Another Example:
	// weather Block (unique ids) ---------------------------------------
	$conf['blocks']['weather'][]="sensor_temp_7_temp";
	$conf['blocks']['weather'][]="sensor_hum_7_humidity";
	$conf['blocks']['weather'][]="sensor_baro_7_barometer";
	$conf['blocks']['weather'][]="sensor_wind_temp_8_temp";
	$conf['blocks']['weather'][]="sensor_wind_chill_8_chill";
	$conf['blocks']['weather'][]="sensor_rain_10_rain";
	$conf['blocks']['weather'][]="sensor_wind_speed_8_speed";
	$conf['blocks']['weather'][]="sensor_wind_gust_8_gust";

	// sensor Block (unique ids) ---------------------------------------
	$conf['blocks']['sensors'][]="sensor_temp_28_temp";
*/


/*
unset($conf['groups']);
$conf['blocks']='';
$conf['blocks']['weather'][]="sensor_wind_speed_rfxcom_ve_20_speed";
$conf['blocks']['weather'][]="sensor_rain_rfxcom_pp_21";
$conf['blocks']['sensors'][]="sensor_temp_rfxcom_tr1_19";
*/



// ##############################################################################
// Cameras (if not already defined in the server devices) #######################
// ##############################################################################
/*
Define IP camera streams to be displayed in the Camera Page. if cameras_sizes is not defined, it defaults to 320x240
Example:
$conf['cameras_names']['room1']	="Room 1";
$conf['cameras_urls']['room1']	="http://user:pass@10.0.0.1/videostream.cgi";
$conf['cameras_sizes']['room1']	="640x480";

$conf['cameras_names']['room2']	="Room 2";
$conf['cameras_urls']['room2']	="http://user:pass@10.0.0.2/videostream.cgi";

*/



// ##############################################################################
// Menus To Show ################################################################
// ##############################################################################
/*
Defines menus displayed on the header or footer of the page.
If not set, all menus are shown.
*/
//$conf['menu_head']=array('home','cameras','commands');
//$conf['menu_foot']=array('home','cameras','commands','sensors','devices');




// ##############################################################################
//DEBUG : you don't need to change this #########################################
// ##############################################################################
$conf['debug']['allow']=1;					//allow debug from url (add ?debug in the url)
$conf['debug']['force']=0;					//always show debug
$conf['debug']['level']=E_ALL ^E_NOTICE;	//php error level reporting

?>
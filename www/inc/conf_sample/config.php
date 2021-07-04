<?php
// ##############################################################################
// Minimal Configuration  #######################################################
// ##############################################################################
//API to use: 'domoticz' | 'domogik' | 'domotiga' | 'openhab' | 'openhab1' ... Alphas  APIs: 'pilight' | 'freedomotic' 
$conf['app']['api']			="openhab";

// Personalize the name of your Home
$conf['app']['home_name']	="Home Sweet Home";

// language file to use : (name of the folder in /www/inc/lang/)
$conf['app']['lang']		="en";	// en | de | fr | it | nl | uk

//choose a photoframe method : directory | iphoto
$conf['app']['photoframe']		="directory"; 

// Skin : Uncomment to use another skin (defaults to the 'default' skin, if not set)
//$conf['app']['skin']		="black"; // default | black | metal

// SubDirectory: Uncomment ONLY if you want to install phpMydomo in a sub directory of your web server root (not recommended at this time)
//$conf['app']['dir']		="/my_directory";

//Locale: uncomment to override the locale defined in the language file
//$conf['app']['locale']		="en_US.utf8"; 

// If your server doesnt generate sunrise and sunset times, uncomment and enter your location here in the format : "City, Country"
// PMD will automatically Query Google, to get your location, and calculate Sunset and Sunrise from the latitude and longitude returned by Google
//$conf['app']['location']	="Paris, France";


// The home page automatically reload after xx seconds, to refresh the buttons or sensor states.
$conf['app']['reload_time']			="120"; // time in seconds

// ScreenSaver mode : is set, instead of refreshing the home windows, PMD automatically jump to one of the selected destination
$conf['app']['screensaver_mode']	="clock"; 	// ''|'clock'|'photoframe'	=>  No ScreenSaver | jump to Clock page | jump to album selected in 'screensaver_fp_album'
//$conf['app']['screensaver_pf_album']="Landscapes"; 		// (required) album ID to launch only when ScreenSaver mode is set to 'photoframe';

// Show or hide sensors names in home groups: 0=hide, 1=show , x= show and truncate after x characters
$conf['app']['groups_sensors_names']	="0";

// When using actions, this is the default debounce period (in seconds) : It prevents the same action to be triggered multiple times, if the same action is called within this period.
$conf['app']['actions_debounce']	="3";


//	Urls  #####################################################################
// Change these, if your domotic server is NOT on the same server than phpMyDomo, or if you are not using default server ports.

// Remote API Server. ---------------------------------------------------
// Format : http://hostname_or_ip:port

$conf['urls']['server_openhab']	="http://demo.openhab.org:8080";
//$conf['urls']['server_domoticz']	="http://10.1.100.151:8080";
//$conf['urls']['server_domogik']	="http://10.1.100.152:40405";
//$conf['urls']['server_domotiga']	="http://10.1.100.153:9090";
//$conf['urls']['server_pilight']	="http://10.1.100.153:5000";
//$conf['urls']['server_freedomotic']	="http://admin:admin@93.186.254.203:9111";

// Server Website, the normal GUI of your domotic server -----------------
// Format : http://hostname_or_ip:port(/subdir)

$conf['urls']['www_openhab']		="http://demo.openhab.org:8080/openhab.app?sitemap=demo";
//$conf['urls']['www_domoticz']		="http://10.1.100.151:8080";
//$conf['urls']['www_domogik']		="http://10.1.100.152:40404";
//$conf['urls']['www_domotiga']		="http://10.1.100.153:9090";
//$conf['urls']['www_pilight']		="http://10.1.100.153:5000";
//$conf['urls']['www_freedomotic']	="http://93.186.254.203:8090";



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
$conf['units']['volume']		="m3";
$conf['units']['volume_sub']	="L";



// ##############################################################################
// RGB(W) Color presets ##########################################################
// ##############################################################################
// RGB type buttons ONLY: Each (hex) color is displayed as a buttons in the color Chooser 
$conf['colors']['red']		='FF0000';
$conf['colors']['green']	='00FF00';
$conf['colors']['blue']		='0000FF';
$conf['colors']['yellow']	='FFFF00';
$conf['colors']['cyan']		='00FFFF';
$conf['colors']['white']	='FFFFFF';



// ##############################################################################
// Groups #######################################################################
// ##############################################################################
/*
Define Groups shown on the home page.
Groups are displayed in the order they are created below.

Definitions:
	- GROUP_KEY: a key to identify the group (use a-z 0-1 _ or - chars) , ie "living", "room_1", "external", etc...
	- DEVICE_IDs: Unique Id of the Device (tips: grab it from the phpMyDomo Devices page)
	- GROUP_NAME: Group Name Displayed, ie: "Living Room"
	- 'command' for switches shown in the group
	- 'sensor' for sensors displayed in the Group Title

Example 1:
	$conf['groups_names']['GROUP_KEY']			="GROUP_NAME";
	$conf['groups']['GROUP_KEY']['command'][]	="DEVICE_ID_1";	
	$conf['groups']['GROUP_KEY']['command'][]	="DEVICE_ID_2";
	$conf['groups']['GROUP_KEY']['sensor'][]	="DEVICE_ID_3";
will create a group named "GROUP_NAME" (with ID "GROUP_KEY") displaying switches for DEVICE_ID_1 and DEVICE_ID_2, and showing DEVICE_ID_3 sensor values in the group title.

Example 2: (working with openHab demo API)
*/

// Groups Names ----------------------------------------------------
$conf['groups_names']['living']		="Living Room";
$conf['groups_names']['outside']	="OutDoor";
$conf['groups_names']['heating']	="Heating";
$conf['groups_names']['office']		="Office";

// Groups (unique ids) ----------------------------------------------

$conf['groups']['outside']['command'][]	="command_switch_light_garden_terrace";
$conf['groups']['outside']['command'][]	="command_switch_gf_terrace_light";
$conf['groups']['outside']['command'][]	="command_switch_outside_light";
$conf['groups']['outside']['sensor'][]	="sensor_pir_gf_terrace_motion";
$conf['groups']['outside']['sensor'][]	="sensor_door_ou_terrace_window";

$conf['groups']['living']['command'][]	="command_switch_livingroom_light";
$conf['groups']['living']['command'][]	="command_switch_livingdining_aircon";
$conf['groups']['living']['command'][]	="command_switch_livingdining_fan";
$conf['groups']['living']['command'][]	="command_dimmer_light_gf_living_table";
$conf['groups']['living']['command'][]	="command_shutter_shutter_gf_living";
$conf['groups']['living']['sensor'][]	="sensor_bool_window_gf_living";
$conf['groups']['living']['sensor'][]	="sensor_temp_temperature_gf_living";

$conf['groups']['office']['command'][]	="command_switch_ff_office_light";
$conf['groups']['office']['command'][]	="command_heating_heating_ff_office";
$conf['groups']['office']['command'][]	="command_dimmer_ff_office_blind";

$conf['groups']['heating']['command'][]	="scene_group_heating";
$conf['groups']['heating']['command'][]	="command_heating_heating_ff_bath";
$conf['groups']['heating']['command'][]	="command_heating_heating_ff_bed";
$conf['groups']['heating']['command'][]	="command_heating_heating_ff_child";
$conf['groups']['heating']['command'][]	="command_heating_heating_gf_corridor";
$conf['groups']['heating']['command'][]	="command_heating_heating_gf_kitchen";
$conf['groups']['heating']['command'][]	="command_heating_heating_gf_living";
$conf['groups']['heating']['command'][]	="command_heating_heating_gf_toilet";
$conf['groups']['heating']['sensor'][]="sensor_temp_temperature_setpoint";


// ##############################################################################
// Devices Icons ################################################################
// ##############################################################################
/*
Define a different icons per devices: Normally icons are defined, depending on the device type (from /static/global/img/types/), but you can choose to override them, with your own device icon, for each device

Format:
$conf['devices_icons']['DEVICE_ID']['LOCATION']	="ICON_NAME";

Definitions:
	- DEVICE_ID: Unique Id of the Device (tips: grab it from the phpMyDomo Devices page)
	- LOCATION: Location of the Icon ; devices|types|custom 
		- "devices" : Icon is choosen from the /static/global/img/devices/ folder
		- "types" 	: Icon is choosen from the /static/global/img/types/ folder
		- "custom" : Icon is choosen from the /static/custom/devices/ folder. This is the RIGHT place to add your OWN custom icons
	-ICON_NAME : the name of the icon files , ie "alarm1" whill select : "icon48_alarm1_on.png" or "icon48_alarm1_off.png" depending on the device's state

Examples:
*/

$conf['devices_icons']['scene_group_heating']['devices']				="switch1";


// ##############################################################################
// Devices Warnings #############################################################
// ##############################################################################
/*
Compare device value/state with your own value(s) to highlight (in red) the sensor in the main home page.

Format:
$conf['devices_warnings']['DEVICE_ID'][]	="Oxxx";

Definitions:
	- DEVICE_ID: Unique Id of the Device (tips: grab it from the phpMyDomo Devices page)
	- Oxxx: is the Operator + the value to compare to the current device value  
		- "O" : Operator to use :
			- "<" lower than
			- ">" greater than
			- "=" equal to
			- "~" NOT equal to
		- "xxx" : the value to compare to
You can set multiple warning per sensors

Example 1:
$conf['devices_warnings']['sensor_bool_window_gf_living'][]	="=off";
This would highlight the 'sensor_bool_window_gf_living' sensor whenever it is open (equals to 'off' state).

Example 2:
$conf['devices_warnings']['sensor_temp_temperature_gf_living'][]	=">40";
$conf['devices_warnings']['sensor_temp_temperature_gf_living'][]	="<4";
This would highlight the 'sensor_temp_temperature_gf_living' sensor whenever the temperature is greater than 40° OR lower than 4°.

*/
$conf['devices_warnings']['sensor_temp_weather_temperature'][]	=">50";


// ##############################################################################
// Blocks #######################################################################
// ##############################################################################
/*
Blocks are shown in the right column of the Home Page.

Format:
	$conf['blocks']['NAME'][]="DEVICE_ID";

Definitions:
	- NAME : Block's Name (if you set "weather" or "sensors" the block's title name and icons will be pre-set)
	- DEVICE_ID : Unique Id of the Device (tips: grab it from the phpMyDomo Devices page)

Example 1:
	$conf['blocks']['weather'][]="DEVICE_ID_4";
	$conf['blocks']['weather'][]="DEVICE_ID_5";

	$conf['blocks']['sensors'][]="DEVICE_ID_7";
	$conf['blocks']['sensors'][]="DEVICE_ID_8";
will show DEVICE_ID_4 and DEVICE_ID_5 in the 'weather' block, and DEVICE_ID_4 and DEVICE_ID_5 in the 'sensors' block

Example 2: (working with openHab demo API)
*/

// weather Block (unique ids) ---------------------------------------
$conf['blocks']['weather'][]="sensor_temp_weather_temperature";

// Sensor Block (unique ids) ---------------------------------------
$conf['blocks']['sensors'][]="sensor_temp_temperature_gf_living";
$conf['blocks']['sensors'][]="sensor_temp_temperature_ff_office";
$conf['blocks']['sensors'][]="sensor_temp_temperature_ff_child";
$conf['blocks']['sensors'][]="sensor_temp_temperature_ff_bed";
$conf['blocks']['sensors'][]="sensor_temp_temperature_gf_toilet";
$conf['blocks']['sensors'][]="sensor_hygro_f2_balcony_humidity";
$conf['blocks']['sensors'][]="sensor_hygro_balcony_humidity";
$conf['blocks']['sensors'][]="sensor_pir_ff_backyard_motion";
$conf['blocks']['sensors'][]="sensor_door_c_guestroom_window";
$conf['blocks']['sensors'][]="sensor_bool_garage_door";
$conf['blocks']['sensors'][]="sensor_custom_wifi_level";
$conf['blocks']['sensors'][]="sensor_custom_radio_station";
$conf['blocks']['sensors'][]="sensor_text_moon_phase";



// ##############################################################################
// Links Block ##################################################################
// ##############################################################################
/*
The Links Block allow you to create a block , with custom links to various websites

Example: 

$conf['blocks']['links'][]=array(
	'url'	=>'http://www.phpmydomo.com',	// (required) url to link to
	'name'	=>'PMD WebSite',				// (required) button name 
	'blank'	=>'1',							// 0 (default) | 1 : launch in a new window or not 
	'icon'	=>'external-link',				// icon name (default to 'bookmark' if not set)  refer to http://fontawesome.io/icons/ 
);

$conf['blocks']['links'][]=array(
	'url'	=>'https://github.com/phpMyDomo/phpMyDomo/issues',	// (required) url to link to
	'name'	=>'PMD issues',					// (required) button name 
	'blank'	=>'1',							// 0 (default) | 1 : launch in a new window or not 
	'icon'	=>'github',						// icon name (default to 'bookmark' if not set)  refer to http://fontawesome.io/icons/ 
);

*/


// ##############################################################################
// Cameras (if not already defined in the server devices) #######################
// ##############################################################################
/*
Define IP camera streams to be displayed in the Camera Page. if cameras_sizes is not defined, it defaults to 320x240
Example :
$conf['cameras_names']['room1']	="Room 1";
$conf['cameras_urls']['room1']	="http://user:pass@10.0.0.1/videostream.cgi";
$conf['cameras_sizes']['room1']	="640x480";

$conf['cameras_names']['room2']	="Room 2";
$conf['cameras_urls']['room2']	="http://user:pass@10.0.0.2/videostream.cgi";

Example 2:
*/
$conf['cameras_names']['room2']	="Comune di Melfi";
$conf['cameras_urls']['room2']	="http://webcam.comunemelfi.it:8087/mjpg/video.mjpg";
$conf['cameras_sizes']['room2']	="1280x1024";

$conf['cameras_names']['caen']	="Grenoble";
$conf['cameras_urls']['caen']	="http://webcam.minatec.grenoble-inp.fr/mjpg/video.mjpg";
$conf['cameras_sizes']['caen']	="640x480";

$conf['cameras_names']['somewhere']	="Somewhere";
$conf['cameras_urls']['somewhere']	="http://monumentcam.kdhnc.com/mjpg/video.mjpg";
$conf['cameras_sizes']['somewhere']	="800x600";

$conf['cameras_names']['sandiego']	="San Diego School";
$conf['cameras_urls']['sandiego']	="http://132.239.12.145/mjpg/video.mjpg";
$conf['cameras_sizes']['sandiego']	="640x400";


// ##############################################################################
// Menus To Show ################################################################
// ##############################################################################
/*
Defines menus displayed on the header or footer of the page.
If not set, all menus are shown.
*/
$conf['menu_head']=array('home','clock','squeeze','cameras','photos');
//$conf['menu_foot']=array('home','clock','squeeze','cameras','photos','admin');




// ##############################################################################
//DEBUG : you don't need to change this #########################################
// ##############################################################################
$conf['debug']['allow']=1;					//allow debug from url (add ?debug in the url)
$conf['debug']['force']=0;					//always show debug
$conf['debug']['level']=E_ALL ^E_NOTICE;	//php error level reporting

?>
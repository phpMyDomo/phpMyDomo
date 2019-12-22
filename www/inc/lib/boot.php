<?php

ini_set('display_errors','On');

// base ------------------------------------------------------------------------------------
$conf=array();
$conf['app']['version']		="0.600b";
$conf['app']['name']		="phpMyDomo";

//detect https
if ( isset( $_SERVER["HTTPS"] ) and strtolower($_SERVER["HTTPS"]) == "on"){
	$conf['app']['protocol']  =  "https://";
}
else {
	$conf['app']['protocol']  =  "http://";
}

//$conf['app']['protocol']	=(strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https') ? 'https://':'http://';

// menus ------------------------------------------------------------------------------------
$conf['menu_urls']['home']		='home';	
$conf['menu_urls']['clock']		='clock';	
$conf['menu_urls']['squeeze']	='squeeze';	
$conf['menu_urls']['cameras']	='cameras';	
$conf['menu_urls']['photos']	='photos';	
$conf['menu_urls']['commands']	='commands';	
$conf['menu_urls']['sensors']	='sensors';	
$conf['menu_urls']['devices']	='devices';	

$conf['menu_icons']['home']		='fa fa-home';
$conf['menu_icons']['clock']	='fa fa-clock-o';
$conf['menu_icons']['squeeze']	='fa fa-music';	
$conf['menu_icons']['cameras']	='fa fa-video-camera';
$conf['menu_icons']['photos']	='fa fa-camera';
$conf['menu_icons']['commands']	='fa fa-power-off';
$conf['menu_icons']['sensors']	='fa fa-cloud';
$conf['menu_icons']['devices']	='fa fa-gears';

//menus to show
isset($conf['menu_head']) or $conf['menu_head']=array_keys($conf['menu_urls']);
isset($conf['menu_foot']) or $conf['menu_foot']=array_keys($conf['menu_urls']);


//set paths -------------------------------------------------------------------------------
$conf['paths']['includes']	=dirname(dirname(__FILE__)).'/';
$conf['paths']['www']		=dirname(dirname(dirname(__FILE__))).'/';
$conf['paths']['root']		=dirname($conf['paths']['www']).'/';

$conf['paths']['apis']			=$conf['paths']['includes'].'api/';
$conf['paths']['actions']		=$conf['paths']['includes'].'action/';
$conf['paths']['confs']			=$conf['paths']['includes'].'conf/';
$conf['paths']['caches']		=$conf['paths']['includes'].'cache/';
$conf['paths']['docs']			=$conf['paths']['includes'].'doc/';
$conf['paths']['libs']			=$conf['paths']['includes'].'lib/';
$conf['paths']['langs']			=$conf['paths']['includes'].'lang/';
$conf['paths']['pages']			=$conf['paths']['includes'].'page/';
$conf['paths']['photoframes']	=$conf['paths']['includes'].'photoframe/';
$conf['paths']['smarty']		=$conf['paths']['includes'].'smarty/';
$conf['paths']['vendors']		=$conf['paths']['includes'].'vendor/';
$conf['libs']['jsonrpc_client']	=$conf['paths']['vendors']."JsonRPC/src/JsonRPC/Client.php";
$conf['libs']['growl_atoload']	=$conf['paths']['vendors']."PEAR/Net_Growl/Net/Growl/Autoload.php";
$conf['libs']['mysensors']		=$conf['paths']['vendors']."MySensors_php_API/src/mysensors.class.php";
$conf['libs']['smarty']			=$conf['paths']['vendors']."Smarty/libs/Smarty.class.php";
$conf['paths']['static']		=$conf['paths']['www'].'static/';


// require ---------------------------------------------------------------------------------
require_once($conf['paths']['confs'].'config.php');
isset($conf['app']['photoframe']) or $conf['app']['photoframe']='';

$conf['paths']['api']		=$conf['paths']['apis'].		$conf['app']['api'].'/';
$conf['paths']['photoframe']=$conf['paths']['photoframes'].	$conf['app']['photoframe'].'/';
$conf['paths']['lang']		=$conf['paths']['langs'].	$conf['app']['lang'].'/';
$conf['paths']['lang_en']	=$conf['paths']['langs'].	'en/';

//set libs -------------------------------------------------------------------------------
$conf['libs']['kernel']			=$conf['paths']['libs']."pmd_kernel.php";
$conf['libs']['root']			=$conf['paths']['libs']."pmd_root.php";
$conf['libs']['root_action']	=$conf['paths']['libs']."pmd_root_action.php";
$conf['libs']['root_api_client']=$conf['paths']['libs']."pmd_root_api_client.php";
$conf['libs']['root_page']		=$conf['paths']['libs']."pmd_root_page.php";
$conf['libs']['root_photoframe']=$conf['paths']['libs']."pmd_root_photoframe.php";

$conf['libs']['api_client']		=$conf['paths']['api']."api_client.php";
$conf['libs']['photoframe']		=$conf['paths']['photoframe']."photoframe.php";

// set urls ---------------------------------------------------------------------------------
if(!isset($conf['app']['dir'])){$conf['app']['dir']='';}
$conf['urls']['host']		=$conf['app']['protocol'].$_SERVER["HTTP_HOST"];
$conf['urls']['www']		=$conf['urls']['host'].$conf['app']['dir'];
$conf['urls']['static']		=$conf['urls']['www'].'/static';
$conf['urls']['minify']		=$conf['urls']['www'].'/static/min';
$conf['urls']['pmd_url']	="http://www.phpmydomo.org";
$conf['urls']['pmd_api']	=$conf['urls']['pmd_url'].'/api/?q=';
$conf['urls']['pmd_dl_zip']	=$conf['urls']['pmd_url'].'/download/?t=zip';
$conf['urls']['pmd_dl_gz']	=$conf['urls']['pmd_url'].'/download/?t=gz';
$conf['urls']['pmd_changelog']	='https://raw.github.com/phpMyDomo/phpMyDomo/master/www/inc/doc/changelog.md';
$conf['urls']['ss_pf_album']=$conf['urls']['www'].'/'.$conf['menu_urls']['photos'].'?id=';
$conf['urls']['ss_clock']	=$conf['urls']['www'].'/'.$conf['menu_urls']['clock'];
$conf['urls']['pmd_github']	="https://github.com/phpMyDomo/phpMyDomo";




// BOOTING ###################################################################################

//set debug ---------------------------------------------------------------------------------
error_reporting(0);
if($conf['debug']['force'] or ($conf['debug']['allow'] and isset($_GET['debug']) ) ){
	$conf['debug']['show']=1;
}
if($conf['debug']['show']){
	error_reporting($conf['debug']['level']);
}

// check install ----------------------------------------
function DieError($m){
	echo "<h2 color='red'>Fatal Error</h2>$m";
	exit;
}

if(!file_exists($conf['paths']['caches'].'version')){
	echo "<H1>Installing {$conf['app']['name']} (version {$conf['app']['version']})</H1>";
	
	echo "Checking cache folder : ";
	if(!is_writable($conf['paths']['caches'])){DieError("<p><b>{$conf['paths']['caches']}</b> is not writable, please chmod it to 777</p><i>chmod -R 777 {$conf['paths']['caches']}</i>");}
	
	echo "OK<br> Checking minimum config :";
	if(!$conf['app']['api'])					{DieError('Client API is not set');}
	echo "OK<br>";

	//success
	file_put_contents($conf['paths']['caches'].'version',$conf['app']['version']);
	echo "<p>Cool! <b>{$conf['app']['name']} has been successfully installed!</b></p>";
	echo "<i>(Please refresh this page if not done automatically) <meta http-equiv='refresh' content='5'></i>";
	exit;
}

if(file_exists($conf['paths']['caches'].'last_version')){
	$conf['app']['last_version']=(float) file_get_contents($conf['paths']['caches'].'last_version');
}
if(file_exists($conf['paths']['caches'].'version')){
	$conf['app']['installed_version']	=file_get_contents($conf['paths']['caches'].'version');
}

//starting ------------------------------------------------------
require_once($conf['libs']['kernel']);
$dw=new PMD_Kernel();
?>

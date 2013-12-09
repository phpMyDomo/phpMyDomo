<?php
/*
	phpMyDomo : Home Automation Web Interface
	http://www.phpmydomo.org
    ----------------------------------------------
	Copyright (C) 2013  Francois Dechery

	LICENCE: ###########################################################
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>
	#####################################################################
*/

class PMD_Kernel{
	
	//global conf
	var $conf=array();
	var $lang=array();
	
	//objects
	var $o_api;
	var $o_page;

	//----------------------------------------------------------------------------------
	function __construct(){
		$this->_init();
	}

	//----------------------------------------------------------------------------------
	function _init(){
		global $conf;
		$this->conf=$conf;
		//root object
		require($this->conf['libs']['root']);

		//page
		require($this->conf['libs']['root_page']);

		//english
		$lang=array();
		include_once($conf['paths']['lang_en'].'global.php');
		$this->lang['global']=$lang;

		//merge local lang
		$lang=array();
		include_once($this->conf['paths']['lang'].'global.php');
		$this->lang['global']=array_merge($this->lang['global'],$lang);

		//api
		require($this->conf['libs']['root_api_client']);
		require($this->conf['libs']['api_client']);
		$this->o_api= new PMD_ApiClient($this);
		
		//locale
		$locale= isset($this->conf['app']['locale']) ? $this->conf['app']['locale'] : $this->lang['global']['locale'];
		setlocale(LC_TIME,	$locale);
		setlocale(LC_MESSAGES,	$locale);		
	}
	
	//----------------------------------------------------------------------------------
	function Controller(){
		$url=$_SERVER['REQUEST_URI'];
		$page=preg_replace('#^/#','',$url);
		$page=preg_replace('#\?.*#','',$page);
		$page=preg_replace('#/$#','',$page);
		$page=str_replace('/','_',$page);
		$page or $page='home';
		
		$this->conf['app']['page']=$page;
		$file=$this->conf['paths']['pages']."$page.php";
		if(file_exists($file)){
			require_once($file);
			$this->o_page= new PMD_Page($this);
			$this->o_page->Run();
		}
		else{
			$this->PageError('404',"'$page' is not defined");
		}
	}
	
	//----------------------------------------------------------------------------------
	function PageError($code='404', $txt="Not Found"){
		echo "<h1 class='color:red'>$code: $txt</h1>";
		exit;
	}

	//----------------------------------------------------------------------------------
	function PageDebug($txt='', $arr="", $exit=1){
		$out='';
		$txt and $out .="$txt<br>\n";
		if($arr){
			$out .="<pre>".print_r($arr,true)."</pre>\n";
		}
		if($exit){
			echo "<h1 clor=red>Debug</h1>$out";
			exit;
		}
		echo "$out";
	}


} 
?>
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
		$sub=$this->conf['app']['dir'];
		$page=preg_replace("#^{$sub}/#",'',$url);
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
			$this->PageError('404',"<i>$page</i> is not defined !");
		}
	}
	
	//----------------------------------------------------------------------------------
	function PageError($code='404', $txt="Not Found"){
		$p['err_code']	=$code;
		$p['err_txt']	=$txt;
		$this->conf['app']['page']='error';
		if(!is_object($this->o_page)){
			$this->o_page= new PMD_Root_Page($this);
		}
		$this->o_page->Display($p);
	}

	//----------------------------------------------------------------------------------
	function PageDebug($txt='', $arr="", $exit=1){

		if($exit){
			$p['content_txt']		=$txt;
			$p['content_backtrace']	=$this->_GetBacktraceAll();
			$p['content_arr']		=print_r($arr,true);
			$this->conf['app']['page']='debug';
			if(!is_object($this->o_page)){
				$this->o_page= new PMD_Root_Page($this);
			}
			$this->o_page->Display($p);
			exit;
		}
		else{
			$out='';
			$txt and $out .="$txt<br>\n";
			if($arr){
				$out .="<pre>".print_r($arr,true)."</pre>\n";
			}
			echo "<div class='debug'>$out</div>";
		}
	}


	//-----------------------------------------------------------------------------------------------------------------------
	private function _GetBacktraceAll($remove_steps=0){
		$remove_steps +=3;
		$backtrace = debug_backtrace();
		
		krsort($backtrace);
		//remove last step
		for ($i=0; $i < $remove_steps ; $i++) { 
			array_pop($backtrace);
		}
		$cb=count($backtrace);
		foreach($backtrace as $back){
			if(!$back['class']){continue;}
			$path	.= $back['class'].'->'.$back['function'];
			$cb--; $cb and $path .=",<br> ";
		}
		$path=str_replace('PMD_','&copy;',$path);
		return $path;
	}


} 
?>
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
	
	//latest cache file
	var $cache_file;

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
		
		if($debug_lang=$_GET['lang']){ // allow forcing lang in URL (for debugging purpose)
			$this->conf['paths']['lang']=$this->conf['paths']['langs'].$debug_lang.'/';
		}
		include_once($this->conf['paths']['lang'].'global.php');
		$this->lang['global']=$this->ArrayMergeRecursive($this->lang['global'],$lang);

		//set Home URL per clients
		$this->conf['default_home'] = 'home';
		if(is_array($this->conf['host_homes'])){
			foreach($this->conf['host_homes'] as $host => $page){
				if($host==$_SERVER['REMOTE_ADDR'] or gethostbyname($host)==$_SERVER['REMOTE_ADDR'] ){
					$this->conf['urls']['home'] = $this->conf['urls']['www'].'/'.$page;
					$this->conf['default_home'] = $page;
				}
			}
		}
		//ensure 'selector_keep' is an array
		if(!is_array($this->conf['app']['selector_keep'])){
			$this->conf['app']['selector_keep']=array();
		}

		//locale
		$locale= isset($this->conf['app']['locale']) ? $this->conf['app']['locale'] : $this->lang['global']['locale'];
		setlocale(LC_TIME,	$locale);
		setlocale(LC_MESSAGES,	$locale);		
	}

	//----------------------------------------------------------------------------------
	function Controller(){
		$url=$_SERVER['REQUEST_URI'];
		$url=preg_replace("#http(s)?://".$_SERVER['HTTP_HOST']."(/)?#",'',$url);	// really weird but sometime $_REQUEST_URI contains the FULL url !!!!
		$sub=$this->conf['app']['dir'];
		$page=preg_replace("#^{$sub}/#",'',$url);
		$page=preg_replace('#\?.*#','',$page);
		$page=preg_replace('#/$#','',$page);
		$page=str_replace('/','_',$page);
		$page or $page=$this->conf['default_home'] or $page='home';

		$this->conf['app']['page']=$page;

		$file=$this->_HandleCustomPages($page);
		$file=$this->conf['paths']['pages']."$file.php";

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
	private function _HandleCustomPages($page){
		if( is_array($this->conf['pages']) ){
			if($arr=$this->conf['pages'][$page]){
				$page=$arr['type'] or $page='home';
			}
		}		
		return $page;
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

	//-----------------------------------------------------------------------------------------------------------------------
	function CacheRead($file,$time_out=3600){
		$this->cache_file=$file;
		if(file_exists($file) and filemtime($file) > (time() - $time_out ) ){
			return file_get_contents($file);
		}
	}
	//-----------------------------------------------------------------------------------------------------------------------
	function CacheWrite($content,$file=''){
		$file or $file=$this->cache_file;
		file_put_contents($file,$content);
		chmod($file,0777);
	}

	// ------------------------------------------------------------------------------------------------------------------------
	function ArrayMergeRecursive($array1, $array2){
		$arrays = func_get_args();
		$narrays = count($arrays);
		
		for ($i = 0; $i < $narrays; $i ++) {
			if (!is_array($arrays[$i])) {
				trigger_error('Argument #' . ($i+1) . ' is not an array - trying to merge array with scalar! Returning null!', E_USER_WARNING);
				return;
			}
		}
		
		$ret = $arrays[0];
		
		for ($i = 1; $i < $narrays; $i ++) {
			foreach ($arrays[$i] as $key => $value) {
					if (is_array($value) && isset($ret[$key])) {
						$ret[$key] = call_user_func_array (array($this,__METHOD__), array($ret[$key], $value));
					}
					else {
						$ret[$key] = $value;
					}
			}    
		}
		return $ret;
	}


} 
?>
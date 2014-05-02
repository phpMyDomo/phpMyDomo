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

class PMD_Root_Action extends PMD_Root{

	var $action				='undefined';	// action type
	var $config_required	=true;			/// config file is required

	var $vars;	//action config

	var $parameters=array();

	
	//----------------------------------------------------------------------------------
	function __construct(& $class, $action=''){
		parent::__construct($class);
		if($action){
			$this->action=$action;
			$this->_require($action);
		}
	}

	//----------------------------------------------------------------------------------
	private function _require($action_file){
		$my_conf		=$this->conf['paths']['confs']."action_$action_file.php";
		if($this->config_required){
			if(file_exists($my_conf)){
				require($my_conf);
				$this->vars=$action;
			}
			else{
				$data=array('code'=>500,'message'=>"A configuration file is required at: $my_conf");
				$this->DisplayJson(false,$data);
			}
		}
	}

	//----------------------------------------------------------------------------------
	function RunAction(){
		if($this->action !='undefined'){
			if(isset($_GET['preset'])){$prefix='_'.$_GET['preset'];}
			$cache_dir=$this->conf['paths']['caches']."actions/";
			$cache_file=$cache_dir."{$this->action}{$prefix}_".md5(json_encode($_GET));
			if(!$debounce=$this->GetParam('debounce')){
				if($debounce=$this->conf['app']['actions_debounce']){
					$this->parameters['debounce']="(Main Conf): $debounce";
				}
			}
			if($debounce){
				if(!file_exists($cache_dir)){
					mkdir($cache_dir);
					chmod($cache_dir,0777);
				}
				
				if(!file_exists($cache_file)){
					touch($cache_file);
				}
				elseif( filemtime($cache_file) < ( time() - $debounce) ){
					touch($cache_file);
				}
				else{
					$this->DisplayJson(false,array('code'=>403, 'message'=>"The Debounce time ($debounce sec) is greater than the elapsed time since the same action",'sent_parameters'=>$_GET));					
				}
			}
		}
		$this->Run();
	}


	//----------------------------------------------------------------------------------
	function Run(){
		echo "Extend this method to run the action";
	}

	//----------------------------------------------------------------------------------
	//return a cleaned input 
	function GetInput($name,$type='raw'){
		if(isset($_GET[$name])){
			$input=urldecode($_GET[$name]);
			if($type=='int'){
				return intval($input);
			}
			elseif($type=='float'){
				return (float) $input;
			}
			elseif($type=='bool'){
				return (bool) $input;
			}
			elseif($type=='str'){
				return preg_replace('#[^a-z0-9;,:/?=+%\s\.@&_-]#i','',$input);
			}
			else{
				return $input;
			}
		}
	}

	//----------------------------------------------------------------------------------
	//return value from either $vars.presets.preset, either from $vars.globals , or from $_GET 
	function GetParam($name,$type='raw'){
		$preset	='';
		$value	='';
		$from	='';
		if(isset($_GET['preset'])){
			$preset=$_GET['preset'];
		}
		$this->parameters['preset']=$preset;
		
		if($this->GetInput($name,$type)){
			$value = $this->GetInput($name,$type);
			$from='(Url)	';
		}
		elseif(isset($this->vars['presets'][$preset][$name])){
			$value = $this->vars['presets'][$preset][$name];
			$from="(Preset)	";
		}
		elseif(isset($this->vars['globals'][$name])){
			$value = $this->vars['globals'][$name];			
			$from='(Globals)';
		}
		if($name){
			$from and $from="$from	: ";
			$this->parameters[$name]=$from.$value;
		}
		return $value;
	}


	//----------------------------------------------------------------------------------
	function DisplayJson($status=true,$data='', $and_exit=true){
		$out=array();
		if($status){
			$out['result']='OK';
		}
		else{
			$out['result']='ERROR';			
		}
		if(is_array($data)){
			$out['data']=$data;
		}
		$out['inputs']=$this->parameters;
		if(isset($_GET['debug'])){
			$this->Debug("Action Result",$out);
		}
		else{
			echo json_encode($out);
		}
		if($and_exit){
			exit;
		}		
	}
}
?>
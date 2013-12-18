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
	function Run(){
		echo "Extend this method to run the action";
	}

	//----------------------------------------------------------------------------------
	//return a cleaned input 
	function GetInput($name,$type='raw'){
		if(isset($_GET[$name])){
			$input=urldecode($_GET[$name]);
			if($type=='int'){
				return intval(input);
			}
			elseif($type=='str'){
				return preg_replace('#[a-z0-9;,\s\.@&_-]#','',$input);
			}
			elseif($type=='raw'){
				return $input;
			}
		}
	}

	//----------------------------------------------------------------------------------
	//return value from either $vars.presets.preset, either from $vars.globals , or from $_GET 
	function GetParam($name,$type='raw'){
		$preset='';
		if(isset($_GET['preset'])){
			$preset=$_GET['preset'];
		}
		if($this->GetInput($name,$type)){
			return $this->GetInput($name,$type);
		}
		elseif(isset($this->vars['presets'][$preset][$name])){
			return $this->vars['presets'][$preset][$name];
		}
		elseif(isset($this->vars['globals'][$name])){
			return $this->vars['globals'][$name];			
		}
	}


	//----------------------------------------------------------------------------------
	function DisplayJson($status=true,$data='', $and_exit=true){
		$out=array();
		if($status){
			$out['result']='ok';
		}
		else{
			$out['result']='err';			
		}
		if(is_array($data)){
			$out['data']=$data;
		}
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
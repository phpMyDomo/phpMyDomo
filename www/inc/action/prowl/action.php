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

class PMD_Action extends PMD_Root_Action{

	var $timeout=5;
	
	//----------------------------------------------------------------------------------
	function Run(){

		$key			=$this->GetParam('key'		,'str');
		$title			=$this->GetParam('title'	,'str');
		$message		=$this->GetParam('message'	,'int');
		$priority		=$this->GetParam('priority'	,'str');
		$url			=$this->GetParam('url'		,'str');
		$custom			=$this->GetParam('custom'	,'raw');

		$message		=str_replace('{custom}',$custom, $message);
		if(!strlen($priority)){$priority==0;}

		$result='';
		if($key and $title and $message){
			require_once(dirname(__FILE__).'/class.php-prowl.php');
			$prowl = new Prowl();
			$prowl->setApiKey($key);
			//$prowl->setDebug(true);
			$result=$prowl->add($this->conf['app']['name'],$title,$priority,$message,$url);
			if($result->success['code']==200){
				$this->DisplayJson(true, array('code'=>200, 'message'=>"Successfully Sent Notification : $title",'api_result'=>$result));
			}
			else{
				$this->DisplayJson(false, array('code'=>500, 'message'=>"Failed to send Notification : $title",'api_result'=>$result));				
			}
		}
		else{
			$this->DisplayJson(false, array('code'=>500, 'message'=>"Missing some parameters!"));
		}
	}
}
?>
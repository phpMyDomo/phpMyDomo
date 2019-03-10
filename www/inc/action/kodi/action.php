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
//namesapce for json rpc2 library
use JsonRPC\Client;

class PMD_Action extends PMD_Root_Action{
	
	//----------------------------------------------------------------------------------
	function Run(){

		$mode			=$this->GetParam('mode'			,'str');
		$server			=$this->GetParam('server'		,'str');
		$title			=$this->GetParam('title'		,'str');
		$message		=$this->GetParam('message'		,'raw');
		$icon			=$this->GetParam('icon'			,'str');
		$time			=$this->GetParam('time'	,		'float');
		$custom			=$this->GetParam('custom'		,'raw');
		$message		=str_replace('{custom}',$custom, $message);
		$timeout		=$this->GetParam('timeout'		,'int') or $timeout=1;

		if($server){
			$server_url="http://$server/jsonrpc";
			require_once($this->conf['libs']['jsonrpc_client']);
			$o_jsonrpc = new Client($server_url,$timeout);
			//$o_jsonrpc = new Client($server_url,5,true); //debug
		}
		else{
			$this->DisplayJson(false, array('code'=>500, 'message'=>"Server is not set!"));			
		}
		$result='';
		$p=array();
		$missing_parameters=true;

		if($mode=='notify'){
			if($title and $message ){
				$missing_parameters=false;
				$method="GUI.ShowNotification";
				$p['title']		=$title;
				$p['message']	=$message;
				$icon	and  $p['image']		=$icon;
				$time	and  $p['displaytime']	=round($time *1000);
			}
		}
		elseif($mode=='pause'){
			$missing_parameters=false;
			$method="Player.PlayPause";
			$p['playerid']=1;
		}
		else{
			$this->DisplayJson(false, array('code'=>404, 'message'=>"Invalid mode : $mode"));
		}

		if($missing_parameters){
			$this->DisplayJson(false, array('code'=>500, 'message'=>"Missing some parameters!"));
		}
		
		// legacy warnnig
		if( $this->GetParam('image','str') ){
			$p['title']	='[PMD Warning] '. $p['title'];
			$p['message']	="Use of the 'image' parameter is deprecated. Use 'icon' from now on! ".$p['message'];
			$p['image']='error';
			$p['displaytime']=15*1000; 
		}
		
		$result=$o_jsonrpc->execute($method,$p);
		$data=array("sent_url"=>$server_url ,"sent_method"=>$method,"sent_parameters"=>$p, "api_result"=>$result);
		if($result=='OK'){
			$data['code']	=200; 
			$data['message']="Sucessfully sent mode: $mode";
			$this->DisplayJson(true, $data);	
		}
		else{
			$data['code']	=500;
			$data['message']="Request failed!";
			$this->DisplayJson(false, $data);
		}
	}
}
?>
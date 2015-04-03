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

	var $server;
	var $timeout=1;
	var $mac;
	var $login;
	var $pass;
	var $token;
	var $api_result=array();
	
	//----------------------------------------------------------------------------------
	function Run(){

		$server			=$this->GetParam('server'	,'str');
		$mac			=$this->GetParam('mac'		,'raw');
		$timeout		=$this->GetParam('timeout'	,'int');

		$login			=$this->GetParam('login'	,'str');
		$pass			=$this->GetParam('pass'		,'raw');
		$token			=$this->GetParam('token'	,'raw');

		$mode			=$this->GetParam('mode'		,'str');

		$left			=$this->GetParam('left',	'int');
		$right			=$this->GetParam('right',	'int');
		$text			=urldecode($this->GetParam('text','raw'));

		$custom			=$this->GetParam('custom'	,'raw');
		$text			=str_replace('{custom}',$custom, $text);

		$this->server	=$server;
		$this->mac		=$mac;
		$this->login	=$login;
		$this->pass		=$pass;
		$this->token	=$token;
		$timeout and $this->timeout	=$timeout;

		if($server and $mac ){
			if($mode=="ears" and $token and $mac ){
				$complete=1;
			}
			elseif($login and $pass and $mac){
				$complete=1;
			}
		}
		if($complete and $mode ){
			if($mode=='tts' and $text){
				$ok= $this->ApiSend($mode, $text);
			}
			elseif($mode=='ears'){
				$ok= $this->ApiSend($mode,$left,$right);				
			}
			
			if($ok){
				$this->DisplayJson(true, array('code'=>200, 'message'=>"Successfully excecuted mode : $mode",'api_result'=>$this->api_result));
			}
			else{
				$this->DisplayJson(false, array('code'=>500, 'message'=>"Failed to execute mode : $mode",'api_result'=>$this->api_result));				
			}
		}
		$this->DisplayJson(false, array('code'=>500, 'message'=>"Missing some parameters!"));
	}

	//----------------------------------------------------------------------------------
	private function ApiValidateResult($mess){
		if(preg_match('#<message>(EARPOSITIONNOTSENT|ERROR)#i',$mess)){
			return false;
		}
		return true;
	}

	//----------------------------------------------------------------------------------
	private function ApiSend($mode, $data1="",$data2=""){
		$auth_url="http://{$this->server}/ojn_api/accounts/auth?login={$this->login}&pass={$this->pass}";
		
		$urls['tts']	="http://{$this->server}/ojn_api/bunny/{$this->mac}/tts/say?text=".urlencode($data1);
		$violet_urls['ears']	="http://{$this->server}/ojn/FR/api.jsp?posleft={$data1}&posright={$data2}&sn={$this->mac}&token={$this->token}";

		$options = array(
			'http' => array(
				'timeout' => $this->timeout
			),
		  );
		$context  = stream_context_create($options);

		if($url=$violet_urls[$mode]){
			$this->api_result['url']=$url;
			if($xml=file_get_contents($url,false,$context)){
				$this->api_result['mess']=htmlentities($xml);
				return $this->ApiValidateResult($xml);
			}
		}
		elseif($url=$urls[$mode] and $result=file_get_contents($auth_url,false,$context)){
			$token=trim(strip_tags($result));
			$this->api_result['token']=$token;
			//$this->api_result['url_auth']=$auth_url;
			
			$url .="&token=".$token;
			$this->api_result['url']=$url;
			if($xml=file_get_contents($url,false,$context)){
				$this->api_result['mess']=htmlentities($xml);
				return $this->ApiValidateResult($xml);
			}
		}
	}
}
?>
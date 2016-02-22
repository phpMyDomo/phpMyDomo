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

	var $timeout=3;
	var $server;
	var $mac;
	var $token;
	var $api_result=array();
	
	//----------------------------------------------------------------------------------
	function Run(){

		$server			=$this->GetParam('server'	,'str');
		$mac			=$this->GetParam('mac'		,'raw');
		$token			=$this->GetParam('token'	,'raw');
		$timeout		=$this->GetParam('timeout'	,'int');

		$mode			=$this->GetParam('mode'		,'str');

		$left			=$this->GetParam('left',	'int');
		$right			=$this->GetParam('right',	'int');

		$text			=urldecode($this->GetParam('text','raw'));
		$voice			=$this->GetParam('voice',	'raw');
		$voice			or $voice="Claire";

		$custom			=$this->GetParam('custom'	,'raw');
		$text			=str_replace('{custom}',$custom, $text);

		$this->server	=$server;
		$this->mac		=$mac;
		$this->token	=$token;

		$timeout and $this->timeout	=$timeout;

		if($server and $mac and $token){
				$complete=1;
		}
		if($complete and $mode ){
			if($mode=='tts' and $text){
				$ok= $this->ApiSend($mode, $text,$voice);
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

		$violet_urls['ears']	="http://{$this->server}/ojn/FR/api.jsp?sn={$this->mac}&token={$this->token}&posleft={$data1}&posright={$data2}";
		$violet_urls['tts']		="http://{$this->server}/ojn/FR/api.jsp?sn={$this->mac}&token={$this->token}&voice={$data2}&tts=".urlencode($data1);

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
	}
}
?>
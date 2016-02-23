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

	API Reference : 
	http://openjabnab.fr/ojn_admin/api.php
	http://wiki.openjabnab.fr/commandes_api
*/

class PMD_Action extends PMD_Root_Action{

	var $timeout=3;
	var $server;
	var $mac;
	var $token;
	var $api_result=array();
	var $must_sleep=0;

	var $actions_names=array(
		13	=>	array('Wake Up'),
		14	=>	array('Go To Sleep'),
		17	=>	array('Reboot'),
		18	=>	array('Soft Reboot'),
		7	=>	array('Sleep Status','rabbitSleep'),
		//8	=>	array('rabbitVersion',	''		,'Bunny Version','rabbitVersion'),
		//10	=>	array('rabbitName',		''		,'Bunny Name','rabbitName'),

		//9	=>	array('Supported voices'),
		//15	=>	array('Bunny connected'),
		//16	=>	array('Last Online'),
		//19	=>	array('List of records')
	);


	
	//----------------------------------------------------------------------------------
	function Run(){

		$server			=$this->GetParam('server'	,'str');
		$mac			=$this->GetParam('mac'		,'str');
		$token			=$this->GetParam('token'	,'str');
		$timeout		=$this->GetParam('timeout'	,'int');

		$mode			=$this->GetParam('mode'		,'str');

		$left			=$this->GetParam('left',	'int');
		$right			=$this->GetParam('right',	'int');

		$text			=urldecode($this->GetParam('text','raw'));
		$voice			=$this->GetParam('voice',	'str');
		//$voice			or $voice="Claire";

		$mp3			=$this->GetParam('mp3','raw');

		$action			=$this->GetParam('action'	,'int');
		$force			=$this->GetParam('force'	,'int');

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
				$this->forceCommand($force);
				$ok= $this->ApiSend($mode, $text,$voice,'TTSSENT');
				$this->forceCommand($force,0);
			}
			elseif($mode=='ears'){
				$this->forceCommand($force);
				$ok= $this->ApiSend($mode,$left,$right,'EARPOSITIONSENT');				
				$this->forceCommand($force,0);
			}
			elseif($mode=='stream' and $mp3){
				$this->forceCommand($force);
				$ok= $this->ApiSend($mode,$mp3,'WEBRADIOSENT');				
				$this->forceCommand($force,0);
			}
			elseif($mode=='wake'){
				$mode='action';
				$action=13;
			}
			elseif($mode=='sleep'){
				$mode='action';
				$action=14;
			}
			elseif($mode=='reboot'){
				$mode='action';
				$action=17;
			}
			elseif($mode=='softreboot'){
				$mode='action';
				$action=18;
			}
			elseif($mode=='sleeping'){
				$mode='get';
				$action=7;
			}

			if($mode=='action' and $action){
				$ok= $this->ApiSend($mode,$action,'','COMMANDSENT');				
			}
			elseif($mode=='get' and $action){
				$ok= $this->ApiSend($mode,$action,'','',$this->actions_names[$action][1]);				
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
	private function ApiSend($mode, $data1="",$data2="",$expected_message="",$expected_param=''){

		$violet_urls['ears']	="http://{$this->server}/ojn/FR/api.jsp?sn={$this->mac}&token={$this->token}&posleft={$data1}&posright={$data2}";
		$violet_urls['tts']		="http://{$this->server}/ojn/FR/api.jsp?sn={$this->mac}&token={$this->token}&voice={$data2}&tts=".urlencode($data1);
		
		$violet_urls['action']	="http://{$this->server}/ojn/FR/api.jsp?sn={$this->mac}&token={$this->token}&action={$data1}";
		$violet_urls['get']		="http://{$this->server}/ojn/FR/api.jsp?sn={$this->mac}&token={$this->token}&action={$data1}";
		$violet_urls['stream']	="http://{$this->server}/ojn/FR/api_stream.jsp?sn={$this->mac}&token={$this->token}&urlList={$data1}";

		$options = array(
			'http' => array(
				'timeout' => $this->timeout
			),
		  );
		$context  = stream_context_create($options);
		$this->api_result=array();

		if($url=$violet_urls[$mode]){
			$this->api_result['url']=$url;
			if($raw=file_get_contents($url,false,$context)){
				$this->api_result['raw']=htmlentities($raw);
				
				$xml=(array) @simplexml_load_string($raw);
				$this->api_result['xml']=$xml;

				if($expected_message){
					$this->api_result['meaning']=$this->actions_names[$data1][0];
					$this->api_result['received']=$xml['message'];
					if($this->api_result['received'] == $expected_message){
						return true;
					}
				}
				elseif($expected_param){
					$this->api_result['meaning']=$this->actions_names[$data1][0];
					$this->api_result['received']=$xml[$expected_param];
					
					if($this->api_result['received'] == 'YES' or $this->api_result['received'] == 'NO'){
						return true;
					}
					else{
						return false;
					}
				}
				
				return $this->ApiValidateResult($raw);
			}
		}
	}
	//----------------------------------------------------------------------------------
	private function forceCommand($force, $is_start=1){		
		if($force){
			if($is_start){
				if($this->ApiSend('get','7','','' ,$this->actions_names[7][1] )){ //status

					if($this->api_result['received']=='YES'){
						if($this->ApiSend('action','13','','COMMANDSENT')){ //wake
							$this->must_sleep=1;
							sleep(1);
							return true;
						}
						else{
							$this->DisplayJson(false, array('code'=>500, 'message'=>"Failed to wake bunny !",'api_result'=>$this->api_result));				
						}	
					}
				}
				else{
					$this->DisplayJson(false, array('code'=>500, 'message'=>"Can't get bunny sleeping status !",'api_result'=>$this->api_result));				
				}
			}
			else{
				if(	$this->must_sleep){
					$this->must_sleep=0;
					sleep(1);
					if($this->ApiSend('action','14','','COMMANDSENT')){ //sleep
						return true;
					}
					else{
						$this->DisplayJson(false, array('code'=>500, 'message'=>"Failed to wake bunny !",'api_result'=>$this->api_result));				
					}
				}
			}
		}
	}

}
?>
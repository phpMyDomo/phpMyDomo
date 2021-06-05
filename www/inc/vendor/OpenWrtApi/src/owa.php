<?php 

/*
	OpenWrtApi : Home Automation Web Interface
	http://www.github.com/soif/OpenWrtApi
	
    ----------------------------------------------
	Copyright (C) 2020  Francois Dechery

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



class OpenWrtApi{

	var $version='1.1.1';

	private $timeout	=5;
	private $debug		=false;
	private $url	;

	private $curl_error_code=0;
	private $curl_error_text='';
	private $ubus_error_code=0;
	private $ubus_errors=array(
		'0'		=>'UBUS_STATUS_OK: (No Error)',
		'1'		=>'UBUS_STATUS_INVALID_COMMAND	: ',
		'2'		=>'UBUS_STATUS_INVALID_ARGUMENT	: ',
		'3'		=>'UBUS_STATUS_METHOD_NOT_FOUND	: ',
		'4'		=>'UBUS_STATUS_NOT_FOUND	: ',
		'5'		=>'UBUS_STATUS_NO_DATA	: ',
		'6'		=>'UBUS_STATUS_PERMISSION_DENIED	: ',
		'7'		=>'UBUS_STATUS_TIMEOUT	: ',
		'8'		=>'UBUS_STATUS_NOT_SUPPORTED	: ',
		'9'		=>'UBUS_STATUS_UNKNOWN_ERROR	: ',
		'10'	=>'UBUS_STATUS_CONNECTION_FAILED	: '
	);

	private $ubus_session='';
 
	private $curl_headers = array(
        'Connection: close',
        'Content-Type: application/json',
        'Accept: application/json'
    );

	private $rpc_multi		=array();
	private $rpc_multi_id	=0;

	//---------------------------------------------------------------
	function __construct($url, $debug=false){
		$this->url=$url."/cgi-bin/luci/admin/ubus";
		$this->debug	=$debug;
	}

	//---------------------------------------------------------------
	public function GetErrors(){
		$errors['curl']['code']=$this->curl_error_code;
		$errors['curl']['text']=$this->curl_error_text;
		$errors['ubus']['code']=$this->ubus_error_code;
		$errors['ubus']['text']=$this->ubus_errors[$this->ubus_error_code];
		return $errors;
	}

	//---------------------------------------------------------------
	public function UbusLogin($user,$pass){
		if($result=$this->UbusCall('session','login',array("username" => $user, "password" => $pass ))){
			$this->ubus_session=$result['ubus_rpc_session'];
			return $result;
		}
	}

	//---------------------------------------------------------------
	public function SetSessionId($session_id){
		$this->ubus_session=$session_id;
	}

	//---------------------------------------------------------------
	public function GetSessionId(){
		return $this->ubus_session;
	}

	//---------------------------------------------------------------
	public function UbusCall($path, $method, array $args = array()){
		if($path=='session' and $method=='login'){
			$session_id="00000000000000000000000000000000";
		}
		else{
			$session_id=$this->ubus_session;
		}

		if(!$session_id){
			return FALSE;
		}

		$params=array(
			$session_id,
			$path,
			$method,
			$args
		);

		$result = $this->_jsonRPC('call',$params);
		if($this->debug){
			echo "* JsonRPC CALL Result:	";
			print_r($result);	
		}
		if($result[0]){
			$this->ubus_error_code=$result[0];
			return FALSE;
		}
		else{
			return $result[1];
		}
	}

	//---------------------------------------------------------------
	public function UbusList(array $args = array()){
		$params=$args;
		$result = $this->_jsonRPC('list',$params);
		if($this->debug){
			echo "* JsonRPC LIST Result:	";
			print_r($result);	
		}
		return $result;
	}

	//---------------------------------------------------------------
	public function UbusListStations($interfaces_names){
		if(is_array($interfaces_names)){
			foreach($interfaces_names as $if){
				$this->_MultiAdd('iwinfo','assoclist',array('device'=>$if));
			}
			$answer=$this->_MultiCall();
			if(is_array($answer)){
				foreach($answer as $k => $a){
					$out[$interfaces_names[$k]]=$a['results'];
				}
				return $out;
			}
		}
	}

	//---------------------------------------------------------------
	private function _MultiAdd($path, $method, array $args = array()){
		if($session_id=$this->ubus_session){
			$params=array(
				$session_id,
				$path,
				$method,
				$args
			);
			$this->rpc_multi[]=$this->_FormatJsonRPC('call',$params,$this->rpc_multi_id);
			$this->rpc_multi_id++;
			return $this->rpc_multi_id;
		}
	}

	//---------------------------------------------------------------
	private function _MultiCall(){
		if(is_array($this->rpc_multi) and count($this->rpc_multi)){
			$answer = $this->_CurlRequest($this->rpc_multi);
			$this->rpc_multi=array();
			$this->rpc_multi_id=0;
			if(is_array($answer)){
				foreach($answer as $rpc){
					$rpc['result'] and $out[$rpc['id']]=$rpc['result'][1];
				}
				return $out;
			}
		}
	}

	//---------------------------------------------------------------
	private function _FormatJsonRPC($method, array $params = array(), $id='' ){
		if($id ===''){
			$id = mt_rand();
		}

		$payload = array(
			'jsonrpc'	=> '2.0',
			'method'	=> $method,
			'id' 		=> $id
		);

		if (! empty($params)) {
			$payload['params'] = $params;
		}
		return $payload;
	}

	//---------------------------------------------------------------
	private function _jsonRPC($method, array $params = array() ){
		$payload=$this->_FormatJsonRPC($method,$params);
		$answer = $this->_CurlRequest($payload);

		if (isset($answer['id']) && $answer['id'] == $payload['id'] && array_key_exists('result', $answer)) {

			return $answer['result'];
		}
		else if ($this->debug && isset($answer['error'])) {
			echo "* JsonRPC answer Error:	";
			print_r($answer['error']);
		}
	}


	//---------------------------------------------------------------
	private function _CurlRequest($payload){
		$url=$this->url ."?".mt_rand();
		if ($this->debug){
			echo "* Curl URL:	{$url}\n";
			echo "* Curl Request:	";
			print_r($payload);
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
		curl_setopt($ch, CURLOPT_USERAGENT, 'JSON-RPC PHP Client');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->curl_headers);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

		$answer = curl_exec($ch);
		$response = json_decode($answer, true);

		$this->curl_error_code=curl_errno($ch);
		$this->curl_error_text=curl_error($ch);

		curl_close($ch);

		return is_array($response) ? $response : array();
	}

}

?>
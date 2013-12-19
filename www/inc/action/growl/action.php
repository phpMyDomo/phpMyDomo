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

	private $timeout	=3;
	private $sticky		=false;
	private $priority	='normal';
	private $o_growl;
	private $log=array();
	private $do_register=true;

	//----------------------------------------------------------------------------------
	function Run(){
		require_once($this->conf['libs']['growl_atoload']);
		//set_include_path(get_include_path() . PATH_SEPARATOR . $this->conf['paths']['vendors'].'PEAR_Net_Growl');
		//echo get_include_path();
		$hosts			=$this->GetParam('hosts'		,'raw');
		$protocol		=$this->GetParam('protocol'		,'str');
		$title			=$this->GetParam('title'		,'str');
		
		$message		=$this->GetParam('message'		,'str');
		$pass			=$this->GetParam('pass'			,'raw');
		$icon			=$this->GetParam('icon'			,'str');
		$priority		=$this->GetParam('priority'		,'str');
		$sticky			=$this->GetParam('sticky'		,'bool');
		$custom			=$this->GetParam('custom'		,'raw');
		$message		=str_replace('{custom}',$custom, $message);

		//$this->do_register=$this->GetParam('register'	,'bool');

		$result='';
		if($hosts and $protocol and $title and $message){
			$hosts_arr=explode(',',$hosts);
			foreach($hosts_arr as $host){
				$host=trim($host);
				if($protocol=='both'){
					if($this->_Register($host,'udp',$pass)){
						$this->_Publish($title,$message,$icon,$priority,$sticky);
					}
					elseif($this->_Register($host,'gntp',$pass)){
						$this->_Publish($title,$message,$icon,$priority,$sticky);
					}
				}
				else{
					if($this->_Register($host,$protocol,$pass)){
						$this->_Publish($title,$message,$icon,$priority,$sticky);
					}
				}
			}
			
			// make answer ------------------------------------------
			$out=false;
			$count_publish	=count($this->log['ok_publish']);
			$count_hosts	=count($hosts_arr);
			
			if($count_publish==0){
				$data=array('code'=>404, 'message'=> 'Impossible to reach any host!');
				$out=false;
			}
			else{
				if( $count_publish == $count_hosts){
					$data=array('code'=>200, 'message'=> "Notified ALL $count_hosts hosts.");
					$out=true;
				}
				else{
					$data=array('code'=>201, 'message'=> "Notified ONLY $count_publish from $count_hosts total hosts.");
					$out=true;
				}			
			}
			//make logs ----------
			if(isset($this->log['ok_register'])){
				$logs=array();
				foreach($this->log['ok_register'] as $k => $l){
					$logs[]=$l;
					$this->log['ok_publish'][$k] and $logs[]=$this->log['ok_publish'][$k];
				}
				$data['logs_ok']=$logs;
			}
			if(isset($this->log['err_register'])){
				$logs=array();
				foreach($this->log['err_register'] as $k => $l){
					$logs[]=$l;
					$this->log['err_publish'][$k] and $logs[]=$this->log['err_publish'][$k];
				}
				$data['logs_errors']=$logs;
			}
			$this->DisplayJson($out,$data);
		}
		else{
			$this->DisplayJson(false, array('code'=>500, 'message'=>"Missing some parameters!"));
		}
	}
	
	//----------------------------------------------------------------------------------
	private function _Register($host,$protocol='udp',$password=''){
		$icon=$this->conf['urls']['static'].'/global/img/app_icon128.png';
		$notifications = array(
		    'Notification' => array(
		        'display'	=> 'Notification',
			//	'icon'	=> $icon
				)
		);
		$appName  = $this->conf['app']['name'];
		$options  = array(
			'AppIcon'	=> $icon,
			'debug'		=> '/tmp/netgrowl.log',
		    'host'		=> $host,
		    'protocol'	=> $protocol,
		    'timeout'	=> $this->timeout,
		);
		try {
		    $this->o_growl = Net_Growl::singleton($appName, $notifications, $password, $options);
			if($this->do_register){
		    	$this->o_growl->register();
				$this->log['ok_register'][]="Registered $protocol $host";
			}
			else{
				$this->log['ok_register'][]="Instancied $protocol $host";
			}
			return true;
		} 
		catch (Net_Growl_Exception $e) {
		    $this->o_growl->reset();
			$this->log['err_register'][]="Register $protocol $host : ".$e->getMessage() ;
		}
	}

	//----------------------------------------------------------------------------------
	private function _Publish($title,$description,$icon='',$priority='normal',$sticky=false){
		$sticky		or 	$sticky=$this->sticky;
		$priority	or	$priority=$this->priority;
		$icon		or $icon=$this->conf['urls']['static'].'/global/img/app_icon128.png';
		$priority 	="PRIORITY_".strtoupper($priority);
		$type 		= 'Notification';
		$options     = array(
			'priority'	=> constant('Net_Growl::'.$priority),
			'sticky'	=> $sticky,
			'icon'		=> $icon
		);
		
		try {
		    $this->o_growl->publish($type, $title, $description, $options);
		    $this->o_growl->reset();
			$this->log['ok_publish'][]="-Published : \"$title\"";
			return true;
		} 
		catch (Net_Growl_Exception $e) {
		    $this->o_growl->reset();
			$this->log['publish'][]="-Publish : ".$e->getMessage() ;
		}
	}


	
}
?>
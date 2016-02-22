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
	
	//----------------------------------------------------------------------------------
	function Run(){
		require_once($this->conf['libs']['mysensors']);

		$p['msg']		=$this->GetParam('msg'		,'str');

		$p['node']		=$this->GetParam('node'			,'int');
		$p['child']		=$this->GetParam('child'		,'int');
		$p['type']		=$this->GetParam('type'			,'str');
		$p['sub']		=$this->GetParam('sub'			,'str');
		$p['payload']	=$this->GetParam('payload'		,'str');
		//$p['get']		=$this->GetParam('get'			,'bool');

		$p['gw_type']		=$this->GetParam('gw_type'		,'str');
		$p['eth_ip']		=$this->GetParam('eth_ip'		,'str');
		$p['eth_port']		=$this->GetParam('eth_port'		,'str');
		$p['serial_port']	=$this->GetParam('serial_port'	,'str');


		if($p['gw_type']=='serial'){
			$o =new MySensorSendSerial($p['serial_port']);			
		}
		else{
			$o =new MySensorSendEthernet($p['eth_ip'],$p['eth_port']);
		}
		
		if($p['msg']){
			list($p['node'], $p['child'], $p['type'], $p['ack'], $p['sub'] ,$p['payload'] ,$p['get'])=explode(';',trim($p['msg']));
		}
		
		$to="{$p['node']}:{$p['child']}";
		if($p['node'] !='' and $p['child'] !='' and $p['type'] !='' and $p['sub'] !=''){
			$r= $o->SendMessage($p['node'],$p['child'],$p['type'],$p['ack'],$p['sub'],$p['payload'],$p['get']);
			if($r){
				$this->DisplayJson(true, array('code'=>200, 'message'=>"sucessfully sent to $to",'sent_parameters'=>$p));
				exit();
			}
			else{
				$this->DisplayJson(false, array('code'=>500, 'message'=>"Error while sending message to $to",'sent_parameters'=>$p));				
			}
		}
		else{
			$this->DisplayJson(false, array('code'=>500, 'message'=>'Missing some parameters!','sent_parameters'=>$p));			
		}
	}
}
?>
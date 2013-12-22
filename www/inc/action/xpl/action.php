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

	private $xpl_manufacturer	='pmd';				//xPL Org assigned
	private $xpl_device			='phpmydom';		//max 8char
	private $xpl_instance_id	='main';			//max 16char

	private $xpl_port			=3865;				// xPL UDP assigned port
	private $xpl_broadcast		='255.255.255.255';	// Broadcast address
	private $xpl_listen			='ANY_LOCAL';		// ListenOnAddress from xPL network settings.

	private $xpl_message		='';
	

	//----------------------------------------------------------------------------------
	function Run(){

		$xtype					=$this->GetParam('xtype'			,'str');
		$xtarget				=$this->GetParam('xtarget'			,'str');
		$xschema				=$this->GetParam('xschema'			,'str');
		$xbody					=$this->GetParam('xbody'			,'str');
		$xsource				=$this->GetParam('xsource'			,'str');
		$this->xpl_instance_id	=$xsource;

		$custom					=$this->GetParam('custom'			,'raw');
		$xbody					=trim(str_replace('{custom}',$custom, $xbody));

		$result='';
		if($xtype and $xtarget  and $xschema  and $xbody  and $xsource){
			if($this->_SendXplMessage($xtype, $xtarget, $xschema, $xbody)){
				$this->DisplayJson(true, array('code'=>200, 'message'=>"Successfully send xPl message to : $xtarget",'xpl_sent'=>$this->xpl_message));
			}
			else{
				$this->DisplayJson(true, array('code'=>500, 'message'=>"Failed to send xPl message to : $xtarget",'xpl_sent'=>$this->xpl_message));
			}
		}
		else{
			$this->DisplayJson(false, array('code'=>500, 'message'=>"Missing some parameters!"));
		}
	}


	// ------------------------------------------------------------------------------------------------------
	// Send an xPL message on the network
	// Based on php example by Mal Lansell , at : http://www.xplmonkey.com/php.html
	private function _SendXplMessage($xPLType, $xPLTarget, $xPLSchema, $xPLBody){

		$listenOnAddress	="ANY_LOCAL";								// ListenOnAddress from xPL network settings.
		$xPLSource 			= $this->xpl_manufacturer.'-'.$this->xpl_device.'.' .$this->xpl_instance_id;	// Identifies the source of the message

		if( !function_exists( 'socket_create' ) ){
			trigger_error( 'Sockets not enabled in this version of PHP', E_USER_ERROR );
		}

		// create low level socket
		if( !$socket = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP ) ){
			trigger_error('Error creating new socket',E_USER_ERROR);		
		}

		// Set the socket to broadcast
		if( !socket_set_option( $socket, SOL_SOCKET, SO_BROADCAST, 1 ) ){
			trigger_error( 'Unable to set socket into broadcast mode', E_USER_ERROR );
		}

		// If the listenOnAddress is not set to ANY_LOCAL, we need to bind the socket.
		if( $listenOnAddress != "ANY_LOCAL" ){
			if( !socket_bind( $socket, $this->xpl_listen, 0 ) ){
				trigger_error('Error binding socket to ListenOnAddress', E_USER_ERROR );
		  }
		}

		// Send the message ----
		$this->xpl_message =$xPLType."\n{\nhop=1\nsource=".$xPLSource."\ntarget=".$xPLTarget."\n}\n".$xPLSchema."\n{\n".$xPLBody."\n}\n";
		if(FALSE===socket_sendto($socket, $this->xpl_message, strlen($msg), 0, $this->xpl_broadcast, $this->xpl_port)){
			//trigger_error('Failed to send message', E_USER_ERROR );
			$out=false;
		}
		else{
			$out=true;
		}
		// We're done ------
		socket_close( $socket );

		return $out;
	}
}
?>
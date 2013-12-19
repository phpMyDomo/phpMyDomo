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

class PMD_Root{
	
	//global vars
	var $conf=array();
	var $lang=array();
	var $debug=false;
	
	//global objects
	var $o_kernel;
	var $o_api;
	

	//----------------------------------------------------------------------------------
	function __construct(& $kernel){
		$this->o_kernel	= & $kernel;
		$this->o_api	= & $kernel->o_api;
		
		$this->conf		= & $kernel->conf;
		$this->lang		= & $kernel->lang;
		$this->debug 	= $this->conf['debug']['show'];
	}

	//----------------------------------------------------------------------------------
	function Debug($txt='',$arr='',$exit=1){
		return 		$this->o_kernel->pageDebug($txt,$arr,$exit);
	}
} 
?>
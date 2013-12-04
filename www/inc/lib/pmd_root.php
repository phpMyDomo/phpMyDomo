<?php
/*
	phpMyDomo : Home Automation Web Interface
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
	var $o_fn;
	var $o_api;
	

	//----------------------------------------------------------------------------------
	function __construct(& $class){
		$this->o_fn		= & $class;
		$this->o_api	= & $class->o_api;
		
		$this->conf		= & $class->conf;
		$this->lang		= & $class->lang;
		$this->debug 	= $this->conf['debug']['show'];
	}

	//----------------------------------------------------------------------------------
	function Debug($txt='',$arr='',$exit=1){
		return 		$this->o_fn->pageDebug($txt,$arr,$exit);
	}
} 
?>
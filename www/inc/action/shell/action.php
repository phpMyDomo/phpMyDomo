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

		$command		=$this->GetParam('command'		,'raw');
		$custom			=$this->GetParam('mode'			,'raw');
		$command		=str_replace('{custom}',$custom, $command);

		$result='';
		if($command){
			if($result=shell_exec($command)){
				$this->DisplayJson(true, array('code'=>200, 'message'=>"Successfully executed command : $command",'api_result'=>$result));
			}
			else{
				$this->DisplayJson(false, array('code'=>500, 'message'=>"Failed to execute command : $command",'api_result'=>$result));				
			}
		}
		else{
			$this->DisplayJson(false, array('code'=>500, 'message'=>"Missing some parameters!"));
		}
	}
}
?>
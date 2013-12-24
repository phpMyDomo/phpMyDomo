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

class PMD_Root_PhotoFrame extends PMD_Root{

	var $vars;			// config

	var $use_path=1;
	
	//----------------------------------------------------------------------------------
	function __construct(& $class){
		parent::__construct($class);
		$this->_require();
		$this->Init();
	}

	//----------------------------------------------------------------------------------
	private function _require(){
		$my_conf		=$this->conf['paths']['confs'].'photoframe_'.$this->conf['app']['photoframe'].'.php';
		if(file_exists($my_conf)){
			require_once($my_conf);
			$this->vars=$photo;

			$this->vars['prefs']['speed']				*=1000; //milliseconds
			$this->vars['prefs']['transition_speed']	*=1000; //milliseconds
			
		}
		else{
			$this->o_kernel->PageError(500,"Cant find configuration file at: $my_conf");
		}
	}


	//----------------------------------------------------------------------------------
	function Init(){
		if($this->use_path){
			//add missing /
			if(!preg_match('#/$#',$this->vars['path'])){
				$this->vars['path'].='/';
			}
			if(!file_exists($this->vars['path'])){
				$this->o_kernel->PageError("500",'Cant find the current albums path ($frame["path"]) : '.$this->vars['path']);
			}
		}
	}

	//----------------------------------------------------------------------------------
	function GetPreferences(){
		return $this->vars['prefs'];
	}

	//----------------------------------------------------------------------------------
	function PathToUrl($path){
		$url=$this->conf['urls']['www'].'/'.str_replace($this->conf['paths']['www'],'',$path);
		return $url;
	}



	//----------------------------------------------------------------------------------
	function ListAlbums(){
		$this->o_kernel->PageError(404,"Dear Dev, Extend ListAlbums (returns an array of id=>photos paths)");
	}

	//----------------------------------------------------------------------------------
	function ListPhotos($album_id){
		$this->o_kernel->PageError(404,"Dear Dev, Extend ListPhotos(album_id) : returns an array of photos (image,thumb,title,url)");
	}



} 
?>
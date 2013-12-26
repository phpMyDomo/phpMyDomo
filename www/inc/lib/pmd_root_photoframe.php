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
			$this->SetPreferences();			
		}
		else{
			$this->o_kernel->PageError(500,"Cant find configuration file at: $my_conf");
		}
	}

	//----------------------------------------------------------------------------------
	function SetPreferences(){
		isset($this->vars['prefs']['speed'])				or $this->vars['prefs']['speed']				=5;		//time in seconds
		isset($this->vars['prefs']['transition'])			or $this->vars['prefs']['transition']			=3;		// 0=None, 1=Fade, 2=Slide Top, 3=Slide Right, 4=Slide Bottom, 5=Slide Left, 6=Carousel Right, 7=Carousel Left
		isset($this->vars['prefs']['transition_speed'])		or $this->vars['prefs']['transition_speed']		=0.8;	//time in seconds
		isset($this->vars['prefs']['random'])				or $this->vars['prefs']['random']				=true;	// true | false
		isset($this->vars['prefs']['performance'])			or $this->vars['prefs']['performance']			=1;	// 0=Normal, 1=Hybrid speed/quality, 2=Optimizes image quality, 3=Optimizes transition speed

		isset($this->vars['prefs']['show_thumb_nav'])		or $this->vars['prefs']['show_thumb_nav']		=true;	// true | false
		isset($this->vars['prefs']['show_slide_nav'])		or $this->vars['prefs']['show_slide_nav']		=true;	// true | false

		isset($this->vars['prefs']['show_progress_bar'])	or $this->vars['prefs']['show_progress_bar']	=false;	// true | false
		isset($this->vars['prefs']['show_control_bar'])		or $this->vars['prefs']['show_control_bar']		=true;	// true | false
		isset($this->vars['prefs']['show_play'])			or $this->vars['prefs']['show_play']			=true;	// true | false
		isset($this->vars['prefs']['show_album'])			or $this->vars['prefs']['show_date']			=true;	// true | false
		isset($this->vars['prefs']['show_caption'])			or $this->vars['prefs']['show_caption']			=true;	// true | false
		isset($this->vars['prefs']['show_date'])			or $this->vars['prefs']['show_date']			=true;	// true | false
		isset($this->vars['prefs']['show_counter'])			or $this->vars['prefs']['show_counter']			=true;	// true | false
		isset($this->vars['prefs']['show_bullets'])			or $this->vars['prefs']['show_bullets']			=false;	// true | false
		isset($this->vars['prefs']['show_albums'])			or $this->vars['prefs']['show_albums']			=false;	// true | false
		isset($this->vars['prefs']['show_thumb'])			or $this->vars['prefs']['show_thumb']			=true;	// true | false

		$this->vars['prefs']['speed']				*=1000; //milliseconds
		$this->vars['prefs']['transition_speed']	*=1000; //milliseconds

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
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

/* 
#########################################################################################
Domoticz API ############################################################################
#########################################################################################
No dimmer supported at this time
*/

class PMD_PhotoFrame extends PMD_Root_PhotoFrame{


	//----------------------------------------------------------------------------------
	function Init(){
		//add missing /
		if(!preg_match('#/$#',$this->vars['path'])){
			$this->vars['path'].='/';
		}
		if(!file_exists($this->vars['path'])){
			$this->o_kernel->PageError("500",'Cant find the current albums path ($frame["path"]) : '.$this->vars['path']);
		}
		//$this->vars['url_base']=$this->conf['urls']['www'].'/'.str_replace($this->conf['paths']['www'],'',$this->vars['path']);		
	}


	//----------------------------------------------------------------------------------
	function ListAlbums(){
		$out=array();
		foreach(glob($this->vars['path']."*",GLOB_ONLYDIR) as $path){
			$dir=basename($path);
			$id=urlencode($dir);
			$count=0;
			$first_image='';
			foreach(glob($path."/*.{jpg,jpeg,JPG,JPEG}",GLOB_BRACE) as $img_path){
				$count or $first_image=$this->PathToUrl($img_path);
				$count or $first_time	=filectime($img_path);
				$count++;
			}
			
			$out[$id]['title']	=ucwords(str_replace('_',' ',$dir));
			$out[$id]['image']	=$first_image;
			$out[$id]['thumb']	=$out[$id]['image'];
			$out[$id]['time']	=$first_time;
			$out[$id]['count']	=$count;
		}
		if(count($out)){
			asort($out);
			//$this->Debug('Dirs',$out);
			return $out;
		}
		else{
			$this->o_kernel->PageError("404",'No albums found in '.$this->vars['path']);			
		}
	}

	//----------------------------------------------------------------------------------
	function ListPhotos($album_id){
		$dir=urldecode($album_id);
		$dir_path=$this->vars['path'].$dir.'/';
		if(file_exists($dir_path)){
			$i=0;
			foreach(glob($dir_path."*.{jpg,jpeg,JPG,JPEG}",GLOB_BRACE) as $path){
				$file	= basename($path);
				$info 	= pathinfo($file);
				$out[$i]['image']	=$this->PathToUrl($path);
				$out[$i]['title']	=ucwords(str_replace('_',' ',basename($file,'.'.$info['extension'])));
				$out[$i]['time']	=filectime($path);
				$i++;
			}

			if(count($out)){
				asort($out);
				//$this->Debug('Dirs',$out);
				return $out;
			}
			else{
				$this->o_kernel->PageError("404",'No Photos found in '.$dir_path);			
			}
		}
		else{
			$this->o_kernel->PageError("404",'Cant find this album directory : '.$dir_path);			
		}
	}
	
} 
?>
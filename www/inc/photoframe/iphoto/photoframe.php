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

	private $file_cache		="db/photoframe_iphoto.json";
	private $file_xml		="AlbumData.xml";
	private $cache_duration	=14400;
	
	private $data=array();

	//----------------------------------------------------------------------------------
	function Init(){
		parent::Init();
		require_once(dirname(__FILE__).'/iphoto.parse.lib.php');
		//$this->o_iphoto=new IphotoParser();
		$this->file_cache	=$this->conf['paths']['caches'].$this->file_cache;
		$this->file_xml		=$this->vars['path'].$this->file_xml;
	}


	//----------------------------------------------------------------------------------
	private function ParseIphoto(){
		if(!count($this->data)){
			if($json_cache=$this->o_kernel->CacheRead($this->file_cache,$this->cache_duration)){
				$this->data = json_decode($json_cache,true);
			}
			else{
				$o=new IphotoParser($this->file_xml);
				$o->parse();
				$photos=$albums=$keywords=array();
				$o->getResults($photos, $albums, $keywords);
				$cache=array(
					'albums' 	=>$albums,
					'photos' 	=>$photos,
					'keywords' 	=>$keywords,
				);
				$this->o_kernel->CacheWrite(json_encode($cache));
				$this->data = $cache;
			}
		}
	}

	//----------------------------------------------------------------------------------
	function ListAlbums(){
		$this->ParseIphoto();
		//$this->Debug('iPhoto',$this->data['albums']);
		foreach($this->data['albums'] as $aid => $album){
			$count=count($album['PhotoIds']);
			if(!$count){continue;}
			$first_photo=$this->data['photos'][$album['PhotoIds'][0]];
			$out[$aid]['title']	=$album['AlbumName'];
			$out[$aid]['image']	=$this->PathToUrl($this->vars['path'].$first_photo['ImagePath']);
			$out[$aid]['thumb']	=$this->PathToUrl($this->vars['path'].$first_photo['ThumbPath']);
			$out[$aid]['count'] =$count;
			$out[$aid]['time'] 	=$first_photo['Timestamp'];
		}
		if(count($out)){
			//asort($out);
			//$this->Debug('Albums',$out);
			return $out;
		}
		else{
			$this->o_kernel->PageError("404",'No albums found in '.$this->vars['path']);			
		}
	}

	//----------------------------------------------------------------------------------
	function ListPhotos($album_id){
		$this->ParseIphoto();
		//$this->Debug('iPhoto',$this->data['photos']);
		if($photos=$this->data['albums'][$album_id]){
			$i=0;
			foreach($photos['PhotoIds'] as $id){
				$this_photo=$this->data['photos'][$id];
				$out[$i]['title']	= $this_photo['Comment'] or $out[$i]['title']= $this_photo['Caption'] ;
				$out[$i]['thumb']	= $this->PathToUrl($this->vars['path'].$this_photo['ThumbPath']);
				$out[$i]['image']	= $this->PathToUrl($this->vars['path'].$this_photo['ImagePath']);
				$out[$i]['time']	= $this_photo['Timestamp'];
				$i++;
			}
		}

		if(count($out)){
			//asort($out);
			//$this->Debug('Photos',$out);
			return $out;
		}
		else{
			$this->o_kernel->PageError("404",'No Photos found in '.$dir_path);			
		}

	}
	
} 
?>
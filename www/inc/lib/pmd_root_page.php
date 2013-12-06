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

class PMD_Root_Page extends PMD_Root{

	var $o_smarty;
	
	var $skin			="default";
	var $dir_template	="default";
	var $headers	=array();
	var $dir_skin;
	
	//prevent minifying 
	var $minify_bypass 		= false;
	var $minify_reset		= false;
	
	// smarty cache_lifetime
	var $cache_lifetime=3600;

	//----------------------------------------------------------------------------------
	function __construct(& $class){
		parent::__construct($class);
		$this->_init();
	}

	//----------------------------------------------------------------------------------
	private function _init(){

		$lang_file=$this->conf['paths']['lang'].$this->conf['app']['page'].'.php';
		if(file_exists($lang_file)){
			include($lang_file);
			$this->lang['page']=$lang;
		}
		
		$this->_initHeaders();
		$this->_initSmarty();
	}
	//----------------------------------------------------------------------------------
	private function _initSmarty(){
		require($this->conf['libs']['smarty']);
		$this->o_smarty= new Smarty();
		
		if(!defined('SMARTY_RESOURCE_CHAR_SET') ){
			define('SMARTY_RESOURCE_CHAR_SET', 'utf-8');
		}

		if($this->conf['debug']['show']){
			$this->o_smarty->compile_check		=true;
			$this->o_smarty->debugging			=true;
			$this->minify_bypass				=true;		
		}
		else{
			$this->o_smarty->compile_check		=false;
		}	

		if(isset($_GET['compil'])){
			$this->o_smarty->compile_check		=true;
			$this->minify_reset					=true;		
		}

		$this->o_smarty->cache_lifetime	= $this->cache_lifetime;
		$this->o_smarty->use_sub_dirs=TRUE;

		$this->o_smarty->config_dir		=$this->conf['paths']['smarty'].'configs/';
		$this->o_smarty->template_dir	=$this->conf['paths']['smarty'].'skins/';
		$this->o_smarty->compile_dir	=$this->conf['paths']['caches'].'smarty_comp/';
		$this->o_smarty->cache_dir		=$this->conf['paths']['caches'].'smarty_cache/';
	}

	//----------------------------------------------------------------------------------
	private function _initHeaders(){
		if(isset($this->conf['app']['skin'])){
			$this->skin=$this->conf['app']['skin'];
		}
		if(isset($_GET['skin'])){
			$_GET['skin'] and $this->skin=$_GET['skin'];
		}

		$this->dir_skin=$this->conf['urls']['static']."/skins/{$this->skin}";

		$this->SetHeader('bs/css/bootstrap.min.css','css_global');
		$this->SetHeader('bs/css/bootstrap-theme.min.css','css_global');
		$this->SetHeader('bs/css/bootstrap-slider.min.css','css_global');
		$this->SetHeader('bs/css/font-awesome.min.css','css_global');
		$this->SetHeader('css/main.css','css_global');
		$this->SetHeader('css/skin.css','css');

		$this->SetHeader('js/jquery-1.7.2.min.js','js_global');
		$this->SetHeader('js/jquery.lazyload.min.js','js_global');
		$this->SetHeader('bs/js/bootstrap.min.js','js_global');
		$this->SetHeader('bs/js/bootstrap-slider.min.js','js_global');
		$this->SetHeader('js/main.js','js_global');
		$this->SetHeader('js/skin.js','js');

	}




	//----------------------------------------------------------------------------------
	function Run(){
		echo "Root Page Running";
	}

	//----------------------------------------------------------------------------------
	function Display($page=array()){
		$page['title']				=$this->lang['page']['title'];
		$page['app_name']			=$this->conf['app']['home_name'];
		$page['api']				=$this->conf['app']['api'];
		$page['code']				=$this->conf['app']['page'];
		$page['template']			=$this->dir_template;
		$page['dirs']['www']		=$this->conf['urls']['www'];
		$page['dirs']['static']		=$this->conf['urls']['static'];
		$page['dirs']['skin']		=$this->dir_skin;
		$page['dirs']['server_admin']=$this->conf['api']['urls']['admin'];
		$page['headers']			=$this->_getHeaders();
		$page['menu_urls']			=$this->conf['menu_urls'];	
		$page['menu_head']			=$this->conf['menu_head'];	
		$page['menu_foot']			=$this->conf['menu_foot'];	
		$page['menu_icons']			=$this->conf['menu_icons'];
		$page['groups']				=$this->conf['groups'];
		$page['groups_names']		=$this->conf['groups_names'];
		$page['blocks']				=$this->conf['blocks'];
		$page['units']				=$this->conf['units'];
		$this->Assign('p',$page);
		
		//$this->Debug('Page',$page);
		$template='pages/'.$this->conf['app']['page'];
		$this->PrintDisplay($template);
		exit;
	}


	//----------------------------------------------------------------------------------
	function Assign($var,$value=''){
		if(is_array($var)){
			$this->o_smarty->assign($var);
		}
		else{
			$this->o_smarty->assign($var,$value);		
		}
	}

	//----------------------------------------------------------------------------------
	private function PrintDisplay($template='',$cache_id='',$compil_id=''){
		return $this->GetDisplay($template,$cache_id,$compil_id, TRUE);
	}


	//----------------------------------------------------------------------------------
	private function GetDisplay($template='',$cache_id='',$compil_id='',$display=false){

		$this->_assignDefaults();
		
		if($display){
			header('Content-type: text/html; charset=utf-8');
			$this->o_smarty->display(	$this->_GetAbsTemplate($template),	$this->_GetAbsCache($cache_id),	$compil_id);
		}
		else{
			return $this->o_smarty->fetch($this->_GetAbsTemplate($template),	$this->_GetAbsCache($cache_id),	$compil_id);
		}
	}

	//----------------------------------------------------------------------------------
	private function _assignDefaults(){
		$this->Assign('c',				$this->conf);
		$this->Assign('lg',				$this->lang['global']);
		$this->Assign('l',				$this->lang['page']);
		
	}

	// -----------------------------------------------------------------------------------------------------------------
	private function _getHeaders(){
		$html='';
		$minify_css='';
		$minify_js='';
		$abs_static	=str_replace("{$this->conf['urls']['www']}", '',$this->conf['urls']['static']);
		$abs_skin	=str_replace("{$this->conf['urls']['www']}", '',$this->dir_skin);

		foreach($this->headers['css_global'] as $url){
			$html .="	<link rel='stylesheet' type='text/css' href='{$this->conf['urls']['static']}/global/$url' />\n";
			$minify_css	.="$abs_static/global/$url,";
		}
		foreach($this->headers['css'] as $url){
			$html .="	<link rel='stylesheet' type='text/css' href='{$this->dir_skin}/$url' />\n";
			$minify_css	.="$abs_skin/$url,";
		}

		foreach($this->headers['js_global'] as $url){
			$html		.="	<script language='javascript' src='{$this->conf['urls']['static']}/global/$url'></script>\n";
			$minify_js	.="$abs_static/global/$url,";
		}
		foreach($this->headers['js'] as $url){
			$html .="	<script language='javascript' src='{$this->dir_skin}/$url'></script>\n";
			$minify_js	.="$abs_skin/$url,";
		}

		if($this->minify_bypass){
			$out=$html;
		}
		else{
			$minify_css	=rtrim($minify_css,',');
			$minify_js	=rtrim($minify_js,',');
			$version=$this->conf['app']['version'];
			$minify_query="&v=$version";
			if($this->minify_reset){$minify_query .="&compil=1";}
			$out  ="	<link rel='stylesheet' type='text/css' href='{$this->conf['urls']['minify']}/?f=$minify_css$minify_query' />\n";
			$out .="	<script language='javascript' src='{$this->conf['urls']['minify']}/?f=$minify_js$minify_query'></script>\n";
		}
		return $out;
	}

	// -----------------------------------------------------------------------------------------------------------------
	function SetHeader($path,$type='css'){
		$this->headers[$type][$path]=$path;
	}

	// -----------------------------------------------------------------------------------------------------------------
	private function _GetAbsTemplate($template){
		return $this->dir_template.'/'.$template.".tpl";
	}
	// -----------------------------------------------------------------------------------------------------------------
	private function _GetAbsCache($cache_id){
		return $cache_id;
	}

}
?>
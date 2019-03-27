<?php
class PMD_Page extends PMD_Root_Page{

	var $vars;			// config

	//----------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------
	function __construct(& $class){
		parent::__construct($class);
		$this->_require();
	}

	//----------------------------------------------------------------------------------
	function Run(){
		$this->LoadApiClient();

		$this->SetHeader('js/moment-with-locales.min.js','js_global');
		$this->SetHeader('js/jquery.thooClock.js',		'js_global');
		$this->SetHeader('js/jquery.countdown.js',		'js_global');
		$this->SetHeader('js/howler.js',					'js_global');
		$this->SetHeader('js/clock.js',					'js_global');
		$this->SetHeadJavascript("var pmd_clock={};");
		foreach($this->vars['analog'] as $k => $v){
			$this->SetHeadJavascript("pmd_clock.$k='$v';");
		}
		$this->SetHeadJavascript("pmd_clock_refresh_time={$this->vars['refresh_time']};");
		$this->SetHeadJavascript("pmd_clock_goback='{$_GET['back']}';");

		$data['type']		=$this->vars['type'];
		$data['opt_hour']	=$this->_SmartyTimeOptions(23);
		$data['opt_min']	=$this->_SmartyTimeOptions(59);
		$data['opt_min2']	=$this->_SmartyTimeOptions(120);
		$data['opt_sec']	=$this->_SmartyTimeOptions(59);
		$data['opt_sounds']	=$this->_ListSounds();

		$data['devices']	=$this->o_api->GetDevices();
		$data['sensors']	=$this->vars['sensors'];
				
		$this->Assign('data',				$data);
		$this->Display($page);
	}

	//----------------------------------------------------------------------------------
	private function _require(){
		$my_conf		=$this->conf['paths']['confs'].'clock.php';		
		if(file_exists($my_conf)){
			require_once($my_conf);
			$this->vars=$clock;
		}
		else{			
			$this->o_kernel->PageError(500,"Cant find configuration file at: $my_conf");
		}
	}
	//----------------------------------------------------------------------------------
	private function _SmartyTimeOptions($max){
		for($i=0; $i<=$max; $i++ ){
			$out[$i]=$i;
		}
		return $out;
	}
	//----------------------------------------------------------------------------------
	private function _ListSounds(){
		$path_files=$this->conf['paths']['static'].'global/audio/clock/';
		if(!$files=scandir($path_files)){
			$this->o_kernel->PageError(500,"Cant find the clock sound directory at : $path_files");
		}
		foreach($files as $file){
			$ext = pathinfo($file, PATHINFO_EXTENSION);
			$name=str_replace(".$ext",'',$file);
			if($ext !='mp3'){continue;}
			$out[$name]=ucwords(str_replace('_',' ',$name));
		}
		if(!$out){
			$this->o_kernel->PageError(500,"The clock sound directory ($path_files) does not contain any .mp3 file.");			
		}
		return $out;
	}


} 
?>
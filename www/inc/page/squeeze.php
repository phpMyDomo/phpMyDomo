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
		$this->SetHeader('js/squeeze.js',	'js_global');
		$this->SetHeadJavascript("var pmd_sqz_prefs={} ;");
		foreach($this->vars as $k => $v){
			if(is_numeric($v)){
				$this->SetHeadJavascript("pmd_sqz_prefs.$k=$v;");
			}
			else{
				$this->SetHeadJavascript("pmd_sqz_prefs.$k='$v';");
			}
		}

		if($_GET['do']=='ajax'){
			$this->_Ajax();
			exit;
		}

		$data['players']=$this->_RequestPlayersFull();
		$data['prefs']=$this->vars;
		$data['agent']=$this->_DetectMobileBrowser();
		
		// debug @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
		if($_GET['debugall']){
			echo "<hr><pre>\n";print_r($data);echo "\n</pre>\n\n";exit;
		}
						
		$this->Assign('data',	$data);
		$this->Display($page);
	}

	//----------------------------------------------------------------------------------
	private function _require(){
		$my_conf		=$this->conf['paths']['confs'].'squeeze.php';		
		if(file_exists($my_conf)){
			require_once($my_conf);
			$this->vars=$prefs;
		}
		else{			
			$this->o_kernel->PageError(500,"Cant find configuration file at: $my_conf");
		}
	}

	//----------------------------------------------------------------------------------
	private function _RequestPlayers(){
		$r=$this->_Request(array('',array('serverstatus',0,999)));
		$arr=$r['result']['players_loop'];
		if(is_array($arr)){
			foreach($arr as $a){
				$id=$a['playerid'];
				$out[$id]=$a;
				//echo "{$a['name']}	{$a['ip']}	{$a['playerid']}\n";
			}
		}
		else $out=array();
		return $out;
	}

	//----------------------------------------------------------------------------------
	private function _RequestPlayersFull(){
		$players=$this->_RequestPlayers();
		foreach($players as $id => $row){
			$row['status']	= $this->_RequestPlayerSlatus($row['playerid'],5);
			if($formated = $this->_FormatPlayer($row)){
				$out[$id]	= $formated;
			}
		}
		return $out;
	}

	//----------------------------------------------------------------------------------
	private function _RequestButtonAll($type,$v1,$v2){
		$players=$this->_RequestPlayers();
		$debug=1;
		foreach($players as $js_id => $row){
			$r=$this->_Request(array($row['playerid'],array($type,$v1,$v2)), 1, $debug);
		}
		return $out;
	}

	//----------------------------------------------------------------------------------
	private function _RequestButton($id,$type,$v1,$v2){
		$debug=1;
		$r=$this->_Request(array($id,array($type,$v1,$v2)), 0, $debug);
		echo "ok\n";
	}

	//----------------------------------------------------------------------------------
	private function _RequestPlayerSlatus($id,$max=1){
		//$r=$this->_Request(array($id,array('status',0,999)));
		$r=$this->_Request(array($id,array('status','-',$max,"tags:aAbcdeghiIJKlLmNoqrStTuxy")));
		$out=$r['result'];
		if(! is_array($out)){
			$out=array();
		}
		return $out;
	}


	//----------------------------------------------------------------------------------
	private function _Ajax(){
		if($_GET['act']=='but' ){
			$type=$_GET['type'];
			if($type=='pcp'){
				$ip		=$_GET['id'];
				$command=$_GET['v1'];
				if($command=='restartsqlt'){
					$p['server_url']="http://{$ip}/cgi-bin/restartsqlt.cgi";
					echo "Restarting SqueezeLite at $ip..";
					$this->_Request($p, 0);
				}				
			}
			else{ // button, playlist, time
				$id=$_GET['id'];
				$v1=$_GET['v1'];
				$v2=$_GET['v2'];
				if($v1=='undefined'){$v1='';}
				if($v2=='undefined'){$v2='';}
				if($id=="ALL"){
					$r=$this->_RequestButtonAll($type,$v1,$v2);				
				}
				else{
					$r=$this->_RequestButton($id,$type,$v1,$v2);
				}
				
			}
		}
		if($_GET['act']=='state_all' ){
			echo json_encode($this->_RequestPlayersFull());
		}
		exit;
	}


	//----------------------------------------------------------------------------------
	private function _FormatPlayer($row) {

		$out['playerid']	=$row['playerid'];
		$out['name']		=$row['name'];
		$out['f_jsid']		=strtolower(str_replace(':','',$row['playerid']));
		
		$status=$row['status'];
		$out['time']		=$status['time'];		
		$out['h_time']		=$this->_FormatSeconds($status['time']);
		
		$out['firmware']		=$row['firmware'];
		if(!$row['firmware']){
			return FALSE; // hide ghost player
		}
		$out['mode']		=$status['mode'];
		
		list($out['ip'],$trash['port'])=explode(':',$status['player_ip']);
		//$out['mac']		=strtoupper($row['playerid']);
		$out['volume']		=$status['mixer volume'];
		$out['f_repeat']	=$status['playlist repeat'];
		$out['f_shuffle']	=$status['playlist shuffle'];
		//$out['pl_mode']		=$status['playlist mode'];

		$play_icons=array(
			'play'	=>'play',
			'pause'	=>'pause',
			'stop'	=>'stop'
		);
		$out['json_icons']	=json_encode($play_icons);
		
		
		//buttons state
		if($status['mode'] =='play')	$out['f_states']['play']	=1;
		if($status['mode'] =='stop')	$out['f_states']['stop']	=1;
		if($status['mode'] =='pause')	$out['f_states']['pause']	=1;
		
		if(!$out['f_repeat'])			$out['f_states']['repeat_0']=1;
		if($out['f_repeat']==1)			$out['f_states']['repeat_1']=1;
		if($out['f_repeat']==2)			$out['f_states']['repeat_2']=1;
		
		if(!$out['f_shuffle'])			$out['f_states']['shuffle_0']=1;
		if($out['f_shuffle']==1)		$out['f_states']['shuffle_1']=1;
		if($out['f_shuffle']==2)		$out['f_states']['shuffle_2']=1;
		if($out['power'])				$out['f_states']['power']	=1;
		if($out['volume'] < 0)			$out['f_states']['mute']	=1;
		$out['volume']=abs($out['volume']); 

		//make current song & playlists ---------------------------
		if(is_array($status['playlist_loop'])){
			foreach($status['playlist_loop'] as $k => $arr){
				
				$tmp=$arr;
				$tmp=array();

				$tmp['artist']		=$this->_FormatTitle($arr['artist'],0);
				$tmp['title']		=$this->_FormatTitle($arr['title']);
				$tmp['album']		=$this->_FormatTitle($arr['album']);	//  and $arr['f_album']	= '<b>'. $arr['f_album'].'</b>'; // <i>:</i> and $arr['f_album']	= '['. $arr['f_album'].']';


				$tmp['duration']	=$arr['duration'];
				$tmp['h_duration']	=$this->_FormatSeconds($arr['duration']);

				//$tmp['time']	=$arr['time'];
				//$tmp['h_time']	=$this->_FormatSeconds($arr['time']);

				$tmp['year']	=$arr['year'];
				$tmp['year']	and $tmp['h_year']	="<u>{$tmp['year']}</u>";

				$tmp['bpm']		=$arr['bpm'];

				$tmp['url_img']	='';
				$arr['coverid']		and $tmp['url_img']=$this->vars['url_server']."/music/{$arr['coverid']}/cover.png";


				if($status['remote']){ //this is a radio
					$arr['artwork_url']	and $tmp['url_img']	=$arr['artwork_url'];
					
					//fix bad formatted radio
					$radio_name=$status['current_title'];
					similar_text($radio_name,$arr['artist'],$perc);
					if($perc >= 70){
						list($artist,$title)=explode(' - ',$arr['title']);
						if(trim($title)){
							$tmp['artist']	=$this->_FormatTitle($artist,0); //. " ($perc)";
							$tmp['title']	=$this->_FormatTitle($title);
						}
					}
				}
				
				
				if($k==0){	//only first song
					list($rate,$info)		=explode(' ',$arr['bitrate']);
					$tmp['bitrate']		=trim(preg_replace('#^(\d+).*#','$1',$rate));
					$tmp['bitrate_unit']	=trim(preg_replace('#^'.$tmp['bitrate'].'#','',$rate));
					$tmp['bitrate_info']	=trim($info);

					$tmp['filetype']		=$arr['type'];
					
					if( $search_title 	=$this->_makeSongFullTitle($arr)){

						$links['url_youtube']['title']		='YouTube';
						$links['url_youtube']['icon']		='youtube';
						$links['url_youtube']['href']		='https://www.youtube.com/results?search_query='.urlencode($search_title);

						$links['url_allmusic']['title']		='AllMusic';
						$links['url_allmusic']['icon']		='database';
						$links['url_allmusic']['href']		='http://www.allmusic.com/search/songs/'.urlencode($search_title);

						$links['url_google']['title']		='Google';
						$links['url_google']['icon']		='google';
						$links['url_google']['href']		='https://www.google.com/search?q='.urlencode($search_title);					
					}					
					$out['song'] 	= $tmp;
					$out['song']['links']=$links;
				}
				
				//store it
				$out['playlist'][$k]=$tmp;
				
			}
			
		}


		//make rew & ff
		if($status['time']){
			$out['f_rw1'] = max(floatval($status['time']) - $this->vars['scroll_time1'], 0);
			$out['f_ff1'] = min(floatval($status['time']) + $this->vars['scroll_time1'], floatval($out['song']['duration']) );
			
			$out['f_rw2'] = max(floatval($status['time']) - $this->vars['scroll_time2'], 0);
			$out['f_ff2'] = min(floatval($status['time']) + $this->vars['scroll_time2'], floatval($out['song']['duration']) );
		}

		//$row['remoteMeta']['f_duration']=$this->_FormatSeconds($row['remoteMeta']['duration']);

		//$out['raw']		=$row;
		return $out;
	}

	//----------------------------------------------------------------------------------
	private function _FormatTitle($txt,$uc=1) {
		if($txt =='-1')	{$txt='';}
		$uc and $txt=ucwords(strtolower($txt));
		$txt=str_replace('No Album','',$txt);
		$txt=trim($txt);
		return $txt;
	}
	//----------------------------------------------------------------------------------
	private function _makeSongFullTitle($metas) {
		$metas['artist'] and $full_title .="{$metas['artist']} - ";
		$full_title .="{$metas['title']}";
		return $full_title;
	}

	//----------------------------------------------------------------------------------
	private function _FormatSeconds($seconds) {
		if ($seconds <= 0) return '';

		$hours = intval($seconds/pow(60,2));
		$minutes = intval($seconds/60)%60;
		$seconds = $seconds%60;
		$out = "";
		if ($hours > 0) 	$out .= $hours . ":"; 
		if ($minutes >= 0)	$out .= str_pad($minutes,2,'0',STR_PAD_LEFT) . ":";
		if ($seconds >=  0)	$out .= str_pad($seconds,2,'0',STR_PAD_LEFT);
		//if(!$hours && !$minutes) $out .='sec';
		return trim($out);
	}


	//----------------------------------------------------------------------------------
	private function _Request($p,$return_transfer=1,$echo=0){
		if($server_url= $p['server_url']){
			//onlyrequest this url
			$params=array();
		}
		else{
			$server_url=$this->vars['url_server']."/jsonrpc.js";
			
			if(count($p[1])==3 and $p[1][2]==''){
				unset($p[1][2]);
			}
			
			$params=array(
				'id'=>1,
				'method'=>'slim.request',
				'params'=>$p
			);
			$params=json_encode($params);
			if($echo) {echo "Sending :". $params."<br><br>\n\n";}
		}

		$ch = curl_init($server_url); 
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($params))
		);       
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  'GET');
		curl_setopt($ch, CURLOPT_USERAGENT, 'phpMyDomo');

		if($return_transfer){
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		}
		else{
			if($echo) {echo "Blind mode : Ignoring answer...";}

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
			curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 10); 
			curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);			
		}
		
		$result = curl_exec($ch);
		curl_close($ch);

		if($echo) {echo "<pre>\n";print_r( json_decode($result,true))."\n</pre>\n\n";}
		if($return_transfer){
			return json_decode($result,true);
		}
	}

	//----------------------------------------------------------------------------------
	private function _ListSounds(){
			$this->o_kernel->PageError(500,"The clock sound directory ($path_files) does not contain any .mp3 file.");			
	}

	//----------------------------------------------------------------------------------
	private function _DetectMobileBrowser(){
			$useragent=$_SERVER['HTTP_USER_AGENT'];
			if(preg_match('#android#i', $useragent)){
				return 'android';
			}
			if(preg_match('#iPhone|iPad|iPod|IOS#i', $useragent)){
				return 'ios';
			}
	}


} 
?>
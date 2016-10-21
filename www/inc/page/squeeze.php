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
				$out[$id]['f_jsid']=strtolower(str_replace(':','',$a['playerid']));
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
			$row['status']	= $this->_RequestPlayerSlatus($row['playerid']);
			$out[$id]		=$this->_FormatPlayer($row);
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
	private function _RequestPlayerSlatus($id){
		//$r=$this->_Request(array($id,array('status',0,999)));
		$r=$this->_Request(array($id,array('status','-',1,"tags:aAbcdeghiJKlLNoqrStuy")));
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
				$v2=$_GET['v2'] or $v2='';
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
		$status=$row['status'];
		if(is_array($status['playlist_loop'])){
			foreach($status['playlist_loop'] as $k => $arr){
				$arr['f_duration']	=$this->_FormatSeconds($arr['duration']);

				$arr['f_url_img']	='';
				$arr['coverid']		and $arr['f_url_img']=$this->vars['url_server']."/music/{$arr['coverid']}/cover.png";

				$arr['f_artist']	=$this->_FormatTitle($arr['artist'],0);
				$arr['f_title']		=$this->_FormatTitle($arr['title']);

				if($status['remote']){ //this is a radio
					$arr['artwork_url']	and $arr['f_url_img']	=$arr['artwork_url'];
					
					//fix bad formatted radio
					$radio_name=$status['current_title'];
					similar_text($radio_name,$arr['artist'],$perc);
					if($perc >= 70){
						list($artist,$title)=explode(' - ',$arr['title']);
						if(trim($title)){
							$arr['f_artist']	=$this->_FormatTitle($artist,0); //. " ($perc)";
							$arr['f_title']		=$this->_FormatTitle($title);
						}
					}
					
				}
				
				
				$arr['album']		and $arr['f_album']		=$this->_FormatTitle($arr['album']) and $arr['f_album']	= '['. $arr['f_album'].']';
				if( $arr['f_full_title'] 	=$this->_makeSongFullTitle($arr)){
					$arr['f_url_youtube']	='https://www.youtube.com/results?search_query='.urlencode($arr['f_full_title']);
					$arr['f_url_allmusic']	='http://www.allmusic.com/search/songs/'.urlencode($arr['f_full_title']);
					$arr['f_url_google']	='https://www.google.com/search?q='.urlencode($arr['f_full_title']);
				}
				//store it
				$row['status']['playlist_loop'][$k]=$arr;
			}
			//$row['f_playing']=$row['status']['playlist_loop'][$status['playlist_cur_index']];
			$row['f_playing']=$row['status']['playlist_loop'][0];
		}
		list($row['f_ip'],$row['f_port'])=explode(':',$status['player_ip']);
		$row['f_mac']		=strtoupper($row['playerid']);
		$row['f_volume']	=$status['mixer volume'];
		$row['f_repeat']	=$status['playlist repeat'];
		$row['f_mode']		=$status['playlist mode'];
		$row['f_shuffle']	=$status['playlist shuffle'];
		
		$row['f_time']	=$this->_FormatSeconds($status['time']);
		if($status['time'] and $row['f_playing']['duration']){
			$row['f_position']="( <span class='jsCurTime'>{$row['f_time']}</span> / {$row['f_playing']['f_duration']} )";
		}
		elseif($row['f_playing']['duration']){
			$row['f_position']="( {$row['f_playing']['f_duration']} )";
		}
		//make rew & ff
		if($status['time']){
			$row['f_rw1'] = max(floatval($status['time']) - $this->vars['scroll_time1'], 0);
			$row['f_ff1'] = min(floatval($status['time']) + $this->vars['scroll_time1'], floatval($row['f_playing']['duration']) );
			
			$row['f_rw2'] = max(floatval($status['time']) - $this->vars['scroll_time2'], 0);
			$row['f_ff2'] = min(floatval($status['time']) + $this->vars['scroll_time2'], floatval($row['f_playing']['duration']) );
		}
		
		
		//buttons state
		if($status['mode'] =='play')	$row['f_states']['play']	=1;
		if($status['mode'] =='stop')	$row['f_states']['stop']	=1;
		if($status['mode'] =='pause')	$row['f_states']['pause']	=1;
		if(!$row['f_repeat'])			$row['f_states']['repeat_0']=1;
		if($row['f_repeat']==1)			$row['f_states']['repeat_1']=1;
		if($row['f_repeat']==2)			$row['f_states']['repeat_2']=1;
		if(!$row['f_shuffle'])			$row['f_states']['shuffle_0']=1;
		if($row['f_shuffle']==1)		$row['f_states']['shuffle_1']=1;
		if($row['f_shuffle']==2)		$row['f_states']['shuffle_2']=1;
		if($row['power'])				$row['f_states']['power']	=1;
		if($row['f_volume'] < 0)		$row['f_states']['mute']	=1;
		$row['f_volume']=abs($row['f_volume']); 

		//$row['remoteMeta']['f_duration']=$this->_FormatSeconds($row['remoteMeta']['duration']);
		return $row;
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
		$metas['f_artist'] and $full_title .="{$metas['f_artist']} - ";
		$full_title .="{$metas['f_title']}";
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
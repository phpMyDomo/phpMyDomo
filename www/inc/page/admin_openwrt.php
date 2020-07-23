<?php
class PMD_Page extends PMD_Root_Page{

	var $o_owa;
	//----------------------------------------------------------------------------------
	function __construct(& $class){
		parent::__construct($class);
		$this->RequirePageConf();		
	}

	//----------------------------------------------------------------------------------
	function Run(){
		require_once($this->conf['libs']['openwrtapi']);

		$hosts=$this->_ListHosts();
		
		$ajax=$_GET['ajax'];

		if($ajax){
			$query=json_decode($ajax, true);
			if(is_array($query)){
				$this->_AjaxListStations($query);
			}
		}
		else{
			$data['routers_count']=count($hosts);
			$data['bs_col']=floor(12/$data['routers_count']);
			foreach($hosts as $host){
				$this->owa= new OpenWrtApi('http://'.$host['host']);
				if($this->owa->UbusLogin($host['user'],$host['pass'])){
					$data['routers'][$host['host']]['sys_board']=$this->owa->CallUbus('system','board');
					$data['routers'][$host['host']]['sys_info']=$this->owa->CallUbus('system','info');
					if($data['routers'][$host['host']]['radios']=$this->owa->CallUbus('luci-rpc','getWirelessDevices')){
						$json_query=array();
						$json_query['host']=$host['host'];
						foreach($data['routers'][$host['host']]['radios'] as $radio => $radio_infos){
							unset($data['routers'][$host['host']]['radios'][$radio]['interfaces']);
							foreach($radio_infos['interfaces'] as $interface){
								$ifname=$interface['ifname'];
								//$interface['stations']=$this->_ListStations($host['host'], $ifname);
								$data['routers'][$host['host']]['radios'][$radio]['interfaces'][$ifname]=$interface;
								$json_query['interfaces'][]=$ifname;
							}
						}
						$data['routers'][$host['host']]['json_interfaces']=json_encode($json_query);
					}
				}
			}
	
		}
		
		// debug @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
		//echo "<hr><pre>\n"; print_r($data);echo "\n</pre>\n\n";exit;
		
		$this->Assign('data',	$data);
		$this->DisplayAdmin();
	}

	//----------------------------------------------------------------------------------
	private function _AjaxListStations($query){
		$hosts=$this->_ListHosts();
		$host=$hosts[$query['host']];
		$out=array();
		$out['error']=0;
		$out['error_txt']='OK';
		if(is_array($query['interfaces'])){
			$this->owa= new OpenWrtApi('http://'.$host['host']);
			if($session_id=$this->_loadSession($host['host'])){
				$this->owa->SetSessionId($session_id);
			}
			if($this->owa->CallUbus('system','info') ){
				$logged_in=true;
			}
			else{
				if($logged_in=$this->owa->UbusLogin($host['user'],$host['pass'])){
					$this->_saveSession($host['host']);
				}
			}
			if($logged_in){
				foreach($query['interfaces'] as $if){
					$out['data'][$if]=$this->_ListStations($if);
				}
			}
			else{
				$out['error']=1;
				$out['error_txt']='Login failed';
			}
		}
		echo json_encode($out);
		exit;
	}

	//----------------------------------------------------------------------------------
	private function _saveSession($host){
		file_put_contents($this->conf['paths']['caches']."owa_session_$host", $this->owa->GetSessionId() );
	}
	//----------------------------------------------------------------------------------
	private function _loadSession($host){
		return @file_get_contents($this->conf['paths']['caches']."owa_session_$host");
	}

	//----------------------------------------------------------------------------------
	private $macs=array();
	private function _ListStations($ifname){
		$stations=$this->owa->CallUbus('iwinfo','assoclist',array('device'=>$ifname));
		$indexed_stations=array();
		if($stations['results']){

			if($this->vars['mac_to_ip'] and file_exists($this->vars['mac_file'])){
				$do_mac_to_ip=true;
				$lines=file($this->vars['mac_file']);
				foreach($lines as $line){
					list($mac,$ip,$host,$name)=explode("\t",trim($line));
					$this->macs[$mac]=array(
						'mac'	=> trim($mac),
						'ip'	=> trim($ip),
						'host'	=> trim($host),
						'name'	=> trim($name)
					);
				}
			}
			foreach($stations['results'] as $station){
				$indexed_stations[$station['mac']]=$station;
				$indexed_stations[$station['mac']]['info']=array('mac'=>'','ip'=>'','host'=>'','name'=>'','vendor'=>'');

				if($do_mac_to_ip){
					$info=$this->macs[$station['mac']] and $indexed_stations[$station['mac']]['info']=$info;
				}

				if($this->vars['mac_to_vendor']){
					$this->_UpdateVendorsDb();
					$indexed_stations[$station['mac']]['info']['vendor']=$this->_GetMacVendor($station['mac']);
				}
			}
		}
		return $indexed_stations;
	}


	//----------------------------------------------------------------------------------
	function _ListHosts(){
		$out=array();
		foreach($this->vars['hosts'] as $host){
			$out[$host['host']]=$host;
		}
		return $out;
	}


	//----------------------------------------------------------------------------------
	private $vendors=array();
	private function _UpdateVendorsDb($do_save=1){
		$cache_file=$this->conf['paths']['caches']."owa_vendors.txt";
		if(file_exists($cache_file) and filemtime($cache_file) > (time() - 3600*24) ){
			$lines=file($cache_file);
		}
		else{
			$lines=file('http://standards-oui.ieee.org/oui.txt');
			file_put_contents($cache_file,implode("",$lines));
		}

		foreach ($lines as $line) {
			$line=trim($line);
			if(preg_match("#([a-f0-9-]{8})[^\(]+\(hex\)\t\t(.*)#i",$line,$match)){
				$pre=strtoupper(str_replace('-',':',$match[1]));
				$name=ucwords(strtolower(trim($match[2])));
				$this->vendors[$pre]=$name;
			}
		}
	
	}

	//----------------------------------------------------------------------------------
	private function _GetMacVendor($mac){
		$prefix=substr($mac,0,8);
		return $this->vendors[$prefix];
	}

} 
?>
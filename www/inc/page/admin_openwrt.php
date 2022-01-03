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

		$this->SetHeadJavascript("var ow_levels=". json_encode($this->vars['levels']). ";");

		$routers=$this->_ListRouters();
		
		$ajax=$_GET['ajax'];

		if($ajax){
			$query=json_decode($ajax, true);
			if(is_array($query)){
				$this->_Ajax($query);
			}
		}
		else{
			$data['routers_count']=count($routers);
			$data['bs_col']=floor(12/$data['routers_count']);
			foreach($routers as $rout){
				$data['routers'][$rout['host']]['desc']=$rout['desc'];
				$scheme="http://";
				if($rout['ssl']){
					$scheme="https://";
				}
				$this->owa= new OpenWrtApi($scheme.$rout['host']);
				if($this->owa->UbusLogin($rout['user'],$rout['pass'])){
					$data['routers'][$rout['host']]['sys_board']=$this->owa->UbusCall('system','board');
					$data['routers'][$rout['host']]['sys_info']=$this->owa->UbusCall('system','info');
					if($data['routers'][$rout['host']]['radios']=$this->owa->UbusCall('luci-rpc','getWirelessDevices')){
						ksort($data['routers'][$rout['host']]['radios']);
						$json_query=array();
						$json_query['act']='stations';
						$json_query['host']=$rout['host'];
						foreach($data['routers'][$rout['host']]['radios'] as $radio => $radio_infos){
							unset($data['routers'][$rout['host']]['radios'][$radio]['interfaces']);
							foreach($radio_infos['interfaces'] as $interface){
								$ifname=$interface['ifname'];
								//$interface['stations']=$this->_ListStations($rout['host'], $ifname);
								$data['routers'][$rout['host']]['radios'][$radio]['interfaces'][$ifname]=$interface;
								//keep (last) bssid in radio
								$data['routers'][$rout['host']]['radios'][$radio]['bssid']=$interface['iwinfo']['bssid'];
								$json_query['interfaces'][]=$ifname;
							}
						}
						$data['routers'][$rout['host']]['json_interfaces']=json_encode($json_query);
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
	private function _Ajax($query){
		$routers=$this->_ListRouters();
		$rout=$routers[$query['host']];
		$out['error']=0;
		$out['error_txt']='OK';

		$scheme="http://";
		if($rout['ssl']){
			$scheme="https://";
		}

		$this->owa= new OpenWrtApi($scheme.$rout['host']);
		if($session_id=$this->_loadSession($rout['host'])){
			$this->owa->SetSessionId($session_id);
		}
		if($out['data']['sys_info']=$this->owa->UbusCall('system','info') ){
			$logged_in=true;
		}
		else{
			if($logged_in=$this->owa->UbusLogin($rout['user'],$rout['pass'])){
				$this->_saveSession($rout['host']);
			}
		}
		if(!$logged_in){
			$out['error']=1;
			$out['error_txt']='Login failed';
		}

		if($query['act']=='stations'){
			$out['data']['stations']=$this->_ListStations($query);
		}
		elseif($query['act']=='disconnect'){
			$this->owa->UbusCall('hostapd.'.$query['ifname'], 'del_client', array('addr'=>$query['mac'],'ban_time'=>60000,'deauth'=>true,'reason'=>5));
		}
		elseif($query['act']=='reboot'){
			//if($out['data']=$this->owa->UbusCall('system','reboot',array())){
			if($out['data']=$this->owa->UbusCall('file','exec',array('command'=>'/sbin/reboot'))){
				$out['data']['msg']="rebooting...";
			}
			else{
				$out['debug_errors']=$this->owa->GetErrors();
			}
		}
		echo json_encode($out);
		exit;	
	}

	//----------------------------------------------------------------------------------
	private function _ListStations($query){
		$out=array();
		if(is_array($query['interfaces'])){
			return $this->_UbusListStations($query['interfaces']);
		}
	}


	//----------------------------------------------------------------------------------
	private function _saveSession($rout){
		file_put_contents($this->conf['paths']['caches']."owa_session_$rout", $this->owa->GetSessionId() );
	}
	//----------------------------------------------------------------------------------
	private function _loadSession($rout){
		return @file_get_contents($this->conf['paths']['caches']."owa_session_$rout");
	}

	//----------------------------------------------------------------------------------
	private $macs=array();
	private function _UbusListStations($interfaces){
		$if_stations=$this->owa->UbusListStations($interfaces);
		$indexed_stations=array();
		if($if_stations){
			//get MAC infos
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
			foreach($if_stations as $if => $stations){
				$indexed_stations[$if]=array();
				if(is_array($stations)){
					foreach($stations as $station){
						$indexed_stations[$if][$station['mac']]=$station;
						$indexed_stations[$if][$station['mac']]['info']=array('mac'=>'','ip'=>'','host'=>'','name'=>'','vendor'=>'');
	
						if($do_mac_to_ip){
							$info=$this->macs[$station['mac']] and $indexed_stations[$if][$station['mac']]['info']=$info;
						}
	
						if($this->vars['mac_to_vendor']){
							$this->_UpdateVendorsDb();
							$indexed_stations[$if][$station['mac']]['info']['vendor']=$this->_GetMacVendor($station['mac']);
						}
						//make sort key
						$sort=$info['name'] or $sort=$info['host'] or $sort=$info['ip'] or $sort=$info['vendor'] or $sort=$indexed_stations[$if][$station['mac']]['info']['vendor'];
						$indexed_stations[$if][$station['mac']]['sort_key']=strtolower($sort);
	
					}	
				}

				if($do_mac_to_ip or $this->vars['mac_to_vendor']){
					$this->_SortArrayByColumn($indexed_stations[$if],'sort_key');
				}
			}
		}
		return $indexed_stations;
	}

	//----------------------------------------------------------------------------------
	private function _SortArrayByColumn( &$arr, $col, $dir = SORT_ASC) {
		$sort_col = array();
		if(is_array($arr)){
			foreach ($arr as $key => $row) {
				$sort_col[$key] = $row[$col];
			}
			array_multisort($sort_col, $dir, $arr);
		}
	}


	//----------------------------------------------------------------------------------
	function _ListRouters(){
		$out=array();
		foreach($this->vars['routers'] as $rout){
			$out[$rout['host']]=$rout;
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
			$lines=$this->_FetchVendorsDb();
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
	private function _FetchVendorsDb(){
		$cache_file	=$this->conf['paths']['caches']."owa_vendors.txt";
		$lock_file	=$this->conf['paths']['caches']."owa_vendors.lock";

		if(file_exists($lock_file) ){
			if(filemtime($lock_file) < (time() - 3600)){
				unlink($lock_file);
			}
			$clean_lines=file($cache_file);
		}
		else{
			file_put_contents($lock_file,time());
			
			$lines=file('http://standards-oui.ieee.org/oui.txt');
			//keep only hex lines
			$oui_data='';
			foreach ($lines as $line) {
				if(preg_match("#([a-f0-9-]{8})[^\(]+\(hex\)\t\t(.*)#i",$line,$match)){
					$oui_data .="$line";
					$clean_lines[]=$line;
				}
			}
			file_put_contents($cache_file,$oui_data);
			unlink($lock_file);
		}

		return $clean_lines;
	}

	//----------------------------------------------------------------------------------
	private function _GetMacVendor($mac){
		$prefix=substr($mac,0,8);
		return $this->vendors[$prefix];
	}

} 
?>
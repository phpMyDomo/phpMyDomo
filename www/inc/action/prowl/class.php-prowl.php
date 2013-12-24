<?php
/**
 * php-prowl
 *
 * This class provides a simple mechanism for interacting with the prowlapp.com
 * API service for pushing notifications to iOS devices.
 * @author Scott Wilcox <scott@dor.ky>
 * @version 0.1
 * @package php-prowl	
 */
class Prowl
{
	private $api_root = "https://api.prowlapp.com/publicapi/";
	private $user_agent = "php-prowl <http://dor.ky>";
	private $api_key = null;
	private $api_provider_key = null;
	private $request_method = "GET";
	private $http_code = null;
	private $debug = false;
	
	public $remaining_calls = 0;
	public $resetdate = 0;
	
	public function setApiKey($key)
	{
		$this->api_key = $key;
	}
	public function setProviderKey($key)
	{
		$this->api_provider_key = $key;
	}
	private function setRequestMethod($method) {
		$this->request_method = $method;
	}
	private function buildQuery($params) {
          $query_string = "";
          if ($this->api_key != null)
            $query_string .= "apikey=".$this->api_key."&";
          
          if ($this->api_provider_key != null)
            $query_string .= "providerkey=".$this->api_provider_key."&";
          if (count($params)) {
            foreach ($params as $key => $value) {
              $query_string .= "$key=".urlencode($value)."&";
            }
          }
          
          return substr($query_string, 0, -1);
	}
	public function setDebug($bool) {
		$this->debug = $bool;
		if (!defined('DEMO_EOL')) {
			define("DEMO_EOL",isset($_SERVER['HTTP_USER_AGENT']) ? "<br />" : "\n");
		}
	}

	public function add($application = "php-prowl",$event,$priority = 0,$description,$url="") {
		if (empty($this->api_key)) {
			throw new Exception("No API key(s) set.");
		}
			
		// Set POST method
		$this->setRequestMethod("POST");
		
		// This is our payload for this alert
		$fields = array(
			'application' => $application,
			'event' => $event,
			'description' => $description,
			'url' => $url,
			'priority' => $priority
		);

		return $this->request("add",$fields);
	}
	
	public function verify($key) {
		$this->setRequestMethod("GET");
		
	}
	
	public function request_token() {
		if (empty($this->api_provider_key)) {
			throw new Exception("No provider key(s) set.");
		}
		
		// Set GET method
		$this->setRequestMethod("GET");		

		$response = $this->request("retrieve/token");
		if ($response) {	
			if ($response->success["code"] == 200) {
				return $response->retrieve;
			} else {
				throw new Exception("API Request Failed: ".var_dump($response));
			}
		}
	}
	
	public function retrieve_apikey($token) {
		if (empty($this->api_provider_key)) {
			throw new Exception("No provider key(s) set.");
		}

		// Set GET method
		$this->setRequestMethod("GET");		
		
		// Send our request out
		$response = $this->request("retrieve/apikey",array("token" => $token));
		if ($response) {	
			if ($response->success["code"] == 200) {
				return $response->retrieve["apikey"][0];
			} else {
				throw new Exception("API Request Failed: ".var_dump($response));
			}
		}		
	}
	
	private function request($endpoint,$params = null) {
		// Push the request out to the API
		$url = $this->api_root.$endpoint;		
		$params = $this->buildQuery($params);
				
		if ($this->request_method == "GET") {
      		$url .= "?".$params;
		}
		
		$s = curl_init();
		curl_setopt($s, CURLOPT_URL, $url);
		
		if ($this->request_method == "POST") {
			curl_setopt($s, CURLOPT_POST, true);
			curl_setopt($s, CURLOPT_POSTFIELDS, $params);
		}
		
		curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($s, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($s, CURLOPT_HTTPHEADER, array("Expect:"));
		curl_setopt($s, CURLOPT_HEADER, false);
		curl_setopt($s, CURLOPT_SSL_VERIFYPEER, false);		
		curl_setopt($s, CURLINFO_HEADER_OUT, true);
		curl_setopt($s, CURLOPT_USERAGENT, $this->user_agent);
		
		$response_xml = simplexml_load_string(curl_exec($s));
		$this->http_code = curl_getinfo($s, CURLINFO_HTTP_CODE);		
		
		if ($this->debug === true) {
			echo "API URL:".DEMO_EOL."$url".DEMO_EOL;
			echo "<hr />";			
			if (!empty($params)) {
				echo "Payload (".$this->request_method."):".DEMO_EOL;			
				if ($this->request_method == "POST") {
					echo var_dump($params);
				} else {
					echo var_export($params);
				}
				echo "<hr />".DEMO_EOL;				
			}
			echo "HTTP Header:".DEMO_EOL;
			echo curl_getinfo($s,CURLINFO_HEADER_OUT).DEMO_EOL;
			echo "<hr />";			
		}
		
		if (!empty($response_xml->success)) {
			$this->remaining_calls = $response->success["remaining_calls"];
			$this->resetdate = $response->success["resetdate"];		
		}
		curl_close($s);
		return $response_xml;	
	}
}
?>
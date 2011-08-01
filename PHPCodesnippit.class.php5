<?php 
/**
 * PHPCodesnipp.it-LIGHT
 * 
 * PLEASE DO NOT TOUCH THIS FILE
 * 
 * @author Sebastian Roming
 * @license CC-BY-SA 3.0
 */

class PHPCodesnippit {
	
	/**
	 * Public members
	 */
	public $apiEndpoint = 'http://codesnipp.it/v2/api';
	
	/**
	 * Protected members
	 */
	protected $_requestMethod;
	protected $_urlParams;
	protected $_params;
	
	/**
	 * Private members
	 */
	private $_availableRequestMethods = array('GET', 'POST');
	private $_authToken = false;

	
	// --------------------------------------------------
	/**
	 * constructor
	 */
	public function __construct() { 
		
	}
	
	// --------------------------------------------------
	/**
	 * Does the request to the API
	 * 
	 * @param string $methodName
	 * @param array $params
	 * @param string $requestType
	 * @return mixed
	 */
	public function apiCall($methodName, Array $params = null, $requestType = 'GET') {
		
		$this->_clearParams();
				
		if ($methodName == 'auth') {
			
			if (count($params) != 1) {
				throw new WrongParamCountException('Wrong param count for method "' . $methodName . "'");
			}
			
			$this->setRequestMethod('GET');
			$this->setUrlParam('auth');
			$this->setUrlParam($params[0]);			
			
			$authResult = json_decode( $this->_request() );
			
			if ($authResult[0]->status === 'error') {
				
				throw new Exception('Authentication failed: ' . $authResult[0]->message);
				
			}
			
			$this->_authToken = $authResult[0]->token;
			
			return $this->_authToken;
			exit;
			
		}
		
		$this->setRequestMethod('POST');
		$this->setUrlParam($methodName);
		
		if ($params !== null) {
			foreach ($params as $key => $param) {
				$this->setPostParam(array($key=>$param));
			}
		}
		
		if ($this->_authToken !== false) {
			$this->setPostParam(array('auth_token'=>$this->_authToken));
		}
		
		$result = $this->_request();
		
		return $result;
		
	}
	
	// --------------------------------------------------
	/**
	 * Sets the request method for cURL request (POST/GET)
	 * 
	 * @param string $requestMethod
	 */
	public function setRequestMethod($requestMethod='GET') {
		
		if (!in_array($requestMethod, $this->_availableRequestMethods)) {
			throw new CriticalException('Request method not allowed.');
		}
		
		$this->_requestMethod = $requestMethod;
		
	}
	
	// --------------------------------------------------
	/**
	 * Sets URL params for the API Endpoint
	 * 
	 * @param string $urlParam
	 */
	public function setUrlParam($urlParam) {

		$this->_urlParams[] = $urlParam;
		
	}	
	
	// --------------------------------------------------
	/**
	 * Sets POST Params for the API request
	 * 
	 * @param mixed $param
	 */
	public function setPostParam($param) {
		
		$this->_params[] = $param;
		
	}
	
	// --------------------------------------------------
	/**
	 * Builds the URL that points to the API Endpoint and
	 * starts the cURL request
	 * 
	 * @return mixed
	 */
	protected function _request() {
		
		$url = $this->apiEndpoint;
		
		if (!empty($this->_urlParams)) {
			foreach ($this->_urlParams as $urlParam) {
				$url .= '/' . $urlParam;
			}
		}
				
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'PHPCodesnipp.it/0.1b by sebastianroming');
		
		if ($this->_requestMethod == 'POST') {
			
			if (!empty($this->_params)) {
				
				$postFields = '';
				foreach ($this->_params as $key => $value) {
					
					if (is_array($value)) {
						
						foreach ($value as $k => $v) {
							$postFields .= $k . '=' . $v . '&';
						}
						
					} else {
						$postFields .= $key . '=' . $value . '&';
					}
					
				}
				rtrim($postFields, '&');
				
			}
			
			curl_setopt($ch, CURLOPT_POST, count($postFields));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
			
		}
				
		$result = curl_exec($ch);
		curl_close($ch);
		
		return $result;
		
	}
	
	// --------------------------------------------------
	/**
	 * Clears all URL and POST params
	 */
	private function _clearParams() {
		$this->_urlParams = array();
		$this->_params = array();
	}
	
	
}
?>
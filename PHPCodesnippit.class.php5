<?php 
/**
 * PHPCodesnipp.it
 * 
 * PLEASE DO NOT TOUCH THIS FILE
 * 
 * @author Sebastian Roming
 * @license tba
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
	
	
	/**
	 * Public methods
	 */
	// --------------------------------------------------
	public function __construct() {
		
	}
	
	// --------------------------------------------------
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
			
			$authToken = $authResult[0]->token;
			
			return $authToken;
			
			exit;
			
		}
		
		$this->setRequestMethod('POST');
		$this->setUrlParam($methodName);
		
		if ($params !== null) {
			foreach ($params as $key => $param) {
				$this->setParam(array($key=>$param));
			}
		}
		
		$result = $this->_request();
		
		return $result;
		
	}
	
	// --------------------------------------------------
	public function setRequestMethod($requestMethod='GET') {
		
		if (!in_array($requestMethod, $this->_availableRequestMethods)) {
			throw new CriticalException('Request method not allowed.');
		}
		
		$this->_requestMethod = $requestMethod;
		
	}
	
	// --------------------------------------------------
	public function setUrlParam($urlParam) {

		$this->_urlParams[] = $urlParam;
		
	}	
	
	// --------------------------------------------------
	public function setParam($param) {
		
		$this->_params[] = $param;
		
	}
	
	
	/**
	 * Protected methods
	 */
	// --------------------------------------------------
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
		curl_setopt($ch, CURLOPT_USERAGENT, 'PHPCodesnipp.it/0.0.1a by sebastianroming');
		
		if ($this->_requestMethod == 'POST') {
			
			if (!empty($this->_params)) {
				
				$postFields = '';
				foreach ($this->_params as $key => $value) {
					$postFields .= $key . '=' . $value . '&';
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
	
	
	/**
	 * Private methods
	 */
	// --------------------------------------------------
	private function _clearParams() {
		$this->_urlParams = array();
		$this->_params = array();
	}
	
	
}
?>
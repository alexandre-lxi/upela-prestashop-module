<?php

/**
 * Upela API connector
 */
class UpelaApi {

# CLASS CONSTANTS

	const METHOD_GET 	= 'get';
	const METHOD_POST 	= 'post';
	
	const DEFAULT_LOG_FILE_NAME = 'upela-api-log-%s.txt'; // %s is substituted by date
	
	protected $nTimeout = 30;

	protected $sUrl = '';

	protected $sLogin = '';

	protected $sPassword = '';

	protected $sMethod = 'post';
	
	protected $sLogFile = null;

	protected $bLogging = false;
	
	protected $sLogId = null;

# PUBLIC METHODS

	# GETTERS / SETTERS

	/**
	 * Gets API url
	 * @return string $sUrl Url
	 */
	public function getUrl() {

		return $this->sUrl;

	} // getUrl

	/**
	 * Sets API url
	 * @param string $sUrl
	 * @return UpelaApi Self
	 */
	public function setUrl($sUrl) {

		$this->sUrl = (string)$sUrl;

		return $this;

	} // setUrl

	/**
	 * Gets API login
	 * @return string Login
	 */
	public function getLogin() {

		return $this->sLogin;

	} // getLogin

	/**
	 * Sets API login
	 * @param string $sLogin New login
	 * @return UpelaApi Self
	 */
	public function setLogin($sLogin) {

		$this->sLogin = (string)$sLogin;

		return $this;

	} // setLogin

	/**
	 * Gets API password
	 * @return string Password
	 */
	public function getPassword() {

		return $this->sPassword;

	} // getPassword

	/**
	 * Sets API password
	 * @param string $sPassword New password
	 * @return UpelaApi Self
	 */
	public function setPassword($sPassword) {

		$this->sPassword = (string)$sPassword;

		return $this;

	} // setPassword

	/**
	 * Gets API method
	 * @return string Method (get or post)
	 */
	public function getMethod() {

		return $this->sMethod;

	} // getMethod

	/**
	 * Sets API method
	 * param string $sMethod Method (get or post)
	 * @return UpelaApi Self
	 */
	public function setMethod($sMethod) {

		if (in_array((string)$sMethod, array(self::METHOD_GET, self::METHOD_POST), true)) {

			$this->sMethod = (string)$sMethod;

		} // if

		return $this;

	} // setMethod

	/**
	 * Gets curl timeout
	 * @return int Number of seconds
	 */
	public function getTimeout() {
	
		return $this->nTimeout;
	
	} // getTimeout
	
	/**
	 * Sets curl timeout
	 * param int $nTimeout Number of seconds 
	 * @return UpelaApi Self
	 */
	public function setTimeout($nTimeout) {
		
		$this->nTimeout = (int)$nTimeout;
		
		return $this;
	
	} // setTimeout
	
	/**
	 * Gets log file
	 * @return string Path to log file
	 */
	public function getLogFile() {
		
		if ($this->sLogFile === null) {
			
			$this->setLogFile(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . sprintf(self::DEFAULT_LOG_FILE_NAME, date('Ymd')));
			
		}
		
		if (!file_exists(dirname($this->sLogFile))){
			
			mkdir(dirname($this->sLogFile), 0755, true);
			
		}
	
		return $this->sLogFile;
	
	} // getLogFile
	
	/**
	 * Activates logging
	 * @return UpelaApi Self
	 */
	public function activateLogging() {
	
		$this->bLogging = true;
	
		return $this;
	
	} // activateLogging
	
	/**
	 * Deactivates logging
	 * @return UpelaApi Self
	 */
	public function deactivateLogging() {
	
		$this->bLogging = false;
	
		return $this;
			
	} // deactivateLogging
	
	/**
	 * Deactivates logging
	 * @return UpelaApi Self
	 */
	public function isLoggingActive() {
	
		return $this->bLogging;
				
	} // isLoggingActive
		
	/**
	 * Sets log file
	 * param string $sLogFile Log file
	 * @return UpelaApi Self
	 */
	public function setLogFile($sLogFile) {
	
		$this->sLogFile = (string)$sLogFile;
	
		return $this;
	
	} // setLogFile
	
	
	# REQUESTS
	public function login($aData){

		return $this->call('login', $aData);

	}

	public function rate($aData){

		return $this->call('rate', $aData);

	}

	public function selectOffer($aData){

		return $this->call('select_offer', $aData);

	}

	public function ship($aData){

		return $this->call('ship', $aData);

	}

	public function pickup($aData){

		return $this->call('pickup', $aData);


	}

	public function cancelPickup($aData){

		return $this->call('cancel_pickup', $aData);

	}

	public function track($aData){

		return $this->call('track', $aData);

	}

	# CALL

	protected function call($sMethod, $aRequest){

		$sUrl = $this->getRequestUrl($sMethod);

		$sRequest = $this->getRequestString($aRequest);

		if ($this->isLoggingActive()){
		
			$this->log('Call');

			$this->log($sUrl);

			$this->log($sRequest);		
			
		}
	

		switch ($this->getMethod()) {

			case self::METHOD_GET:

				$mRequestResult = $this->makeGetRequest($sUrl, $sRequest);

			break;

			default:
			case self::METHOD_POST:

				$mRequestResult = $this->makePostRequest($sUrl, $sRequest);

			break;

		} // switch
		
		if ($this->isLoggingActive()){
			
			$this->log('Response');
				
			$this->log($mRequestResult);		
					
		}

		if ($mRequestResult){
			
			if (substr($mRequestResult, 0, 1) != '{' && strpos($mRequestResult, '{')!==false) {

				$mRequestResult = substr($mRequestResult, strpos($mRequestResult, '{'));
				
			}

			$mResult = json_decode($mRequestResult, true);

			if ($mResult && is_array($mResult)) {

				return $mResult;

			} // if

		}// if

		return array();

	}

	protected function initCurlHandle($sUrl, $sMethod){

		$rCurl = curl_init($sUrl);

		curl_setopt($rCurl, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($rCurl, CURLOPT_TIMEOUT, (int)$this->getTimeout());
    
    curl_setopt($rCurl, CURLOPT_SSL_VERIFYPEER, 0);
    
    curl_setopt($rCurl, CURLOPT_SSL_VERIFYHOST, 0);
		
		switch ($sMethod) {

			case self::METHOD_GET:

				curl_setopt($rCurl, CURLOPT_HTTPGET, 1);

			break;

			case self::METHOD_POST:

				curl_setopt($rCurl, CURLOPT_POST, 1);

			break;

		} // switch

		return $rCurl;

	}

	protected function makeGetRequest($sUrl, $sRequest){

		$sCallUrl = $sUrl . '?' . http_build_query(array('request' => $sRequest));

		$rCurl = $this->initCurlHandle($sCallUrl, self::METHOD_GET);

		return curl_exec($rCurl);

	}

	protected function makePostRequest($sUrl, $sRequest){

		$sCallUrl = $sUrl;

		$rCurl = $this->initCurlHandle($sCallUrl, self::METHOD_POST);

		curl_setopt($rCurl, CURLOPT_POSTFIELDS, http_build_query(array('request' => $sRequest)));

		$mResult = curl_exec($rCurl);
		
		if ($this->isLoggingActive()){
			
			$this->log('CURL INFO ' . json_encode(curl_getinfo($rCurl)));
			
		}
			
		return $mResult;

	}

	protected function getRequestUrl($sMethod){
		
		return rtrim($this->getUrl(), '/') . '/' . strtolower($sMethod) . '/';

	}

	protected function getRequestString(array $aRequest){

		return json_encode(array('account' => $this->getCredentials()) + $aRequest);

	}

	/**
	 * Gets credentials
	 * @return array
	 */
	protected function getCredentials(){

		return array('login' => $this->getLogin(), 'password' => $this->getPassword());

	}

	# LOG
	
	protected function log($mMessage){
				
		$sMessage = is_string($mMessage) ? $mMessage : var_export($mMessage, true);
		
		$sMessage = sprintf(PHP_EOL . '[%s] [%s] [%s] %s' . PHP_EOL, $this->getLogId(), PHP_SAPI === 'cli' || !isset($_SERVER['REMOTE_ADDR']) ? PHP_SAPI :  $_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s'), $sMessage);	
		
		file_put_contents($this->getLogFile(), $sMessage, FILE_APPEND);
		
	}
	
	protected function getLogId(){

		if ($this->sLogId === null) {
			
			$this->sLogId = substr(str_pad(base_convert(uniqid(mt_rand(), true), 16, 34), 8, 'z', STR_PAD_RIGHT), 0, 8);
						
		}
		
		return $this->sLogId;
		
	}

}
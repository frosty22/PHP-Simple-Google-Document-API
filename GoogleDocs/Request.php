<?php

namespace GoogleDocs;

/**
 * CURL request object
 *
 * @author vit-ledvinka
 */
class Request {
	
	/**
	 * URL 
	 * @var string 
	 */
	private $url;
	
	/**
	 * POST query parts data
	 * @var array 
	 */
	private $posts = array();
	
	/**
	 * GET query parts data
	 * @var array
	 */
	private $gets = array();
	
	/**
	 * HEADER query parts
	 * @var array
	 */
	private $headers = array();
	
	/**
	 * Response of request
	 * @var string
	 */
	private $response;

	/**
	 * Create request
	 * @param string $url Request URL 
	 */
	public function __construct($url) {
		$this->url = $url;
	}

	/**
	 * Set POST data for request
	 * @param array $posts 
	 */
	public function setPosts(array $posts) {
		$this->posts = $posts;
	}

	/**
	 * Set GET data for request
	 * @param array $gets 
	 */
	public function setGets(array $gets) {
		foreach ($gets as $name => $get) $gets[$name] = urlencode($get);
		$this->gets = $gets;
	}

	/**
	 * Set HEADERS data for request
	 * @param array $headers 
	 */
	public function setHeaders(array $headers) {
		$this->headers = $headers;
	}
	
	/**
	 * Get response of request
	 * @return string
	 */
	public function getResponse() {
		if (!isset($this->response)) {
			$this->execute();
		}
		return $this->response;
	}
	
	/**
	 * Execute request a receive response
	 * @throws \GoogleDocs\RequestException 
	 */
	private function execute()
	{
		$curl = curl_init();
		
		curl_setopt($curl, CURLOPT_URL, $this->url . (count($this->gets) ? "?".http_build_query($this->gets) : ""));
		
		if (count($this->posts)) {
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $this->posts);
		}
		if (count($this->headers)) {
			curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
		}
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		
		$this->response = curl_exec($curl);
		$state = \curl_getinfo($curl);
		if ($state["http_code"] !== 200) {
			throw new \GoogleDocs\RequestException("Bad http code of response, exception 200 but {$state["http_code"]} receive.");	
		}
		
		curl_close($curl);
	}
	
	
	
}
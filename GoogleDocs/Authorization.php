<?php

namespace GoogleDocs;

/**
 * Authorization of connection on Google Docs
 *
 * @author vit-ledvinka
 */
class Authorization {

	/**
	 * Base authorization constats 
	 */
	const ACCOUNT_TYPE = "HOSTED_OR_GOOGLE";
	const SERVICE = "writely";
	const CLIENT_LOGIN_URL = "https://www.google.com/accounts/ClientLogin";
	
	/**
	 * Email of user
	 * @var string
	 */
	private $email;
	
	/**
	 * Password of user
	 * @var string
	 */
	private $password;
	
	/**
	 * App name (whatever)
	 * @var string
	 */
	private $appName;
	
	/**
	 * Authorization token
	 * @var string
	 */
	private $auth;
	
	/**
	 * Create a authorization object
	 * @param string $email
	 * @param string $password
	 * @param string $appName 
	 */
	public function __construct($email, $password, $appName) {
		$this->email = $email;
		$this->password = $password;
		$this->appName = $appName;
	}

	/**
	 * Get authorization 
	 * @return string
	 */
	public function getAuth() {
		if (!isset($this->auth)) $this->authorize();
		return $this->auth;
	}
	
	/**
	 * Create authorized request
	 * @param string $url
	 * @return \GoogleDocs\Request 
	 */
	public function createAuthorizedRequest($url) {
		$request = new \GoogleDocs\Request($url);
		$request->setHeaders(array(
			"Authorization: GoogleLogin auth=" . $this->getAuth(),
			"GData-Version: 3.0",
		));
		return $request;
	}

	/**
	 * Get authorize token
	 * @throws \GoogleDocs\AuthorizationException 
	 */
	private function authorize()
	{
		$request = new \GoogleDocs\Request(self::CLIENT_LOGIN_URL);
		$request->setPosts(array(
			    "accountType" => self::ACCOUNT_TYPE,
				"Email" => $this->email,
				"Passwd" => $this->password,
				"service" => self::SERVICE,
				"source" => $this->appName
		));
		$response = $request->getResponse();
		
		if (preg_match("/Auth=([a-z0-9_-]+)/i", $response, $matches)) {
			$this->auth = $matches[1];
		} else
			throw new \GoogleDocs\AuthorizationException("Bad response for authorization.");
	}
	
	
	
	
	
}



<?php
declare(strict_types=1);

namespace Supermetrics;

// Load the API base helper class
require_once('API.php');

class Token extends \Supermetrics\API
{
	private $token;
	
	// Override the constructor visibility
	public function __construct(string $api)
	{
		$this -> token = FALSE;
		parent::__construct($api);
	}
	
	/**
	 * @brief Get the short-lived token from API
	 * 
	 * @param string $client_id
	 * 	The client ID to get the token with
	 * @param string $email
	 * 	Client email
	 * @param string $name
	 * 	Client name
	 * 
	 * @retval string
	 * 	The token itself, the informational data is discarded
	 * 
	 * @throws \Exception
	 * 	On API errors
	 */
	public function Get(string $client_id = '', string $email = '', string $name = '') : string
	{
		if ($this -> token !== FALSE)
		{
			return $this -> token;
		}
		
		$r = $this -> Request('register', 'POST', array('client_id' => $client_id, 'email' => $email, 'name' => $name));
		$this -> token = $r['sl_token'];
		return $this -> token;
	}
}

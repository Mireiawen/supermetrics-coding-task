<?php
declare(strict_types=1);

namespace Supermetrics;

abstract class API
{
	protected $api;
	
	// Construct the class
	protected function __construct($api)
	{
		// Check for extension
		if (!extension_loaded('curl'))
		{
			throw new \Exception(_('cURL extension is required!'));
		}
		
		// Set the API base URL
		$this -> api = $api;
	}
	
	/**
	 * @brief Do a request to the API with cURL
	 * 
	 * @param string $endpont
	 * 	API endpoint to send the request to
	 * @param string $method
	 * 	HTTP method to use, such as POST, GET,
	 * @param array $data
	 * 
	 * @retval array
	 * 	Array of the data received from the API, may be empty
	 * 
	 * @throws \Exception
	 * 	On errors, with error message
	 * @throws \JsonException
	 * 	On JSON data errors
	 */
	protected function Request(string $endpoint, string $method = 'GET', array $data = array()) : array
	{
		// Initialize handle
		$handle = curl_init(sprintf('%s/%s', $this -> api, $endpoint));
		if ($handle === FALSE)
		{
			throw new \Exception(_('cURL error: Initialization failed'));
		}
		
		// Set some default settings
		// These shouldn't be hardcoded but are now to save some time
		$opts = array
		(
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_FOLLOWLOCATION => TRUE,
			CURLOPT_POSTREDIR => 1+2+4,
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_HTTPHEADER => array
			(
				'Accept: application/json',
			),
			CURLOPT_CONNECTTIMEOUT => 15,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_CUSTOMREQUEST => $method,
		);
		
		// Set method specific settings
		switch ($method)
		{
		case 'GET':
			$opts[CURLOPT_URL] = sprintf('%s/%s?%s', $this -> api, $endpoint, http_build_query($data));
			break;
		
		case 'POST':
			$payload = http_build_query($data);
			$opts[CURLOPT_POSTFIELDS] = $payload;
			break;
		
		default:
			$opts[CURLOPT_POSTFIELDS] = $data;
			break;
		}
		
		if (curl_setopt_array($handle, $opts) === FALSE)
		{
			$errno = curl_errno($handle);
			$errmsg = curl_error($handle);
			curl_close($handle);
			throw new \Exception(sprintf(_('cURL error %s: %s'), $errno, $errmsg));
		}
		
		// Do the actual query
		$reply = curl_exec($handle);
		if ($reply === FALSE)
		{
			$errno = curl_errno($handle);
			$errmsg = curl_error($handle);
			curl_close($handle);
			throw new \Exception(sprintf(_('cURL error %s: %s'), $errno, $errmsg));
		}
		
		// Close the cURL connection
		curl_close($handle);
		
		// Try to extract the JSON data
		$json = json_decode($reply, TRUE, 512, JSON_OBJECT_AS_ARRAY|JSON_THROW_ON_ERROR);
		
		// Check for error message
		if (isset($json['error']))
		{
			throw new \Exception(sprintf(_('API error: %s'), $json['error']['message']));
		}
		
		return $json['data'];
	}
	
	/**
	 * @brief Get the current API endpoint
	 * 
	 * @retval string
	 * 	The current API endpoint
	 */
	protected function GetAPI() : string
	{
		return $this -> api;
	}
}

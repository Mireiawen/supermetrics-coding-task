<?php
declare(strict_types=1);

namespace Supermetrics;

// Load the API base helper class
require_once('API.php');

// Load the Token helper class

class Post extends \Supermetrics\API
{
	protected $token;
	
	// Override the constructor visibility
	public function __construct(\Supermetrics\Token &$t)
	{
		parent::__construct($t -> GetAPI());
		$this -> token = $t;
	}
	
	/**
	 * @brief Get the posts on specific page
	 * 
	 * @param int $page
	 * 	The page to get posts from
	 * 
	 * @retval array
	 * 	The array of posts retrieved
	 * 
	 * @throws \Exception
	 * 	On API errors
	 */
	public function GetPosts(int $page) : array
	{
		$r = $this -> Request('posts', 'GET', array('sl_token' => $this -> token -> Get(), 'page' => $page));
		return $r['posts'];
	}
}

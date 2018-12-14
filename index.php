<?php
// Load the configuration
require_once('config.inc.php');

// Get the short-lived token
require_once('Token.php');
$token = new \Supermetrics\Token(\Config::API_URL);
$token -> Get(\Config::CLIENT_ID, \Config::CLIENT_EMAIL, \CONFIG::CLIENT_NAME);

// Get the post information
require_once('Post.php');
$post = new \Supermetrics\Post($token);
$posts = array();
for ($i = 1; $i < \Config::POST_PAGES+1; $i++)
{
	$posts = array_merge($posts, $post -> GetPosts($i));
}

// Go through the posts to get the statistics
$months = array();
$weeks = array();
$users = array();
foreach ($posts as $post)
{
	// Get the week and month
	$week = date('W', strtotime($post['created_time']));
	$month = date('m', strtotime($post['created_time']));
	
	// Check for user
	$uid = $post['from_id'];
	if (!isset($users[$month][$uid]))
	{
		$users[$month][$uid] = 0;
	}
	
	// Get the post length
	$len = strlen($post['message']);
	
	// Store for statistics
	$weeks[$week]++;
	$months[$month][] = $len;
	$users[$month][$uid]++;
}

// Sort the data
ksort($months);
ksort($weeks);
ksort($users);

// Get the monthly statistics
foreach ($months as $month => $posts)
{
	$avg = array_sum($posts) / count($posts);
	$max = max($posts);
	printf("%s: average %02.2f characters, longest %d characters\n", date('F', mktime(0, 0, 0, $month, 10)), $avg, $max);
}

// Get the weekly posts
foreach ($weeks as $week => $posts)
{
	printf("Week %02d: %d posts\n", $week, $posts);
}

// Get the average user posts by month
foreach ($users as $month => $userdata)
{
	$avg = array_sum($userdata) / count($userdata);
	printf("In %s users wrote average of %02.2f posts\n", date('F', mktime(0, 0, 0, $month, 10)), $avg);
}

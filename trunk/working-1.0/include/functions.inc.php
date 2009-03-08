<?php

/**
 * Monkey Boards
 * /include/functions.inc.php
 * Standard functions file
 * 
 * @package MonkeyBoards/include
 * @version 1.0
 * @author Jamie Hurst
 */

// Always start a PHP session
session_start();

/**
 * Open up the database connection
 */
function db_open() {
	global $database, $db;
	$db = sqlite_popen($database, 0666, $db_error)
		or die($db_error);
}

/**
 * Count the current number of topics for a given forum
 * @global mixed Database connection
 * @param string $forum_name Forum name
 * @return int|boolean Number of topics
 */
function count_topics($forum_name) {
	global $db;
	
	// Remember to make sure the forum actually exists
	if(forum_exists($forum_name)) {
		$result = sqlite_query($db, "
				SELECT `id` 
				FROM `topics` 
				WHERE `forum` = '{$forum_name}'
			", SQLITE_ASSOC);
		return sqlite_num_rows($result);
	} else
		return false;
	
}

/**
 * Count the number of posts for a given forum
 * @global mixed Database connection
 * @param string $forum_name Forum's name
 * @return int|boolean Number of posts
 */
function count_posts($forum_name) {
	global $db;
	
	// Make sure the forum exists
	if(forum_exists($forum_name)) {
		$posts_count = 0;
		$posts = sqlite_array_query($db, "
				SELECT `topic` 
				FROM `posts`
			", SQLITE_ASSOC);
		// Go through each topic to get the number of posts
		// << This could maybe be done entirely in SQL. >>
		foreach($posts as $post) {
			$result = sqlite_query($db, "
					SELECT `forum` 
					FROM `topics` 
					WHERE `id` = '{$post['topic']}'
				");
			if(sqlite_fetch_single($result) == $forum_name)
				$posts_count++;
		}
		return $posts_count;
	} else
		return false;
}

/**
 * Find out the last post from a forum
 * @global mixed Database connection
 * @param string $forum_name Forum's name
 * @return mixed Last post details
 */
function forum_last_post($forum_name) {
	global $db;
	
	// Always make sure the forum exists first
	if(forum_exists($forum_name)) {
		$posts = sqlite_array_query($db, "
				SELECT * 
				FROM `posts` 
				ORDER BY `timestamp` DESC
			", SQLITE_ASSOC);
		// Go through each post to find last one for this forum
		// << Again, easier way? >>
		foreach($posts as $post) {
			$result = sqlite_query($db, "
					SELECT `forum` 
					FROM `topics` 
					WHERE `id` = '{$post['topic']}'
				");
			if(sqlite_fetch_single($result) == $forum_name)
				return fancy_date($post['timestamp']);
		}
		return 'Never';
	} else
		return false;
}

/**
 * Get a user's last post
 * @global mixed Database connection
 * @param string $username Username
 * @return mixed Last post details
 */
function user_last_post($username) {
	global $db;
	
	$query = sqlite_query($db, "
			SELECT `timestamp` 
			FROM `posts` 
			WHERE `username` = '{$username}' 
			ORDER BY `timestamp` DESC 
			LIMIT 0, 1
		", SQLITE_ASSOC);
	$last_post = sqlite_fetch_single($query);
	if(empty($last_post))
		return 'Never';
	return fancy_date($last_post);
}

/**
 * Test a forum's existance
 * @global mixed Database connection
 * @param string $forum_name Name of forum
 * @return boolean Forum exists or not
 */
function forum_exists($forum_name) {
	global $db;
	
	$result = sqlite_query($db, "
			SELECT * 
			FROM `forums` 
			WHERE `id` = '{$forum_name}'
		", SQLITE_ASSOC);
	
	if(sqlite_fetch_single($result) == $forum_name)
		return true;
	else
		return false;
}

/**
 * Test a topic's existance
 * @global mixed Database connection
 * @param string $id Topic ID
 * @return boolean Topic exists or not
 */
function topic_exists($id) {
	global $db;
	
	$query = sqlite_query($db, "
			SELECT `id` 
			FROM `topics` 
			WHERE `id` = '{$id}' 
			LIMIT 0, 1
		", SQLITE_ASSOC);
	
	if(sqlite_fetch_single($query) == $id)
		return true;
	else
		return false;
}

/**
 * Test if a user is valid
 * @global mixed Database connection
 * @param string $username Username
 * @param string $password Password
 * @return boolean user is valid or not
 */
function valid_user($username, $password) {
	global $db;

	$result = sqlite_query($db, "
			SELECT `username`, `password` 
			FROM `users` 
			WHERE `username` = '{$username}'
		", SQLITE_ASSOC);
	$user = sqlite_fetch_array($result);

	if(!empty($user)) {
		if($username == $user['username'] and $password == $user['password'])
			return true;
		else
			return false;
	} else
		return false;
}

/**
 * See if a user is logged in
 * @return boolean Logged in or not
 */
function logged_in() {
	if(isset($_SESSION['username']) and isset($_SESSION['password'])) {
		// Test session for validity
		if(valid_user($_SESSION['username'], $_SESSION['password']))
			return true;
		else
			return false;
	} else
		return false;
}

/**
 * Generate a new password for random characters
 * @return string New password
 */
function generate_password() {
	// Character pool
	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	mt_srand(microtime() * 1000000);
	$password = '';
	// Generate 7 character password from pool
	for($i = 0; $i < 7; $i++)
		$password .= $chars{mt_rand(0, strlen($chars) - 1)};
	return $password;
}

/**
 * Count a user's posts
 * @global mixed Database connection
 * @param string $username Username
 * @return int Number of posts
 */
function count_user_posts($username) {
	global $db;
	
	$result = sqlite_query($db, "
			SELECT `id` 
			FROM `posts` 
			WHERE `username` = '{$username}'
		", SQLITE_ASSOC);
	return sqlite_num_rows($result);
}

/**
 * Count replies in a topic
 * @global mixed Database connection
 * @param string $topic Topic ID
 * @return int Replies
 */
function count_replies($topic) {
	global $db;
	
	$result = sqlite_query($db, "
			SELECT `id` 
			FROM `posts` 
			WHERE `topic` = '{$topic}'
		", SQLITE_ASSOC);
	$replies = sqlite_num_rows($result) - 1;
	if($replies < 0)
		$replies = 0;
	return $replies;
}

/**
 * Find a topic's last post
 * @global mixed Database connection
 * @global mixed Language support
 * @param string $topic Topic ID
 * @param string $format Format of last post
 * @return mixed Topic's last post
 */
function topic_last_post($topic, $format) {
	global $db, $strings;
	
	$query = sqlite_query($db, "
			SELECT * 
			FROM `posts` 
			WHERE `topic` = '{$topic}' 
			ORDER BY `timestamp` DESC 
			LIMIT 0, 1
		", SQLITE_ASSOC);
	$result = sqlite_fetch_array($query);
	
	switch($format) {
		case 1:
			// << Need error checking here? >>
			$last_post = $result['timestamp'];
			break;
		case 2:
			// Include author and link if logged in
			if(is_array($result)) {
				$last_post = fancy_date($result['timestamp']) . ' ' . $strings['by'] . ' ';
				if(logged_in())
					$last_post .= '<a href="user.php?u=' . $result['username'] . '">';
				$last_post .= $result['username'];
				if(logged_in())
					$last_post .= '</a>';
			} else
				$last_post = 'Never';
	}
	return $last_post;
}

/**
 * Fetch a topic's author
 * @global mixed Database connection
 * @param string $topic Topic ID
 * @return string Username
 */
function fetch_author($topic) {
	global $db;
	
	$query = sqlite_query($db, "
			SELECT `username` 
			FROM `posts` 
			WHERE `topic` = '{$topic}' 
			LIMIT 0, 1
		", SQLITE_ASSOC);
	return sqlite_fetch_single($query);
}

/**
 * Fetch a topic's forum
 * @global mixed Database connection
 * @param string $topic Topic ID
 * @return mixed Forum details
 */
function fetch_forum($topic) {
	global $db;
	
	// Get forum and pull details for it
	$query = sqlite_query($db, "
			SELECT `f`.`id`, `f`.`name`
			FROM `forums` `f`, `topics` `t`
			WHERE `t`.`id` = '{$topic}'
			AND `t`.`forum` = `f`.`id`
			LIMIT 0, 1
		", SQLITE_ASSOC);
	return = sqlite_fetch_single($query);
}

/**
 * Fetch info for a username
 * @global mixed Database connection
 * @param string $username Username
 * @return mixed User info
 */
function fetch_user_info($username) {
	global $db;
	
	// Validate username given
	// << Use preg instead. >>
	if(!ereg('^[a-z][a-z0-9]*$', $username))
		return false;
	$query = sqlite_query($db, "
			SELECT * 
			FROM `users` 
			WHERE `username` = '{$username}' 
			LIMIT 0, 1
		", SQLITE_ASSOC);
	return sqlite_fetch_array($query);
}

/**
 * Validate a username
 * @global mixed Language support
 * @param string $username Username
 */
function validate_username($username) {
	global $strings;
	
	// This returns nothing, but dies on an error
	if(strlen($username) < 3)
		die($strings['short_username']);
	if(!ereg('^[a-z][a-z0-9]*$', $username))
		die($strings['invalid_username']);
}

/**
 * Validate an email
 * @global mixed Language support
 * @param string $email Email address
 */
function validate_email($email) {
	global $strings;
	
	if(!eregi('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$', $email))
		die($strings['invalid_email']);
}

/**
 * Choose a user's title based on their type
 * @global mixed Language support
 * @param int $type User type
 * @return string Language string
 */
function select_title($type) {
	global $strings;
	
	switch($type) {
		case 2:
			return $strings['moderator'];
			break;
		case 3:
			return $strings['administrator'];
			break;
		case 1:
		default:
			return $strings['member'];
	}
}

/**
 * Validate a forum name
 * @param string $forum_name Forum name
 * @return boolean Valid or not
 */
function validate_forum($forum_name) {
	if(ereg('^[a-z][a-z0-9]*$', $forum_name))
		return true;
	else
		return false;
}

/**
 * Cerate a fancy date from a timestamp (e.g. yesterday, 2 mins ago etc.)
 * @global mixed General settings
 * @global int $time
 * @global mixed Language support
 * @param string $timestamp Timestamp to format
 * @return string Formatted date
 */
function fancy_date($timestamp) {
	global $settings, $time, $strings;
	
	// Get timestamps for today, yesterday and tomorrow to work with
	$yesterday = date('U', strtotime('yesterday'));
	$today = date('U', strtotime('today'));
	$tomorrow = date('U', strtotime('tomorrow'));
	
	// Base everything off the difference between the time now and the timestamp given
	$diff = $time - $timestamp;
	
	// Firstly, timestamp is between yesterday and today
	if($timestamp < $today and $timestamp >= $yesterday)
		return $strings['yesterday_at'] . ' ' . date('H:i:s', $timestamp);
	// Timestamp is greater than start of today and less than a minute before now
	elseif($timestamp > $today and $timestamp < $tomorrow and $diff > 0 and $diff <= 60) {
		if($diff == 1)
			return $strings['one_second_ago'];
		else
			return $diff.' '.$strings['seconds_ago'];
	// Timestamp is greater than start of today and less than an hour before now
	} elseif($timestamp > $today and $timestamp < $tomorrow and $diff > 0 and $diff <= 3540) {
		$mins = round($diff / 60);
		if($mins == 1)
			return $strings['one_min_ago']; 
		else
			return $mins.' '.$strings['mins_ago'];
	// Any other time frame today
	} elseif($timestamp > $today and $timestamp < $tomorrow)
		return $strings['today_at'] . ' ' . date('H:i:s', $timestamp);
	// Before yeterday, standard format
	elseif($timestamp < $yesterday)
		return date($settings['date_format'], $timestamp);
}

/**
 * Check the database health
 * @global string $database
 * @global mixed Database connection
 * @global $path Database Path to Monkey Boards
 * @return boolean Topic exists or not
 */
function check_health() {
	global $database, $db, $path;
	
	// Check database actually exists
	if(!file_exists($path . $database)) {
		if(file_exists($path . 'install.php')) {
			// If we're in admin, redirect back a directory
			if(strpos('admin', dirname($_SERVER['SCRIPT_NAME'])) !== false)
				header('Location: ../install.php');
			else
				header('Location: install.php');
		} else {
			// Install file missing!
			die('Fatal error: database and install script missing.');
		}
	} else {
		// Create database connection
		$db = sqlite_popen($path . $database)
			or die('Fatal error: could not open to database.');
		
		// Check tables exist
		$should_exist = array('categories', 'forums', 'posts', 'settings', 'topics', 'users');
		$total = count($should_exist);
		$found = 0;
		foreach($should_exist as $find) {
			$query = sqlite_query($db, "
					SELECT `name` 
					FROM `sqlite_master` 
					WHERE `type` = 'table' 
					AND `name` = '{$find}' 
					LIMIT 0, 1
				", SQLITE_ASSOC);
			if(sqlite_fetch_single($query) == $find)
				$found++;
		}
		if($found !== $total)
			exit('Fatal error: missing ' . ($total - $found) . ' crucial tables from database.');
	}

}

/**
 * Make links in a text string clickable
 * @param string $text Text string
 * @return string Clickable links in string
 */
function make_clickable($text) {
	// If we already don't have links
	if(!strstr($text, '<a')) {
		// << Use preg_replace instead! >>
		$text = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '<a href="\\1">\\1</a>', $text);
		$text = eregi_replace('([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '\\1<a href="http://\\2">\\2</a>', $text);
		$text = eregi_replace('([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})', '<a href="mailto:\\1">\\1</a>', $text);
	}
	return $text;
}

/**
 * Generate site info
 * @global mixed Database connection
 * @return mixed Site info
 */
 function site_info() {
	global $db;
	
	// Get number of users
	$query = sqlite_query($db, "
			SELECT * 
			FROM `users`
		", SQLITE_ASSOC);
	$site_info['users'] = sqlite_num_rows($query);
	
	// Get number of topics
	$query = sqlite_query($db, "
			SELECT * 
			FROM `topics`
		", SQLITE_ASSOC);
	$site_info['topics'] = sqlite_num_rows($query);
	
	// Get number of posts
	$query = sqlite_query($db, "
			SELECT * 
			FROM `posts`
		", SQLITE_ASSOC);
	$site_info['posts'] = sqlite_num_rows($query);
	return $site_info;
}

/**
 * Select language to use
 * @param string $accepted Accepted languages
 * @return string Language to use
 */
function select_lang($accepted) {
	$temp = explode(';', $accepted);
	$languages = explode(',', $temp[0]);
	$preferred = $languages[0];
	
	switch($preferred) {
		case 'fr':
			return 'fr';
			break;
		case 'en-gb':
		default:
			return 'en';
	}
}

/**
 * Generate a cipher (40 characters)
 * @return string Cipher
 */
function generate_cipher() {
	// Character pool
	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	mt_srand(microtime() * 1000000);	
	$cipher = '';
	for($i = 0; $i <= 40; $i++)
		$cipher .= $chars{mt_rand(0, strlen($chars) - 1)};
	return $cipher;
}
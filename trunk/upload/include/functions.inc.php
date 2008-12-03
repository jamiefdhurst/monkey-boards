<?php

session_start();

function db_open() {
	global $database, $db;
	$db = sqlite_popen($database, 0666, $db_error) or die($db_error);
}

function count_topics($forum_name) {
	global $db;

	if(forum_exists($forum_name)) {
		$result = sqlite_query($db, "SELECT id FROM topics WHERE forum = '$forum_name'", SQLITE_ASSOC);
		$topics_count = sqlite_num_rows($result);
		return($topics_count);
	}
	else {
		return false;
	}
	
}

function count_posts($forum_name) {
	global $db;

	if(forum_exists($forum_name)) {
		$posts_count = 0;
		$posts = sqlite_array_query($db, "SELECT topic FROM posts", SQLITE_ASSOC);
		foreach($posts as $post) {
			$topic = $post['topic'];
			$result = sqlite_query($db, "SELECT forum FROM topics WHERE id = '$topic'");
			$test = sqlite_fetch_single($result);
			if($test == $forum_name) $posts_count++;
		}
		return($posts_count);
	}
	else {
		return false;
	}
}

function forum_last_post($forum_name) {
	global $db;

	if(forum_exists($forum_name)) {
		$posts = sqlite_array_query($db, "SELECT * FROM posts ORDER BY timestamp DESC", SQLITE_ASSOC);
		foreach($posts as $post) {
			$topic = $post['topic'];
			$result = sqlite_query($db, "SELECT forum FROM topics WHERE id = '$topic'");
			$test = sqlite_fetch_single($result);
			if($test == $forum_name) {
				$last_post = fancy_date($post['timestamp']);
				return($last_post);
			}
		}
		return('Never');
	}
	else {
		return false;
	}
}

function user_last_post($username) {
	global $db, $settings;
	$query = sqlite_query($db, "SELECT timestamp FROM posts WHERE username = '$username' ORDER BY timestamp DESC LIMIT 0, 1", SQLITE_ASSOC);
	$last_post = sqlite_fetch_single($query);
	if(empty($last_post)) return('Never');
	return(fancy_date($last_post));
}

function forum_exists($forum_name) {
	global $db;

	$result = sqlite_query($db, "SELECT * FROM forums WHERE id = '$forum_name'", SQLITE_ASSOC);
	$test = sqlite_fetch_single($result);

	if($test == $forum_name) {
		return true;
	}
	else {
		return false;
	}
}

function topic_exists($id) {
	global $db;

	$query = sqlite_query($db, "SELECT id FROM topics WHERE id = '$id' LIMIT 0, 1", SQLITE_ASSOC);
	$result = sqlite_fetch_single($query);

	if($result == $id) {
		return true;
	}
	else {
		return false;
	}
}

function valid_user($username, $password) {
	global $db;

	$result = sqlite_query($db, "SELECT username, password FROM users WHERE username = '$username'", SQLITE_ASSOC);
	$user = sqlite_fetch_array($result);

	if(is_array($user)) {
		if($username == $user['username'] and $password == $user['password']) {
			return(true);
		}
		else {
			return(false);
		}
	}
	else {
		return false;
	}
}

function logged_in() {

if(isset($_SESSION['username']) and isset($_SESSION['password'])) {
	if(valid_user($_SESSION['username'], $_SESSION['password'])) {
		return true;
	}
	else {
		return false;
	}
}
else {
	return false;
}

}

function generate_password() {

$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
mt_srand(microtime() * 1000000);

$password = '';

	for($i = 0; $i < 7; $i++) {
		$key = mt_rand(0, strlen($chars) - 1);
		$password = $password . $chars{$key};
	}

return($password);

}

function count_user_posts($username) {

global $db;

$result = sqlite_query($db, "SELECT id FROM posts WHERE username = '$username'", SQLITE_ASSOC);
$posts = sqlite_num_rows($result);

return($posts);

}

function count_replies($topic) {

global $db;

$result = sqlite_query($db, "SELECT id FROM posts WHERE topic = '$topic'", SQLITE_ASSOC);
$replies = sqlite_num_rows($result) - 1;

if($replies < 0) $replies = 0;

return($replies);

}

function topic_last_post($topic, $format) {
	global $db, $strings;

	$query = sqlite_query($db, "SELECT * FROM posts WHERE topic = '$topic' ORDER BY timestamp DESC LIMIT 0, 1", SQLITE_ASSOC);
	$result = sqlite_fetch_array($query);

	switch($format) {
		case 1:
			$last_post = $result['timestamp'];
			break;
		case 2:
			if(is_array($result)) {
				$last_post = fancy_date($result['timestamp']).' '.$strings['by'].' ';
				if(logged_in()) { $last_post .= '<a href="user.php?u='.$result['username'].'">'; }
				$last_post .= $result['username'];
				if(logged_in()) { $last_post .= '</a>'; }
			}
			else {
				$last_post = 'Never';
			}
	}
	return($last_post);
}

function fetch_author($topic) {
	global $db;

	$query = sqlite_query($db, "SELECT username FROM posts WHERE topic = '$topic' LIMIT 0, 1", SQLITE_ASSOC);
	$result = sqlite_fetch_single($query);

	return($result);

}

function fetch_forum($topic) {
	global $db;

	$query = sqlite_query($db, "SELECT forum FROM topics WHERE id = '$topic' LIMIT 0, 1", SQLITE_ASSOC);
	$result = sqlite_fetch_single($query);

	$query = sqlite_query($db, "SELECT id , name FROM forums WHERE id = '$result' LIMIT 0, 1", SQLITE_ASSOC);
	$forum = sqlite_fetch_array($query);

	return($forum);
}

function fetch_user_info($username) {
	global $db;
	if(!ereg("^[a-z][a-z0-9]*$", $username)) return false;
	$query = sqlite_query($db, "SELECT * FROM users WHERE username = '$username' LIMIT 0, 1", SQLITE_ASSOC);
	$user = sqlite_fetch_array($query);
	return($user);
}

function validate_username($username) {
	global $strings;
	if(strlen($username) < 3) exit($strings['short_username']);
	if(!ereg("^[a-z][a-z0-9]*$", $username)) exit($strings['invalid_username']);
}

function validate_email($email) {
	global $strings;
	if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
		exit($strings['invalid_email']);
	}
	return;
}

function select_title($type) {
	global $strings;
	switch($type) {
		case 2:
			$type = $strings['moderator'];
		break;
		case 3:
			$type = $strings['administrator'];
		break;
		case 1:
		default:
			$type = $strings['member'];
		break;
	}
	return($type);
}

function validate_forum($forum_name) {
	if(ereg("^[a-z][a-z0-9]*$", $forum_name)) {
		return(true);
	}
	else {
		return(false);
	}
}

function fancy_date($timestamp) {
	global $settings, $time, $strings;
	$yesterday = date('U', strtotime('yesterday'));
	$today = date('U', strtotime('today'));
	$tomorrow = date('U', strtotime('tomorrow'));
	$diff = $time - $timestamp;
	if($timestamp < $today and $timestamp >= $yesterday) {
		$stamp = $strings['yesterday_at'].' '.date('H:i:s', $timestamp);
	}
	elseif($timestamp > $today and $timestamp < $tomorrow and $diff > 0 and $diff <= 60) {
		if($diff == 1) {
			$stamp = $strings['one_second_ago'];
		}
		else {
			$stamp = $diff.' '.$strings['seconds_ago'];
	}
	}
	elseif($timestamp > $today and $timestamp < $tomorrow and $diff > 0 and $diff <= 3540) {
		$mins = round($diff / 60);
		if($mins == 1) {
			$stamp = $strings['one_min_ago']; 
		}
		else {
			$stamp = $mins.' '.$strings['mins_ago'];
		}
	}
	elseif($timestamp > $today and $timestamp < $tomorrow) {
		$stamp = $strings['today_at'].' '.date('H:i:s', $timestamp);
	}
	elseif($timestamp < $yesterday) {
		$stamp = date($settings['date_format'], $timestamp);
	}
	return($stamp);
}

/* function current_page() {
	global $_SERVER;
	$temp = explode('/', $_SERVER['PHP_SELF']);
	$count = count($temp);
	return($temp[$count - 1]);
} */

function check_health() {

global $database, $db, $path;

// check database exists
if(!file_exists($path.$database)) {
	if(file_exists($path.'install.php')) {
		if(ereg('admin', dirname($_SERVER["SCRIPT_NAME"]))) {
			header('Location: ../install.php');
		}
		else {
			header('Location: install.php');
		}
	}
	else {
		exit('Fatal error: database and install script missing.');
	}
}
else {
	$db = sqlite_popen($path.$database) or die('Fatal error: could not open to database.');

	// check tables exist
	$should_exist = array('categories', 'forums', 'posts', 'settings', 'topics', 'users');
	$total = count($should_exist);
	$found = 0;
	foreach($should_exist as $find) {
		$query = sqlite_query($db, "SELECT name FROM sqlite_master WHERE type='table' AND name = '$find' LIMIT 0, 1", SQLITE_ASSOC);
		$table = sqlite_fetch_single($query);
		if($table == $find) $found++;
	}
	if($found !== $total) {
		$missing = $total - $found;
		exit('Fatal error: missing '.$missing.' crucial tables from database.');
	}

/*	// check installed flag set
	$query = sqlite_query($db, "SELECT value FROM settings WHERE id = 'installed' LIMIT 0, 1", SQLITE_ASSOC);
	$installed = sqlite_fetch_single($query);
	if(empty($installed) or $installed != 1) {
		if(file_exists($path.'install.php')) {
			header('Location: install.php');
		}
		else {
			exit('Fatal error: install script missing.');
		}
	}
	else {
		if(file_exists($path.'install.php')) exit('Please delete the install script.');
	} */
}

}

function make_clickable($text) {
	if(!strstr($text, '<a')) {
		$text = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '<a href="\\1">\\1</a>', $text);
		$text = eregi_replace('([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '\\1<a href="http://\\2">\\2</a>', $text);
		$text = eregi_replace('([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})', '<a href="mailto:\\1">\\1</a>', $text);
	}
	return($text);
}

/* function get_path() {
	global $_SERVER;
	$temp = explode("/", $_SERVER['PHP_SELF']);
	$count = count($temp) - 1;
	unset($temp[0]);
	unset($temp[$count]);
	$temp = implode("/", $temp).'/';
	$temp = eregi_replace('admin/', '', $temp);
	return($_SERVER['DOCUMENT_ROOT'].'/'.$temp);
} */

function site_info() {
	global $db;

	$query = sqlite_query($db, "SELECT * FROM users", SQLITE_ASSOC);
	$site_info['users'] = sqlite_num_rows($query);

	$query = sqlite_query($db, "SELECT * FROM topics", SQLITE_ASSOC);
	$site_info['topics'] = sqlite_num_rows($query);

	$query = sqlite_query($db, "SELECT * FROM posts", SQLITE_ASSOC);
	$site_info['posts'] = sqlite_num_rows($query);

	return($site_info);
}

function select_lang($accepted) {
	$temp = explode(';', $accepted);
	$languages = explode(',', $temp[0]);
	$preferred = $languages[0];

	switch($preferred) {
		case "fr":
			$lang = 'fr';
			break;
		case "en-gb":
		default:
			$lang = 'en';
			break;
	}

	return($lang);
}

function generate_cipher() {

$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
mt_srand(microtime() * 1000000);

$cipher = '';

        for($i = 0; $i <= 40; $i++) {
                $key = mt_rand(0, strlen($chars) - 1);
                $cipher = $cipher . $chars[$key];
        }

return($cipher);

}

?>

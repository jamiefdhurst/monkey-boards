<?php

/**
 * Monkey Boards
 * /include/start.inc.php
 * Default include file
 * 
 * @package MonkeyBoards/include
 * @version 1.0
 * @author Jamie Hurst
 */

// Require functions and config files
require('functions.inc.php');
require('config.inc.php');

// Disable all error reporting
error_reporting(0);

// Set global version number
//$app_version = '0.4';
$app_version = '1.0';

// Generate the path
$path = dirname(eregi_replace('include', '', __FILE__)) . '/';

// Get current page name and check the database health
$current_page = basename($_SERVER['PHP_SELF']);
if($current_page !== 'install.php')
	check_health();

// Get the template object
require($path . 'include/engine/class.template.php');
$tpl = new Template_Lite;

// Set up template engine
$tpl->compile_dir = $path . 'include/engine/compiled';
$tpl->template_dir = $path . 'include/templates';
$tpl->assign('path', $path);
$tpl->assign('app_version', $app_version);

// Generate language based on what we've been given
$tpl->assign('lang', select_lang($_SERVER['HTTP_ACCEPT_LANGUAGE']));

// Check for missing files and assign language support
if(!include('langs/' . $lang . '.php'))
	die('Fatal error: missing language file');
$tpl->assign('strings', $strings);

// Import the get and post variables using the stripping engine
import_request_variables('gp', 'v_');

// << This is daft. >>
$time = time();

// If we aren't installing
if($current_page !== 'install.php') {
	
	// Assign current page
	$tpl->assign('current_page', $current_page);

	$query = sqlite_query($db, "
			SELECT * 
			FROM `settings`
		", SQLITE_ASSOC);
	$result = sqlite_fetch_all($query);
	
	// Set up $ettings
	foreach($result as $setting) {
		$index = ;
		$value = $setting['value'];
		if($setting['preserve'] == 0)
			$value = htmlentities($setting['value']);
		$settings[$setting['id']] = $value;
		$tpl->assign($setting['id'], $value);
	}
	
		// Set up menus
	$main_menu = array(
			array(
					'item' 		=> 	$strings['home'],
					'link' 		=> 	'index.php', 
					'display' 	=> 	0
				),
			array(
					'item' 		=> 	$strings['user_list'], 
					'link' 		=> 	'userlist.php', 
					'display' 	=> 	1
				),
			array(
					'item' 		=> 	$strings['rules'], 
					'link' 		=> 	'rules.php', 
					'display' 	=> 	0
				),
			array(
					'item' 		=> 	$strings['search'], 
					'link' 		=> 	'search.php', 
					'display' 	=> 	0
				),
			array(
					'item' 		=> 	$strings['register'], 
					'link' 		=> 	'register.php', 
					'display' 	=> 	2
				),
			array(
					'item' 		=> 	$strings['login'], 
					'link' 		=> 	'login.php', 
					'display' 	=> 	2
				),
			array(
					'item' 		=> 	$strings['profile'], 
					'link' 		=> 	'profile.php', 
					'display' 	=> 	1
				),
			array(
					'item' 		=>	$strings['admin'], 
					'link' 		=> 	'admin/', 
					'display' 	=> 	3
				),
			array(
					'item' 		=> 	$strings['logout'], 
					'link' 		=> 	'login.php?mode=logout', 
					'display' 	=> 	1
				)
		);
	
	$admin_menu = array(
			array(
					'item' 	=> 	$strings['index'], 
					'link' 	=> 	'index.php'
				),
			array(
					'item' 	=> 	$strings['categories'], 
					'link' 	=> 	'categories.php'
				),
			array(
					'item' 	=> 	$strings['forums'], 
					'link' 	=> 	'forums.php'
				),
			array(
					'item' 	=> 	$strings['users'], 
					'link' 	=> 	'users.php'
				),
			array(
					'item' 	=> 	$strings['settings'], 
					'link' 	=> 	'settings.php'
				)
		);
	
	// Assign menus
	$tpl->assign('main_menu', $main_menu);
	$tpl->assign('admin_menu', $admin_menu);
	
	// Fetch user info or log in info
	if(logged_in()) {
		$tpl->assign('logged_in', 'true');
		$user = fetch_user_info($_SESSION['username']);
		
		$user_type = $user['type'];
		$tpl->assign('user_type', $user_type);
		
		$tpl->assign('username', $_SESSION['username']);
	} else {
		$tpl->assign('logged_in', 'false');
		
		$user_type = 1;
		$tpl->assign('user_type', $user_type);
	}
}
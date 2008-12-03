<?php

require('functions.inc.php');
require('config.inc.php');

// Disable all error reporting
error_reporting(0);

// Set global version number
$app_version = '0.4';

$temp_path = eregi_replace('include', '', __FILE__);
$path = dirname($temp_path).'/';

$current_page = basename($_SERVER['PHP_SELF']);
if($current_page !== "install.php") check_health();

require($path.'include/engine/class.template.php');
$tpl = new Template_Lite;

$tpl->compile_dir = $path.'include/engine/compiled';
$tpl->template_dir = $path.'include/templates';

$tpl->assign('path', $path);
$tpl->assign('app_version', $app_version);

$lang = select_lang($_SERVER['HTTP_ACCEPT_LANGUAGE']);
$tpl->assign('lang', $lang);

if(!include('langs/'.$lang.'.php')) die('Fatal error: missing language file');
$tpl->assign('strings', $strings);

import_request_variables('gp', 'v_');
$time = time();

if($current_page !== "install.php") {

	$tpl->assign('current_page', $current_page);

	$query = sqlite_query($db, "SELECT * FROM settings", SQLITE_ASSOC);
	$result = sqlite_fetch_all($query);

	foreach($result as $setting) {
		$index = $setting['id'];
		if($setting['preserve'] == 0) {
			$value = htmlentities($setting['value']);
		}
		else {
			$value = $setting['value'];
		}
		$settings[$index] = $value;
		$tpl->assign($index, $value);
	}

$main_menu = array(
	array('item' => $strings['home'], 'link' => 'index.php', 'display' => 0),
	array('item' => $strings['user_list'], 'link' => 'userlist.php', 'display' => 1),
	array('item' => $strings['rules'], 'link' => 'rules.php', 'display' => 0),
	array('item' => $strings['search'], 'link' => 'search.php', 'display' => 0),
	array('item' => $strings['register'], 'link' => 'register.php', 'display' => 2),
	array('item' => $strings['login'], 'link' => 'login.php', 'display' => 2),
	array('item' => $strings['profile'], 'link' => 'profile.php', 'display' => 1),
	array('item' => $strings['admin'], 'link' => 'admin/', 'display' => 3),
	array('item' => $strings['logout'], 'link' => 'login.php?mode=logout', 'display' => 1)
);

$admin_menu = array(
	array('item' => $strings['index'], 'link' => 'index.php'),
	array('item' => $strings['categories'], 'link' => 'categories.php'),
	array('item' => $strings['forums'], 'link' => 'forums.php'),
	array('item' => $strings['users'], 'link' => 'users.php'),
	array('item' => $strings['settings'], 'link' => 'settings.php')
);

$tpl->assign('main_menu', $main_menu);
$tpl->assign('admin_menu', $admin_menu);

	if(logged_in()) {
		$tpl->assign('logged_in', 'true');
		$user = fetch_user_info($_SESSION['username']);

		$user_type = $user['type'];
		$tpl->assign('user_type', $user_type);

		$tpl->assign('username', $_SESSION['username']);
	}
	else {
		$tpl->assign('logged_in', 'false');

		$user_type = 1;
		$tpl->assign('user_type', $user_type);
	}
}

?>

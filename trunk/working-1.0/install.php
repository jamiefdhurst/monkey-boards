<?php

// Require standard config file
include('include/config.inc.php');

// If we have a database, relocate back to index
if(file_exists($database))
	header('Location: ./');

$schema = file_get_contents('includes/install.sql');
if(!$schema)
	die('Could not find schema installation file, please re-download Monkey Boards.');

// Require standard start file
require('include/start.inc.php');

// Check through some PHP environment variables to make sure we have the required settings
// Such as PHP version 5.0, SQLite extension and Magic Quotes
if(version_compare(phpversion(), "5.0.0") < 0)
	$install_errors[] = '<strong>PHP version:</strong> You must have PHP version 5.0.0 or greater to use this script.';
if(!function_exists('sqlite_open')) 
	$install_errors[] = '<strong>SQLite support:</strong> You do not appear to have SQLite support. To use this script you need to load the module in PHP.';
if(get_magic_quotes_gpc()) $install_errors[] = '<strong>Magic quotes:</strong> To use this script <var>magic_quotes_gpc</var> <strong>must</strong> be set to <var>off</var> in the php.ini configuration file.';

// Assign the install errors
if(isset($install_errors))
	$tpl->assign('install_errors', $install_errors);
else
	$tpl->assign('install_errors', 'none');

// If we have the database POST data, do the POST
if(!empty($v_database)) {
	
	// Get the URI string and turn it into a base directory
	$uri = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$base_address = dirname($uri);
	
	// Check for an invalid DB name
	if(!ereg("^[a-z][a-z0-9-]*$", $v_database))
		die('Database name invalid. Alphanumeric characters only.');
	
	// Validate the given username, password, email and site name
	validate_username($v_username);
	if(empty($v_password) or empty($v_cpassword))
		die('Password missing. Please go back and type your password twice.');
	if($v_password !== $v_cpassword)
		die('Passwords do not match. Please go back and try again.');
	if(empty($v_email))
		die('Email address missing. Please go back and type your email address.');
	validate_email($v_email);
	if(empty($v_site_title))
		die('Please go back and type a site title.');
	$v_site_title = sqlite_escape_string($v_site_title);
	
	// Include the currently non-existant database
	$db_path = 'include/'.$v_database.'.db';
	if(file_exists($db_path))
		die('Database already in use. Please go back and choose another.');
	
	// Check for access to the blank file
	$db = sqlite_open($db_path) or
		die('Could not open or create database file: ' . $db_path . '. Check write permissions.');
	
	// Go through the schema and execute it
	$lines = count($schema);
	$schema = explode("\r\n", $schema);
	$count = 0;
	foreach($schema as $line) {
		$count++;
		$query = sqlite_exec($db, $line) or
			die("Error executing line $count of $lines");
	}
	
	// Hash the password using SHA1
	$hashed_password = sha1($v_password);
	$create_admin_user = sqlite_exec($db, "
			INSERT INTO `users` 
			VALUES (
				'{$v_username}', 
				'{$hashed_password}', 
				'{$v_email}', 
				'3', 
				'0', 
				'{$time}', 
				NULL
			);
		");
	
	// Final insertion query
	$insert = "
		INSERT INTO `settings` 
		VALUES (
			'site_title', 
			'{$v_site_title}', 
			'1'
		);
		INSERT INTO `settings` 
		VALUES (
			'base_address', 
			'{$base_address}', 
			'0'
		);
		INSERT INTO `categories` 
		VALUES ( 
			NULL, 
			'Test Category'
		);
		INSERT INTO `forums`
		VALUES (
			'test', 
			'1', 
			'Test Forum', 
			'This is a test forum.'
		);
		INSERT INTO `topics` 
		VALUES (
			'1', 
			'test', 
			'Test post', 
			'2', 
			'0', 
			'0'
		);
		INSERT INTO `posts` 
		VALUES (
			'1', 
			'1', 
			'{$v_username}', 
			'{$time}', 
			'If you are looking at this, the installation of Monkey Boards appears to have worked! Now log in and head over to the administration control panel to configure your forum.'
		);
	";
	$query = sqlite_exec($db, $insert);
	
	// Write the config file with database setting
	file_put_contents('include/config.inc.php', '<?php $database = \'' . $db_path . '\';');
	
	// Redirect to forum index
	header('Location: ./index.php');
}

// Assign template settings and display
$tpl->assign('style', 'default');
$tpl->display("install.tpl");
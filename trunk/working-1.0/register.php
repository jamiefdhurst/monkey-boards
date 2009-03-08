<?php

/**
 * Monkey Boards
 * /register.php
 * Register a new user on the forums
 * 
 * @package MonkeyBoards/main
 * @version 1.0
 * @author Jamie Hurst
 */

 // Parse a string with str_replace
 // << This needs removing, str_replace supports arrays! >>
function parse($string, $parsed) {
	foreach($parsed as $find => $replace)
		$string = str_replace($find, $replace, $string);
	return($string);
}

// Require standard start file
require('include/start.inc.php');

// If we have POST data
if(!empty($v_username) and !empty($v_email)) {

	// Validate username and email address entered
	validate_username($v_username);
	validate_email($v_email); 

	// Generate password and hash it
	$password = generate_password();
	$hashed_password = sha1($password);

	// Check username or email address not already in use
	$result = sqlite_query($db, "
			SELECT username
			FROM `users` 
			WHERE `username` = '{$v_username}' 
			OR email = '{$v_email}'
		", SQLITE_ASSOC);
	$user = sqlite_fetch_array($result);
	if(is_array($user))
		die($strings['user_exists']);

	// Parse subject and message for email
	$parsed = array(
			'{site_title}' 	=> 	$settings['site_title'],
			'{username}' 	=> 	$v_username,
			'{password}' 	=> 	$password
		);
	$subject = parse($strings['welcome_email_subject'], $parsed);
	$message = parse($strings['welcome_email_message'], $parsed);

	// Register the new user
	$register = sqlite_exec($db, "
			INSERT INTO `users` 
			VALUES (
				'{$v_username}', 
				'{$hashed_password}', 
				'{$v_email}', 
				'1', 
				'0', 
				'{$time}', 
				NULL
			)
		");
	if(!$register)
		die($strings['db_error']);

	// Mail out the welcome message
	$mail = mail($v_email, $subject, $message, 'From: ' . $settings['email_from']);
	if(!$mail)
		die($strings['mail_error']);

	// Output success message
	// << Need a template here. >>
	die($strings['register_success']);

}

// Display registration template
$tpl->display('register.tpl');
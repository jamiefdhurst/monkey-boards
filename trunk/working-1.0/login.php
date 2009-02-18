<?php

/**
 * Monkey Boards
 * /login.php
 * Perform user login
 * 
 * @version 1.0
 * @author Jamie Hurst
 */

// Require standard start file
require('include/start.inc.php');

// If we have POST data, act upon on it
if(isset($v_mode)) {
	// Assign the mode to the template
	$tpl->assign('mode', $v_mode);
	
	// Act upon the mode
	switch($v_mode) {
		case "reset":
			// Reset the login
			// We need username and email address
			if(!empty($v_username) and !empty($v_email) and isset($v_submit)) {
				// Get the user data
				$query = sqlite_query($db, "
						SELECT * 
						FROM `users` 
						WHERE `username` = '{$v_username}' 
						AND `email` = '{$v_email}' 
						LIMIT 0, 1
					", SQLITE_ASSOC);
				$user = sqlite_fetch_array($query);
				// If the user is empty, redirect to the forgotten password screen
				if(empty($user))
					header('Location: ./login.php?mode=forgot');
				
				// Generate a new password, hash it and store in database
				$password = generate_password();
				$hashed_password = sha1($password);
				$update = sqlite_exec($db, "
						UPDATE `users` SET 
							`password` = '{$hashed_password}' 
						WHERE `username` = '{$v_username}'
					");
				// Email user with new password
				$mail = mail($v_email, $settings['site_title'].': New password', 'Your new password is ' . $password, 'From: ' . $settings['email_from']);
				if(!$mail)
					die($settings['msg_mail_error']);
				// << This is a bit inefficient, could do with a template really. >>
				die('Your new password has been emailed to you. Please check your inbox.');
			} elseif(isset($v_cancel)) {
				// Cancel action
				header('Location: ./login.php');
			} else
				header('Location: ./login.php?mode=forgot');
			break;
		case "forgot":
			// For forgotten mode, display forgot template
			$tpl->display('login.tpl');
			exit;
			break;
		case "logout":
			// Destroy session and return user
			session_destroy();
			header('Location: ./');
			break;
	}
} elseif(logged_in())
	// If user is logged in, redirect them back
	header('Location: ./');

// If we have a username and password, perform a login
if(!empty($v_username) and !empty($v_password)) {
	// Validate username
	validate_username($v_username);
	
	// Generate password hash
	$hashed_password = sha1($v_password);
	
	// Check if valid user
	if(valid_user($v_username, $hashed_password)) {
		// Check if banned
		$query = sqlite_query($db, "
				SELECT `disabled` 
				FROM `users` 
				WHERE `username` = '{$v_username}' 
				LIMIT 0, 1
			", SQLITE_ASSOC);
		$disabled = sqlite_fetch_single($query);
		if($disabled) 
			die($strings['user_banned']);

		// We're OK, log user in
		$_SESSION['username'] = $v_username;
		$_SESSION['password'] = $hashed_password;
		header('Location: ./');
	} else
		die($strings['invalid_login']);
}

// Display login template
$tpl->display('login.tpl');
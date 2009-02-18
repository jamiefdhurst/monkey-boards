<?php

/**
 * Monkey Boards
 * /profile.php
 * Display profile and handle
 * 
 * @version 1.0
 * @author Jamie Hurst
 */

// Require standard start file
require('include/start.inc.php');

// A non-logged in user can't edit their profile, so redirect them
if(!logged_in())
	header('Location: ./');

// If we have something to update in database
if(!empty($v_name) or !empty($v_email) or (!empty($v_newpw) and !empty($v_newpw2))) {
	// Edit the username
	if(!empty($v_name)) {
		$update = sqlite_exec($db, "
				UPDATE `users` SET 
					`name` = '{$v_name}' 
				WHERE `username` = '{$user['username']}';
			");
	}
	// Edit email address
	if(!empty($v_email)) {
		validate_email($v_email);
		$update = sqlite_exec($db, "
				UPDATE `users` SET 
					`email` = '{$v_email}' 
				WHERE `username` = '{$user['username']}'
			");
	}
	// After verifying password, edit password
	// << Need some sort of error message here if there is a problem. >>
	if(!empty($v_newpw) and !empty($v_newpw2) and $v_newpw == $v_newpw2) {
		$hashed = sha1($v_newpw);
		$update = sqlite_exec($db, "
				UPDATE `users` SET 
					`password` = '{$hashed}' 
				WHERE `username` = '{$user['username']}'
			");
		// Update session password too
		$_SESSION['password'] = $hashed;
	}
	// Redirect away from POST data
	header('Location: profile.php');
}

// Pull user data from database
$query = sqlite_query($db, "
		SELECT `username`, `password`, `email`, `name` 
		FROM `users` 
		WHERE `username` = '{$user['username']}'
	", SQLITE_ASSOC);
$user = sqlite_fetch_array($query);

// Assign values and display template
$tpl->assign('user', $user);
$tpl->display('profile.tpl');
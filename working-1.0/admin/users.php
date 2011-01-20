<?php

/**
 * Monkey Boards
 * /admin/users.php
 * Manage forum users
 * 
 * @package MonkeyBoards/admin
 * @version 1.0
 * @author Jamie Hurst
 */

// Require standard start file and admin authentication library
require('../include/start.inc.php');
require('../include/admin_auth.inc.php');

// If we have an action to complete
if(!empty($v_action)) {
	// Get user details if we have them
	if(isset($v_user)) {
		$query = sqlite_query($db, "
				SELECT * 
				FROM `users` 
				WHERE `username` = '{$v_user}'
			", SQLITE_ASSOC);
		$user = sqlite_fetch_array($query);
		// Assign to template
		$tpl->assign('user', $user);
	}
	// Based on action...
	switch($v_action) {
		// Ban a user
		case "ban":
			$ban = sqlite_exec($db, "
					UPDATE `users` 
					SET 
						`disabled` = '1' 
					WHERE `username` = '{$v_user}'
				");
			// Redirect back
			header('Location: users.php');
			break;
		// Delete a user
		case "delete":
			$delete = sqlite_exec($db, "
					DELETE FROM `users` 
					WHERE `username` = '{$v_user}'
				");
			// Redirect back
			header('Location: users.php');
			break;
		// Confirm deletion or start editing
		case "delete_confirm":
		case "edit":
			// Get admins
			$query = sqlite_query($db, "
					SELECT * 
					FROM `users` 
					WHERE `type` = '3'
				", SQLITE_ASSOC);
			$admins = sqlite_fetch_all($query);
			// Assign number of admins to template
			// << What is this for? >>
			$tpl->assign('admins_count', count($admins));
			break;
		// Change user's password
		case "password":
			// Redirect back if no password
			if(empty($v_password))
				header('Location: users.php');
			// Hash the password
			$hashed_password = sha1($v_password);
			// Update the database
			$update = sqlite_exec($db, "
					UPDATE `users` 
					SET 
						`password` = '{$hashed_password}' 
					WHERE `username` = '{$v_user}'
				");
			// Redirect back
			header('Location: users.php');
			break;
		// Save a user's details
		case "save":
			// If this user's name isn't the same, change post authors
			if($v_user !== $v_username)
				$posts = sqlite_exec($db, "
						UPDATE `posts` 
						SET
							`username` = '{$v_username}' 
						WHERE `username` = '$v_user'
					");
			$update = sqlite_exec($db, "
					UPDATE `users` 
					SET 
						`username` = '{$v_username}', 
						`type` = '{$v_type}', 
						`email` = '{$v_email}' 
					WHERE `username` = '{$v_user}'
				");
			// Redirect back
			header('Location: users.php');
			break;
		// Unban user
		case "unban":
			$unban = sqlite_exec($db, "
					UPDATE `users` 
					SET 
						`disabled` = '0' 
					WHERE `username` = '{$v_user}'
				");
			header('Location: users.php');
			break;
	}
	// Assign the action to the template
	$tpl->assign('action', $v_action);
}

// Get all users for template assignment
$query = sqlite_query($db, "
		SELECT * 
		FROM `users`
	", SQLITE_ASSOC);
$users = sqlite_fetch_all($query);
$tpl->assign('users_count', count($users));

// Get the title for each user
foreach($users as $key => $user)
	$users[$key]['title'] = select_title($users[$key]['type']);

// Assign and display
$tpl->assign('users', $users);
$tpl->display('admin_users.tpl');

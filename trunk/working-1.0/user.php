<?php

/**
 * Monkey Boards
 * /user.php
 * View a user's profile
 * 
 * @version 1.0
 * @author Jamie Hurst
 */

// Require standard start file
require('include/start.inc.php');

// Must be logged in
if(!logged_in())
	header('Location: ./');

$query = sqlite_query($db, "
		SELECT * 
		FROM `users` 
		WHERE `username` = '{$v_u}'
	", SQLITE_ASSOC);
$user = sqlite_fetch_array($query);

// Sort out user info
$user['title'] = select_title($user['type']);
$user['posts'] = count_user_posts($user['username']);
$user['registered'] =  fancy_date($user['registered']);
$user['last_post'] = user_last_post($user['username']);

// Assign and display
$tpl->assign('user', $user);
$tpl->display('user.tpl');
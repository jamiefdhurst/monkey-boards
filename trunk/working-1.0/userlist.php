<?php

/**
 * Monkey Boards
 * /userlist.php
 * Display list of all forum users
 * 
 * @package MonkeyBoards/main
 * @version 1.0
 * @author Jamie Hurst
 */

// Require standard start file
require('include/start.inc.php');

// Must be logged in
if(!logged_in())
	header('Location: ./');

// Fetch users
$query = sqlite_query($db, "
		SELECT * 
		FROM `users` 
		ORDER BY `username` ASC
	", SQLITE_ASSOC);
$users = sqlite_fetch_all($query);

// Count the users
$users_count = count($users);
$tpl->assign('users_count', $users_count);

// Sort out each user
foreach($users as $key => $user) {
	$users[$key]['title'] = select_title($users[$key]['type']);
	$users[$key]['posts'] = count_user_posts($users[$key]['username']);
	$users[$key]['registered'] = fancy_date($users[$key]['registered']);
}

// Assign and display
$tpl->assign('users', $users);
$tpl->display('userlist.tpl');
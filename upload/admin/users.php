<?php

require('../include/start.inc.php');
require('../include/admin_auth.inc.php');

if(!empty($v_action)) {
	if(isset($v_user)) {
		$query = sqlite_query($db, "SELECT * FROM users WHERE username = '$v_user'", SQLITE_ASSOC);
		$user = sqlite_fetch_array($query);
		$tpl->assign('user', $user);
	}

	switch($v_action) {
		case "ban":
			$ban = sqlite_exec($db, "UPDATE users SET disabled = '1' WHERE username = '$v_user'");
			header('Location: users.php');
		break;
		case "delete":
			$delete = sqlite_exec($db, "DELETE FROM users WHERE username = '$v_user'");
			header('Location: users.php');
		break;
		case "delete_confirm":
		case "edit":
			$query = sqlite_query($db, "SELECT * FROM users WHERE type = '3'", SQLITE_ASSOC);
			$admins = sqlite_fetch_all($query);
			$tpl->assign('admins_count', count($admins));
		break;
		case "password":
			if(empty($v_password)) header('Location: users.php');
			$hashed_password = sha1($v_password);
			$update = sqlite_exec($db, "UPDATE users SET password = '$hashed_password' WHERE username = '$v_user'");
			header('Location: users.php');
		break;
		case "save":
			if($v_user !== $v_username) {
				$posts = sqlite_exec($db, "UPDATE posts SET username = '$v_username' WHERE username = '$v_user'");
			}
			$update = sqlite_exec($db, "UPDATE users SET username = '$v_username', type = '$v_type', email = '$v_email' WHERE username = '$v_user'");
			header('Location: users.php');
		break;
		case "unban":
			$unban = sqlite_exec($db, "UPDATE users SET disabled = '0' WHERE username = '$v_user'");
			header('Location: users.php');
		break;
	}
	$tpl->assign('action', $v_action);
}

$query = sqlite_query($db, "SELECT * FROM users", SQLITE_ASSOC);
$users = sqlite_fetch_all($query);

$users_count = count($users);
$tpl->assign('users_count', $users_count);

foreach($users as $key => $user) {
	$users[$key]['title'] = select_title($users[$key]['type']);
}

$tpl->assign('users', $users);
$tpl->display("admin_users.tpl");

?>
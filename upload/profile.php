<?php

require('include/start.inc.php');
if(!logged_in()) header('Location: ./');

if(!empty($v_name) or !empty($v_email) or (!empty($v_newpw) and !empty($v_newpw2))) {
	if(!empty($v_name)) {
		$update = sqlite_exec($db, "UPDATE users SET name = '".$v_name."' WHERE username = '".$user['username']."';");
	}
	if(!empty($v_email)) {
		validate_email($v_email);
		$update = sqlite_exec($db, "UPDATE users SET email = '".$v_email."' WHERE username = '".$user['username']."'");
	}
	if(!empty($v_newpw) and !empty($v_newpw2) and $v_newpw == $v_newpw2) {
		$hashed = sha1($v_newpw);
		$update = sqlite_exec($db, "UPDATE users SET password = '".$hashed."' WHERE username = '".$user['username']."'");
		$_SESSION['password'] = $hashed;
	}
	header('Location: profile.php');
	exit;
}

$query = sqlite_query($db, "SELECT username, password, email, name FROM users WHERE username = '".$user['username']."'", SQLITE_ASSOC);
$user = sqlite_fetch_array($query);

$tpl->assign('user', $user);
$tpl->display("profile.tpl");

?>

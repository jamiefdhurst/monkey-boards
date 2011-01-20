<?php

require('include/start.inc.php');
if(isset($v_mode)) {
	$tpl->assign('mode', $v_mode);
	switch($v_mode) {
		case "reset":
			if(!empty($v_username) and !empty($v_email) and isset($v_submit)) {
				$query = sqlite_query($db, "SELECT * FROM users WHERE username = '$v_username' AND email = '$v_email' LIMIT 0, 1", SQLITE_ASSOC);
				$user = sqlite_fetch_array($query);
				if(empty($user)) header('Location: login.php?mode=forgot');

				$password = generate_password();
				$hashed_password = sha1($password);
				$update = sqlite_exec($db, "UPDATE users SET password = '$hashed_password' WHERE username = '$v_username'");

				$mail = mail($v_email, $settings['site_title'].': New password', 'Your new password is '.$password, 'From: '.$settings['email_from']);
				if(!$mail) exit($settings['msg_mail_error']);
				exit('Your new password has been emailed to you. Please check your inbox.');
			}
			elseif(isset($v_cancel)) {
				header('Location: login.php');
				exit;
			}
			else { header('Location: login.php?mode=forgot'); }
		break;
		case "forgot":
			$tpl->display('login.tpl');
			exit;
		break;
		case "logout":
			session_destroy();
			header('Location: ./');
		break;
	}
}
elseif(logged_in()) header('Location: ./');

if(!empty($v_username) and !empty($v_password)) {
	// validate username
	validate_username($v_username);

	// generate password hash
	$hashed_password = sha1($v_password);

	// check if valid user
	if(valid_user($v_username, $hashed_password)) {
		// check if banned
		$query = sqlite_query($db, "SELECT disabled FROM users WHERE username = '$v_username' LIMIT 0, 1", SQLITE_ASSOC);
		$disabled = sqlite_fetch_single($query);
		if($disabled == 1) exit($strings['user_banned']);

		// log in user
		$_SESSION['username'] = $v_username;
		$_SESSION['password'] = $hashed_password;
		header('Location: ./');
	}
	else {
		exit($strings['invalid_login']);
	}
}

$tpl->display("login.tpl");

?>

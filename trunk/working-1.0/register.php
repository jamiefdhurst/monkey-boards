<?php

function parse($string, $parsed) {
	foreach($parsed as $find => $replace) {
		$string = str_replace($find, $replace, $string);
	}
	return($string);
}

require('include/start.inc.php');

if(!empty($v_username) and !empty($v_email)) {

	// validate username and email address entered
	validate_username($v_username);
	validate_email($v_email); 

	// generate password and hash it
	$password = generate_password();
	$hashed_password = sha1($password);

	// check username or email address not already in use
	$result = sqlite_query($db, "SELECT * FROM users WHERE username = '$v_username' OR email = '$v_email'", SQLITE_ASSOC);
	$user = sqlite_fetch_array($result);
	if(is_array($user)) exit($strings['user_exists']);

	// parse subject and message for email
	$parsed = array(
		'{site_title}' => $settings['site_title'],
		'{username}' => $v_username,
		'{password}' => $password
	);

	$subject = parse($strings['welcome_email_subject'], $parsed);
	$message = parse($strings['welcome_email_message'], $parsed);

	// mail the welcome message
	$mail = mail($v_email, $subject, $message, 'From: '.$settings['email_from']);
	if(!$mail) exit($strings['mail_error']);

	// register the new user
	$register = sqlite_exec($db, "INSERT INTO users VALUES ('$v_username', '$hashed_password', '$v_email', 1, 0, '$time', NULL)");
	if(!$register) exit($strings['db_error']);

	// output success message
	exit($strings['register_success']);

}

$tpl->display("register.tpl");

?>

<?php

// Prevent direct access
if(!class_exists('Template_Lite')) die;

if(logged_in()) {
	$user = fetch_user_info($_SESSION['username']);
	if($user['type'] !== '3') header('Location: ../');
}
else {
	header('Location: ../');
}

?>

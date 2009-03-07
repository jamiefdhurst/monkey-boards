<?php

/**
 * Monkey Boards
 * /include/admin_auth.inc.php
 * Check admin authentication
 * 
 * @version 1.0
 * @author Jamie Hurst
 */

// Prevent direct access
if(!class_exists('Template_Lite'))
	die();

// Check using standard function with access rights
// Redirect back if not logged in
if(logged_in()) {
	$user = fetch_user_info($_SESSION['username']);
	if($user['type'] !== '3')
		header('Location: ../');
} else
	header('Location: ../');
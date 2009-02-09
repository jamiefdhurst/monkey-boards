<?php

/**
 * Monkey Boards
 * /help.php
 * Display forum-based help
 * 
 * @version 1.0
 * @author Jamie Hurst
 */

// Require standard start file
require('include/start.inc.php');

// Get the emoticons for display
$query = sqlite_query($db, "
		SELECT * 
		FROM `emoticons`
	", SQLITE_ASSOC);
$smilies = sqlite_fetch_all($query);

// With each smiley, remove duplicates (I think)
foreach($smilies as $key => $smiley) {
	$index = $smiley['title'];
	if(isset($checked[$index]))
		unset($smilies[$key]);
	else
		$checked[$index] = 1;
}

// Display template
$tpl->assign('smilies', $smilies);
$tpl->display("help.tpl");

?>

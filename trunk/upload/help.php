<?php

require('include/start.inc.php');

$query = sqlite_query($db, "SELECT * FROM emoticons", SQLITE_ASSOC);
$smilies = sqlite_fetch_all($query);

foreach($smilies as $key => $smiley) {
	$index = $smiley['title'];
	if(isset($checked[$index])) {
		unset($smilies[$key]);
	}
	else {
		$checked[$index] = 1;
	}
}

$tpl->assign('smilies', $smilies);

$tpl->display("help.tpl");

?>

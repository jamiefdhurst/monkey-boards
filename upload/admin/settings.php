<?php

require('../include/start.inc.php');
require('../include/admin_auth.inc.php');

if(!empty($v_site_title)) {
	$v_site_title = sqlite_escape_string($v_site_title);
	$v_base_address = sqlite_escape_string($v_base_address);
	$v_date_format = sqlite_escape_string($v_date_format);
	$v_email_from = sqlite_escape_string($v_email_from);

	$site_title = sqlite_exec($db, "UPDATE settings SET value = '$v_site_title' WHERE id = 'site_title'");
	$base_address = sqlite_exec($db, "UPDATE settings SET value = '$v_base_address' WHERE id = 'base_address'");
	$date_format = sqlite_exec($db, "UPDATE settings SET value = '$v_date_format' WHERE id = 'date_format'");
	$site_style = sqlite_exec($db, "UPDATE settings SET value = '$v_site_style' WHERE id = 'style'");
	$email_from = sqlite_exec($db, "UPDATE settings SET value = '$v_email_from' WHERE id = 'email_from'");
	header('Location: settings.php');
}

$handle = opendir('../include/styles');
while($dir = readdir($handle)) {
	if(!is_dir($dir)) {
		$styles[] = $dir;
	}
}

$tpl->assign('styles', $styles);

$tpl->display("admin_settings.tpl");

?>

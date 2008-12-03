<?php

require('../include/start.inc.php');
require('../include/admin_auth.inc.php');

$query = sqlite_query($db, "SELECT name FROM sqlite_master WHERE type='table'", SQLITE_ASSOC);
$tables = sqlite_fetch_all($query);

$total = 0;

foreach($tables as $table) {
	$query = sqlite_query($db, "SELECT * FROM ".$table['name'], SQLITE_ASSOC);
	$rows = sqlite_num_rows($query);
	$total += $rows;
}

$db_filesize = filesize($path.$database) / 1024;

$tpl->assign('php_version', PHP_VERSION);
$tpl->assign('sqlite_version', sqlite_libversion());
$tpl->assign('db_filesize', $db_filesize);
$tpl->assign('db_rows', $total);

$tpl->display("admin_index.tpl");

?>
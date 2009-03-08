<?php

/**
 * Monkey Boards
 * /admin/index.php
 * Admin Main Page
 * 
 * @package MonkeyBoards/admin
 * @version 1.0
 * @author Jamie Hurst
 */

// Require standard start file and admin authentication library
require('../include/start.inc.php');
require('../include/admin_auth.inc.php');

// Get SQL master table
$query = sqlite_query($db, "
		SELECT `name` 
		FROM `sqlite_master` 
		WHERE `type` = 'table'
	", SQLITE_ASSOC);
$tables = sqlite_fetch_all($query);

$total = 0;
// Go through each table and pull the number of rows
// << This is the daftest idea ever. Surely an easier way to get database size? >>
foreach($tables as $table) {
	$query = sqlite_query($db, "
			SELECT * 
			FROM `{$table['name']}`"
		, SQLITE_ASSOC);
	$rows = sqlite_num_rows($query);
	$total += $rows;
}

// Calculate database filesize
$db_filesize = filesize($path . $database) / 1024;

// Assign everything to template and display
$tpl->assign('php_version', PHP_VERSION);
$tpl->assign('sqlite_version', sqlite_libversion());
$tpl->assign('db_filesize', $db_filesize);
$tpl->assign('db_rows', $total);
$tpl->display('admin_index.tpl');

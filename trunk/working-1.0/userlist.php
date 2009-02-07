<?php

require('include/start.inc.php');
if(!logged_in()) header('Location: ./');

$query = sqlite_query($db, 'SELECT * FROM users ORDER BY username ASC', SQLITE_ASSOC);
$users = sqlite_fetch_all($query);

$users_count = count($users);
$tpl->assign('users_count', $users_count);

foreach($users as $key => $user) {
	$users[$key]['title'] = select_title($users[$key]['type']);
	$users[$key]['posts'] = count_user_posts($users[$key]['username']);
	$users[$key]['registered'] = fancy_date($users[$key]['registered']);
}

$tpl->assign('users', $users);
$tpl->display('userlist.tpl');

?>

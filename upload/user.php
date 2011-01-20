<?php

require('include/start.inc.php');
if(!logged_in()) header('Location: ./');

$query = sqlite_query($db, "SELECT * FROM users WHERE username = '$v_u'", SQLITE_ASSOC);
$user = sqlite_fetch_array($query);

$user['title'] = select_title($user['type']);
$user['posts'] = count_user_posts($user['username']);
$user['registered'] =  fancy_date($user['registered']);
$user['last_post'] = user_last_post($user['username']);

$tpl->assign('user', $user);
$tpl->display("user.tpl");

?>
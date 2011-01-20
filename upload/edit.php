<?php

require('include/start.inc.php');
if(!logged_in()) header('Location: ./');

$username = $_SESSION['username'];

$query = sqlite_query($db, "SELECT * FROM posts WHERE id = '$v_post' 
AND username = '$username'", SQLITE_ASSOC);
$post = sqlite_fetch_array($query);

$query = sqlite_query($db, "SELECT * FROM topics WHERE id = 
'".$post['topic']."' LIMIT 0, 1", SQLITE_ASSOC);
$topic = sqlite_fetch_array($query);

$query = sqlite_query($db, "SELECT id FROM posts WHERE topic = '".$post['topic']."' LIMIT 0, 1", SQLITE_ASSOC);
$first_post = sqlite_fetch_single($query);

if($first_post == $post['id']) {
	$tpl->assign('first_post', true);
	$post['subject'] = $topic['subject'];
}

if(!is_array($post)) header('Location: ./');
$post['message'] = htmlentities($post['message']);

if(!empty($v_subject) and !empty($v_topic) and !empty($v_post) and !empty($v_message)) {
	$query = sqlite_exec($db, "UPDATE posts SET message = '$v_message' WHERE id = '$v_post'");
	$query = sqlite_exec($db, "UPDATE topics SET subject = '$v_subject' WHERE id = '$v_topic';");
	header('Location: topic.php?id='.$topic['id'].'#p'.$post['timestamp']);
}
elseif(!empty($v_topic) and !empty($v_post) and !empty($v_message)) {
	$query = sqlite_exec($db, "UPDATE posts SET message = '$v_message' WHERE id = '$v_post'");
	header('Location: topic.php?id='.$topic['id'].'#p'.$post['timestamp']);
}

$tpl->assign('post', $post);
$tpl->assign('topic', $topic);
$tpl->display("edit.tpl");

?>

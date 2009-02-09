<?php

/**
 * Monkey Boards
 * /edit.php
 * Edit a specific post
 * 
 * @version 1.0
 * @author Jamie Hurst
 */

// Require standard start file
require('include/start.inc.php');

// If user is not logged in, return to index
if(!logged_in()) 
	header('Location: ./');

// Get the username from the PHP session
$username = $_SESSION['username'];

// Get the post we need
$query = sqlite_query($db, "
		SELECT *
		FROM `posts` 
		WHERE `id` = '{$v_post}'
		AND `username` = '{$username}'
	", SQLITE_ASSOC);
$post = sqlite_fetch_array($query);

// Get topic info
$query = sqlite_query($db, "
		SELECT * 
		FROM `topics` 
		WHERE `id` = '{$post['topic']}'
		LIMIT 0, 1
	", SQLITE_ASSOC);
$topic = sqlite_fetch_array($query);

// And finally pull the first post from the topic
$query = sqlite_query($db, "
		SELECT `id` 
		FROM `posts`
		WHERE `topic` = '{$post['topic']}'
		LIMIT 0, 1
	", SQLITE_ASSOC);
$first_post = sqlite_fetch_single($query);

// If the editing post is first in the topic, set a couple of things
if($first_post == $post['id']) {
	$tpl->assign('first_post', true);
	$post['subject'] = $topic['subject'];
}

// Post SHOULD be an array by now
if(!is_array($post))
	header('Location: ./');

// Strip out all the HTML garbage (XSS prevention)
$post['message'] = htmlentities($post['message']);

// If we have POST data, we need to save it off
if(!empty($v_subject) and !empty($v_topic) and !empty($v_post) and !empty($v_message)) {
	$query = sqlite_exec($db, "
			UPDATE `posts` SET 
			`message` = '{$v_message}'
			WHERE `id` = '{$v_post}'
		");
	$query = sqlite_exec($db, "
			UPDATE `topics` SET 
			`subject` = '{$v_subject}'
			WHERE `id` = '{$v_topic}'
		");
	header('Location: topic.php?id=' . $topic['id'] . '#p' . $post['timestamp']);
} elseif(!empty($v_topic) and !empty($v_post) and !empty($v_message)) {
	$query = sqlite_exec($db, "
			UPDATE `posts` SET 
			`message` = '{$v_message}'
			WHERE `id` = '{$v_post}'
		");
	header('Location: topic.php?id=' . $topic['id'] . '#p' . $post['timestamp']);
}

// Sort out and display the template
$tpl->assign('post', $post);
$tpl->assign('topic', $topic);
$tpl->display("edit.tpl");

?>

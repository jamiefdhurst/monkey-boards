<?php

/**
 * Monkey Boards
 * /topic.php
 * Read a topic and it's posts
 * 
 * @version 1.0
 * @author Jamie Hurst
 */

// Require standard start file
require('include/start.inc.php');

// If we don't have an ID, redirect
if(!isset($v_id) or !is_numeric($v_id) or !topic_exists($v_id))
	header('Location: ./');

// Get the topic
$query = sqlite_query($db, "
		SELECT * 
		FROM `topics` 
		WHERE `id` = '{$v_id}'
	", SQLITE_ASSOC);
$topic = sqlite_fetch_array($query);

// Get the posts
$query = sqlite_query($db, "
		SELECT * 
		FROM `posts` 
		WHERE `topic` = '{$v_id}'
	", SQLITE_ASSOC);
$posts = sqlite_fetch_all($query);

// Add a new view and update the views
$views = $topic['views'] + 1;
$update = sqlite_exec($db, "
		UPDATE `topics` SET 
			`views` = '{$views}' 
		WHERE `id` = '{$v_id}'
	");

// Get the forum's name
$forum = fetch_forum($topic['id']);
$topic['forum_name'] = $forum['name'];

// Iterate through each post to sort it out
foreach($posts as $key => $post) {
	$posts[$key]['stamp'] = fancy_date($posts[$key]['timestamp']);
	// This avoids causing problems with emoticons
	$posts[$key]['message'] = eregi_replace("http://", "http:!!", $posts[$key]['message']);

	// Sort out emoticons
	$query = sqlite_query($db, "
			SELECT * 
			FROM `emoticons`
		", SQLITE_ASSOC);
	$emoticons = sqlite_fetch_all($query);
	
	// Check for each emoticon in the message
	foreach($emoticons as $emoticon)
		$posts[$key]['message'] = str_ireplace($emoticon['pattern'], '<img src="' . $emoticon['image'] . '" alt="' . $emoticon['title'] . '" height="18" width="18" />', $posts[$key]['message']);
	
	// Re-format links and sort them out
	$posts[$key]['message'] = eregi_replace("http:!!", "http://", $posts[$key]['message']);
	$posts[$key]['message'] = make_clickable($posts[$key]['message']);
	$posts[$key]['message'] = eregi_replace("\r\n\r\n", "</p>\n\n<p>", $posts[$key]['message']);
	$posts[$key]['message'] = eregi_replace("\r\n", "<br />\n", $posts[$key]['message']);
	$posts[$key]['message'] = '<p>' . $posts[$key]['message'] . '</p>';
	
	// Fetch author info
	$user = fetch_user_info($posts[$key]['username']);
	$posts[$key]['user_title'] = select_title($user['type']);
	
	// Allow editable if user is moderator OR post is less than five mins ago
	$time = time();
	if($time - $posts[$key]['timestamp'] <= 300 or $user['type'] >= 2)
		$posts[$key]['editable'] = 1;
}

// Assign and display
$tpl->assign('topic', $topic);
$tpl->assign('posts', $posts);
$tpl->display('topic.tpl');
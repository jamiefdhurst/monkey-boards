<?php

/**
 * Monkey Boards
 * /forum.php
 * Display the contents of a forum
 * 
 * @package MonkeyBoards
 * @version 1.0
 * @author Jamie Hurst
 */

// Require standard start file
require('include/start.inc.php');

// We need a forum ID to continue
if(!isset($v_n) or !forum_exists($v_n))
	header('Location: ./');

// Get the forum data
$query = sqlite_query($db, "
		SELECT * 
		FROM `forums` 
		WHERE `id` = '{$v_n}'
		LIMIT 0, 1
	", SQLITE_ASSOC);
$forum = sqlite_fetch_array($query);

// Get all topics from this forum, sticky first
$query = sqlite_query($db, "
		SELECT * 
		FROM `topics` 
		WHERE `forum` = '{$v_n}'
		AND `sticky` = '1'
		ORDER BY `id` DESC
	", SQLITE_ASSOC);
$sticky_topics = sqlite_fetch_all($query);

// Now get non-sticky topics
$query = sqlite_query($db, "
		SELECT * 
		FROM `topics` 
		WHERE `forum` = '{$v_n}' 
		AND `sticky` = '0' 
		ORDER BY `id` DESC
	", SQLITE_ASSOC);
$normal_topics = sqlite_fetch_all($query);

// Now count the total topics and assign it to the template
// << Could use the total of sticky and normal to achieve this?? No new query necessary. >>
$topics = sqlite_query($db, "
		SELECT * 
		FROM `topics` 
		WHERE `forum` = '{$v_n}'
		ORDER BY `id` DESC
	", SQLITE_ASSOC);
$topics_count = sqlite_num_rows($topics);
$tpl->assign('topics_count', $topics_count);

// For each normal topic, fetch the needed data
foreach($normal_topics as $key => $topic) {
	$normal_topics[$key]['author'] = fetch_author($normal_topics[$key]['id']);
	$normal_topics[$key]['replies'] = count_replies($normal_topics[$key]['id']);
	$normal_topics[$key]['last_post'] = topic_last_post($normal_topics[$key]['id'], 2);
	$normal_topics[$key]['timestamp'] = topic_last_post($normal_topics[$key]['id'], 1);
}

// Now for each sticky topic, do the same
foreach($sticky_topics as $key => $topic) {
	$sticky_topics[$key]['author'] = fetch_author($sticky_topics[$key]['id']);
	$sticky_topics[$key]['replies'] = count_replies($sticky_topics[$key]['id']);
	$sticky_topics[$key]['last_post'] = topic_last_post($sticky_topics[$key]['id'], 2);
	$sticky_topics[$key]['timestamp'] = topic_last_post($sticky_topics[$key]['id'], 1);
}

// This function looks at two timestamps and returns 0 if they are equal or -1 or 1 depending on which is higher
function cmp($topic, $next) {
	if($topic['timestamp'] == $next['timestamp'])
		return 0;
	return $topic['timestamp'] < $next['timestamp'] ? -1 : 1;
}

// Sort the normal topics and then reverse them using the compare function
usort($normal_topics, "cmp");
$normal_topics = array_reverse($normal_topics);

// Sort the sticky topics and then reverse them using the compare function
usort($sticky_topics, "cmp");
$sticky_topics = array_reverse($sticky_topics);

// Sort out and display the template
$tpl->assign('forum', $forum);
$tpl->assign('normal_topics', $normal_topics);
$tpl->assign('sticky_topics', $sticky_topics);
$tpl->display('forum.tpl');
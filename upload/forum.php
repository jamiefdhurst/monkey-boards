<?php

require('include/start.inc.php');
if(!isset($v_n) or !forum_exists($v_n)) header('Location: ./');

$query = sqlite_query($db, "SELECT * FROM forums WHERE id = '$v_n' LIMIT 0, 1", SQLITE_ASSOC);
$forum = sqlite_fetch_array($query);

$query = sqlite_query($db, "SELECT * FROM topics WHERE forum = '$v_n' AND sticky = '1' ORDER BY id DESC", SQLITE_ASSOC);
$sticky_topics = sqlite_fetch_all($query);

$query = sqlite_query($db, "SELECT * FROM topics WHERE forum = '$v_n' AND sticky = '0' ORDER BY id DESC", SQLITE_ASSOC);
$normal_topics = sqlite_fetch_all($query);

$topics = sqlite_query($db, "SELECT * FROM topics WHERE forum = '$v_n' ORDER BY id DESC", SQLITE_ASSOC);
$topics_count = sqlite_num_rows($topics);

$tpl->assign('topics_count', $topics_count);

foreach($normal_topics as $key => $topic) {
	$normal_topics[$key]['author'] = fetch_author($normal_topics[$key]['id']);
	$normal_topics[$key]['replies'] = count_replies($normal_topics[$key]['id']);
	$normal_topics[$key]['last_post'] = topic_last_post($normal_topics[$key]['id'], 2);
	$normal_topics[$key]['timestamp'] = topic_last_post($normal_topics[$key]['id'], 1);
}

foreach($sticky_topics as $key => $topic) {
	$sticky_topics[$key]['author'] = fetch_author($sticky_topics[$key]['id']);
	$sticky_topics[$key]['replies'] = count_replies($sticky_topics[$key]['id']);
	$sticky_topics[$key]['last_post'] = topic_last_post($sticky_topics[$key]['id'], 2);
	$sticky_topics[$key]['timestamp'] = topic_last_post($sticky_topics[$key]['id'], 1);
}

function cmp($topic, $next) {
	if($topic['timestamp'] == $next['timestamp']) {
		return 0;
	}
	return($topic['timestamp'] < $next['timestamp']) ? -1 : 1;
}

usort($normal_topics, "cmp");
$normal_topics = array_reverse($normal_topics);

usort($sticky_topics, "cmp");
$sticky_topics = array_reverse($sticky_topics);

$tpl->assign('forum', $forum);
$tpl->assign('normal_topics', $normal_topics);
$tpl->assign('sticky_topics', $sticky_topics);
$tpl->display("forum.tpl");

?>

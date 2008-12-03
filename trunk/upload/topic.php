<?php

require('include/start.inc.php');
if(!isset($v_id) or !is_numeric($v_id) or !topic_exists($v_id)) header('Location: ./');

$query = sqlite_query($db, "SELECT * FROM topics WHERE id = '$v_id'", SQLITE_ASSOC);
$topic = sqlite_fetch_array($query);

$query = sqlite_query($db, "SELECT * FROM posts WHERE topic = '$v_id'", SQLITE_ASSOC);
$posts = sqlite_fetch_all($query);

$views = $topic['views'] + 1;
$update = sqlite_exec($db, "UPDATE topics SET views = '$views' WHERE id = '$v_id'");

$forum = fetch_forum($topic['id']);
$topic['forum_name'] = $forum['name'];

foreach($posts as $key => $post) {
	$posts[$key]['stamp'] = fancy_date($posts[$key]['timestamp']);
	$posts[$key]['message'] = eregi_replace("http://", "http:!!", $posts[$key]['message']);

	// emoticons
	$query = sqlite_query($db, "SELECT * FROM emoticons", SQLITE_ASSOC);
	$emoticons = sqlite_fetch_all($query);

	foreach($emoticons as $emoticon) {
		$posts[$key]['message'] = str_ireplace($emoticon['pattern'], '<img alt="'.$emoticon['title'].'" height="18" src="'.$emoticon['image'].'" width="18"/>', $posts[$key]['message']);
	}

	$posts[$key]['message'] = eregi_replace("http:!!", "http://", $posts[$key]['message']);
	$posts[$key]['message'] = make_clickable($posts[$key]['message']);
	$posts[$key]['message'] = eregi_replace("\r\n\r\n", "</p>\n\n<p>", $posts[$key]['message']);
	$posts[$key]['message'] = eregi_replace("\r\n", "<br/>\n", $posts[$key]['message']);
	$posts[$key]['message'] = '<p>'.$posts[$key]['message'].'</p>';

	$user = fetch_user_info($posts[$key]['username']);
	$posts[$key]['user_title'] = select_title($user['type']);

	if($time - $posts[$key]['timestamp'] <= 300 or $user['type'] >= 2) {
		$posts[$key]['editable'] = 1;
	}
}

$tpl->assign('topic', $topic);
$tpl->assign('posts', $posts);
$tpl->display("topic.tpl");

?>

<?php

require('include/start.inc.php');

$query = sqlite_query($db, "SELECT * FROM forums", SQLITE_ASSOC);
$forums = sqlite_fetch_all($query);
$tpl->assign('forums_count', count($forums));

$query = sqlite_query($db, "SELECT * FROM categories", SQLITE_ASSOC);
$categories = sqlite_fetch_all($query);

$categories_count = count($categories);

foreach($categories as $cat_key => $category) {

$query = sqlite_query($db, "SELECT * FROM forums WHERE category = '".$category['id']."' ORDER BY id ASC", SQLITE_ASSOC);
$forums = sqlite_fetch_all($query);

$forums_count = count($forums);

if($forums_count > 0) {
	foreach($forums as $key => $forum) {
		$forums[$key]['name'] = htmlentities($forums[$key]['name']);
		$forums[$key]['blurb'] = htmlentities($forums[$key]['blurb']);
		$forums[$key]['topics'] = count_topics($forums[$key]['id']);
		$forums[$key]['posts'] = count_posts($forums[$key]['id']);
		$forums[$key]['last_post'] = forum_last_post($forums[$key]['id']);
	}
	$categories[$cat_key]['forums'] = $forums;
}

}

$site_info = site_info();

$tpl->assign('categories_count', $categories_count);
$tpl->assign('forums', $categories);
$tpl->assign('site_info', $site_info);
$tpl->display("index.tpl");

?>

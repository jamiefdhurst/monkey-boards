<?php

/**
 * Monkey Boards
 * /index.php
 * Main page, display all categories and forums
 * 
 * @version 1.0
 * @author Jamie Hurst
 */

// Require standard start file
require('include/start.inc.php');

// Get all forums (for if there are no categories)
$query = sqlite_query($db, "
		SELECT * 
		FROM `forums`
	", SQLITE_ASSOC);
$forums = sqlite_fetch_all($query);
$tpl->assign('forums_count', count($forums));

// Get all categories
$query = sqlite_query($db, "
		SELECT * 
		FROM `categories`
	", SQLITE_ASSOC);
$categories = sqlite_fetch_all($query);
$categories_count = count($categories);

// Which each category...
foreach($categories as $cat_key => $category) {
	
	// Get this category's forums
	$query = sqlite_query($db, "
			SELECT * 
			FROM `forums` 
			WHERE `category` = '{$category['id']}'
			ORDER BY `id` ASC
		", SQLITE_ASSOC);
	$forums = sqlite_fetch_all($query);
	$forums_count = count($forums);
	
	// If we have forums, sort them out
	if($forums_count > 0) {
		// Strip out HTML, get the last post and count the topics and posts
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

// Sort out site info
$site_info = site_info();

// Sort out and display template
$tpl->assign('categories_count', $categories_count);
$tpl->assign('forums', $categories);
$tpl->assign('site_info', $site_info);
$tpl->display("index.tpl");
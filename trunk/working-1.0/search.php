<?php

/**
 * Monkey Boards
 * /search.php
 * Perform search on forum topics and posts
 * 
 * @version 1.0
 * @author Jamie Hurst
 */

// Require standard start file
require('include/start.inc.php');

// Matches keywords in a haystack separated by spaces
function match_keywords($haystack, $keywords) {

	$matched = true;
	$keywords = explode(' ', $keywords);

	foreach($keywords as $key => $keyword) {
		$keyword = trim($keyword);
		$keyword = strtolower($keyword);
		if(stripos('  '.$haystack.'  ', ' '.$keyword.' ') === false) {
			$matched = false;
			break;
		}
	}
	return $matched;
}

// Match any messages in a topic
function match_messages($topic_id, $keywords) {
	global $db;
	$whole_topic = '';

	$query = sqlite_query($db, "
			SELECT `message` 
			FROM `posts` 
			WHERE `topic` = '{$topic_id}'
		", SQLITE_NUM);
	$messages = sqlite_fetch_array($query);

	foreach($messages as $message)
		$whole_topic .= $message;
	if(match_keywords($whole_topic, $keywords))
		return true;
	else
		return false;
}

// Fetch the forums by category
$query = sqlite_query($db, "
		SELECT `id`, `name` 
		FROM `categories`
	", SQLITE_ASSOC);
$categories = sqlite_fetch_all($query);

// With each category get the forums
foreach($categories as $key => $category) {
	$query = sqlite_query($db, "
			SELECT `id`, `name` 
			FROM `forums` 
			WHERE `category` = '{$category['id']}'
		", SQLITE_ASSOC);
	$forums = sqlite_fetch_all($query);
	$categories[$key]['forums'] = $forums;
}

// Assign any given keywords to the template
if(isset($v_keywords) and !empty($v_keywords)) {
	$v_keywords = trim($v_keywords);
	$tpl->assign('keywords', $v_keywords);
}

// Assign any given author to the template
if(isset($v_author) and !empty($v_author)) {
	$v_author = trim($v_author);
	$tpl->assign('author', $v_author);
}

// Assign a couple more variables
if(isset($v_forum) and !empty($v_forum))
	$tpl->assign('selected_forum', $v_forum);
if(isset($v_where) and !empty($v_where))
	$tpl->assign('where', $v_where);

// If we have something to search on
if(isset($v_keywords) or isset($v_author)) {

	// Begin to build up the query
	$default_query = "SELECT * FROM topics ";
	if(isset($v_forum) and !empty($v_forum)) {
		$default_query .= "WHERE `forum` = '{$v_forum}' ";
	}
	$default_query .= 'ORDER BY `id` DESC';

	// Fetch all topics matching the query
	$query = sqlite_query($db, $default_query, SQLITE_ASSOC);
	$topics = sqlite_fetch_all($query);
	
	// Iterate through each topic
	foreach($topics as $topic) {
		$topic['subject'] = strtolower($topic['subject']);
		
		// Search by keywords and author
		if(isset($v_keywords) and !empty($v_keywords) and isset($v_author) and !empty($v_author)) {
			$author_match = false;
			$keywords_match = false;
			
			// Query the given topic to check the author
			$query = sqlite_query($db, "
					SELECT `username` 
					FROM `posts` 
					WHERE `topic` = '{$topic['id']}'
					LIMIT 0, 1
				", SQLITE_ASSOC);
			$author = sqlite_fetch_single($query);
			if($author == strtolower($v_author))
				$author_match = true;
			
			// Go through the where options
			switch($v_where) {
				case 1:
					// Messages AND subject
					if(match_messages($topic['id'], $v_keywords) and match_keywords($topic['subject'], $v_keywords))
						$keywords_match = true;
					break;
				case 2:
					// Message only
					if(match_messages($topic['id'], $v_keywords))
						$keywords_match = true;
					break;
				case 3:
					// Subject only
					if(match_keywords($topic['subject'], $v_keywords))
						$keywords_match = true;
					break;
			}
			// Output a given match
			if($author_match == true and $keywords_match == true)
				$results[] = $topic;
		// Search by keywords only
		} elseif(isset($v_keywords) and !empty($v_keywords)) {
			$keywords_match = false;
			
			// Go through search options
			switch($v_where) {
				case 1:
					// Messages AND subject
					if(match_messages($topic['id'], $v_keywords) or match_keywords($topic['subject'], $v_keywords))
						$keywords_match = true;
					break;
				case 2:
					// Messages only
					if(match_messages($topic['id'], $v_keywords))
						$keywords_match = true;
					break;
				case 3:
					// Subject only
					if(match_keywords($topic['subject'], $v_keywords))
						$keywords_match = true;
					break;
			}
			// Output a given match
			if($keywords_match == true)
				$results[] = $topic;
		// Search by author only
		} elseif(isset($v_author) and !empty($v_author)) {
			// Get topic author
			$query = sqlite_query($db, "
					SELECT `username` 
					FROM `posts` 
					WHERE `topic` = '{$topic['id']}' 
					LIMIT 0, 1
				", SQLITE_ASSOC);
			$author = sqlite_fetch_single($query);
			if($author == strtolower($v_author))
				$results[] = $topic;
		}
	}
}

// If we have results
if(isset($results)) {
	// Sort out each topic's data
	foreach($results as $key => $topic) {
		$results[$key]['author'] = fetch_author($results[$key]['id']);
		$results[$key]['replies'] = count_replies($results[$key]['id']);
		$results[$key]['last_post'] = topic_last_post($results[$key]['id'], 2);
		$results[$key]['timestamp'] = topic_last_post($results[$key]['id'], 1);
	}
	// Assign the results
	$tpl->assign('results', $results);
}

// Assign the categories and display the template
$tpl->assign('categories', $categories);
$tpl->display('search.tpl');
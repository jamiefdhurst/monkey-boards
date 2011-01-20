<?php

require('include/start.inc.php');

function match_keywords($haystack, $keywords) {

	$matched = true;
	$keywords = explode(' ', $keywords);

	foreach($keywords as $key => $keyword) {
		$keyword = trim($keyword);
		$keyword = strtolower($keyword);

		if(!stripos('  '.$haystack.'  ', ' '.$keyword.' ')) {
			$matched = false;
			break;
		}
	}
	if($matched == true) {
		return true;
	}
	elseif($matched == false) {
		return false;
	}
}

function match_messages($topic_id, $keywords) {
	global $db;
	$whole_topic = '';

	$query = sqlite_query($db, "SELECT message FROM posts WHERE topic = '".$topic_id."';", SQLITE_NUM);
	$messages = sqlite_fetch_array($query);

	foreach($messages as $message) { $whole_topic .= $message; }
	if(match_keywords($whole_topic, $keywords)) {
		return true;
	}
	else {
		return false;
	}
}

// fetch the forums by category
$query = sqlite_query($db, "SELECT id, name FROM categories", SQLITE_ASSOC);
$categories = sqlite_fetch_all($query);

foreach($categories as $key => $category) {
	$query = sqlite_query($db, "SELECT id, name FROM forums WHERE category = '".$category['id']."'", SQLITE_ASSOC);
	$forums = sqlite_fetch_all($query);
	$categories[$key]['forums'] = $forums;
}

if(isset($v_keywords) and !empty($v_keywords)) {
	$v_keywords = trim($v_keywords);
	$tpl->assign('keywords', $v_keywords);
}

if(isset($v_author) and !empty($v_author)) {
	$v_author = trim($v_author);
	$tpl->assign('author', $v_author);
}

if(isset($v_forum) and !empty($v_forum)) { $tpl->assign('selected_forum', $v_forum); }
if(isset($v_where) and !empty($v_where)) { $tpl->assign('where', $v_where); }

if(isset($v_keywords) or isset($v_author)) {

	$default_query = 'SELECT * FROM topics ';
	if(isset($v_forum) and !empty($v_forum)) {
		$default_query .= "WHERE forum = '$v_forum' ";
	}
	$default_query .= 'ORDER BY id DESC';

	$query = sqlite_query($db, $default_query, SQLITE_ASSOC);
	$topics = sqlite_fetch_all($query);

	foreach($topics as $topic) {
		$topic['subject'] = strtolower($topic['subject']);

		if(isset($v_keywords) and !empty($v_keywords) and isset($v_author) and !empty($v_author)) {
			$author_match = false;
			$keywords_match = false;
			
			$query = sqlite_query($db, "SELECT username FROM posts WHERE topic = '".$topic['id']."' LIMIT 0, 1;", SQLITE_ASSOC);
			$author = sqlite_fetch_single($query);
			if($author == strtolower($v_author)) {
				$author_match = true;
			}

			switch($v_where) {
				case 1:
					if(match_messages($topic['id'], $v_keywords) and match_keywords($topic['subject'], $v_keywords)) {
						$keywords_match = true;
					}
				break;
				case 2:
					if(match_messages($topic['id'], $v_keywords)) {
						$keywords_match = true;
					}
				break;
				case 3:
					if(match_keywords($topic['subject'], $v_keywords)) {
						$keywords_match = true;
					}
				break;
			}
			if($author_match == true and $keywords_match == true) { $results[] = $topic; }
		}
		elseif(isset($v_keywords) and !empty($v_keywords)) {
			$keywords_match = false;

			switch($v_where) {
				case 1:
					if(match_messages($topic['id'], $v_keywords) or match_keywords($topic['subject'], $v_keywords)) {
						$keywords_match = true;
					}
				break;
				case 2:
					if(match_messages($topic['id'], $v_keywords)) {
						$keywords_match = true;
					}
				break;
				case 3:
					if(match_keywords($topic['subject'], $v_keywords)) {
						$keywords_match = true;
					}
				break;
			}

			if($keywords_match == true) { $results[] = $topic; }
		}
		elseif(isset($v_author) and !empty($v_author)) {
			$query = sqlite_query($db, "SELECT username FROM posts WHERE topic = '".$topic['id']."' LIMIT 0, 1;", SQLITE_ASSOC);
			$author = sqlite_fetch_single($query);
			if($author == strtolower($v_author)) {
				$results[] = $topic;
			}
		}
	}
}

if(isset($results)) {
	foreach($results as $key => $topic) {
		$results[$key]['author'] = fetch_author($results[$key]['id']);
		$results[$key]['replies'] = count_replies($results[$key]['id']);
		$results[$key]['last_post'] = topic_last_post($results[$key]['id'], 2);
		$results[$key]['timestamp'] = topic_last_post($results[$key]['id'], 1);
	}
	$tpl->assign('results', $results);
}

$tpl->assign('categories', $categories);
$tpl->display("search.tpl");

?>

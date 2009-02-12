<?php

/**
 * Monkey Boards
 * /moderate.php
 * Perform post and topic moderation
 * 
 * @version 1.0
 * @author Jamie Hurst
 */

// Include standard start file
require('include/start.inc.php');

// Assign the given action to the template
$tpl->assign('action', $v_action);

// If we have a topic and the user is at least a moderator
if(isset($v_topic) and isset($v_action) and $user_type >= 2) {
	// Pull topic from database
	$query = sqlite_query($db, "
			SELECT * 
			FROM `topics` 
			WHERE `id` = '{$v_topic}'
			LIMIT 0, 1
		", SQLITE_ASSOC);
	$topic = sqlite_fetch_array($query);
	// Assign topic to template
	$tpl->assign('topic', $topic);
	
	// Get correct action
	switch($v_action) {
		case "delete":
			// Delete a topic and all of its posts
			$delete_topic = sqlite_exec($db, "
					DELETE FROM `topics` 
					WHERE `id` = '{$v_topic}'
				");
			$delete_posts = sqlite_exec($db, "
					DELETE FROM `posts` 
					WHERE `topic` = '{$v_topic}'
				");
			header('Location: forum.php?n=' . $topic['forum']);
			break;
		case "delete_confirm":
			// Display template to confirm deletion
			$tpl->display('moderate.tpl');
			break;
		case "lock":
			// Lock a topic in the database
			$update = sqlite_exec($db, "
					UPDATE `topics` SET 
						`locked` = '1' 
					WHERE `id` = '{$v_topic}'
				");
			header('Location: topic.php?id=' . $topic['id']);
			break;
		case "move":
			// Move a topic to a different forum
			$update = sqlite_exec($db, "
					UPDATE `topics` SET 
						`forum` = '{$v_forum}' 
					WHERE `id` = '{$v_topic}'
				");
			header('Location: topic.php?id=' . $topic['id']);
			break;
		case "move_select":
			// Show all forums and allow user to select where to move topic to
			$query = sqlite_query($db, "
					SELECT `id`, `name` 
					FROM `categories`
				", SQLITE_ASSOC);
			$categories = sqlite_fetch_all($query);
			
			foreach($categories as $key => $category) {
				$query = sqlite_query($db, "
						SELECT `id`, `name` 
						FROM `forums` 
						WHERE `category` = '{$category['id']}'
					", SQLITE_ASSOC);
				$forums = sqlite_fetch_all($query);
				$categories[$key]['forums'] = $forums;
			}
			
			// Assign categories and display template
			$tpl->assign('categories', $categories);
			$tpl->display('moderate.tpl');
			break;
		case "stick":
			// Set topic to sticky
			$update = sqlite_exec($db, "
					UPDATE `topics` SET 
						`sticky` = '1'
					 WHERE `id` = '{$v_topic}'
				");
			header('Location: topic.php?id=' . $topic['id']);
			break;
		case "unlock":
			// Unlock a topic
			$update = sqlite_exec($db, "
					UPDATE `topics` SET 
						`locked` = '0' 
					WHERE `id` = '{$v_topic}'
				");
			header('Location: topic.php?id=' . $topic['id']);
			break;
		case "unstick":
			// Unstick a topic
			$update = sqlite_exec($db, "
					UPDATE `topics` SET 
						`sticky` = '0' 
					WHERE `id` = '{$v_topic}'
				");
			header('Location: topic.php?id=' . $topic['id']);
			break;
	}
}
<?php

/**
 * Monkey Boards
 * /admin/forums.php
 * Manage forums
 * 
 * @package MonkeyBoards/admin
 * @version 1.0
 * @author Jamie Hurst
 */

// Require standard start file and admin authentication library
require('../include/start.inc.php');
require('../include/admin_auth.inc.php');

// If we have an action, handle it
if(!empty($v_action)) {
	
	// Get the forum details if we have it
	if(isset($v_forum)) {
		$query = sqlite_query($db, "
				SELECT * 
				FROM `forums` 
				WHERE `id` = '{$v_forum}'
			", SQLITE_ASSOC);
		$forum = sqlite_fetch_array($query);
		// Assign to template
		$tpl->assign('forum', $forum);
	}
	
	// Action to choose
	switch($v_action) {
		// Add a new forum
		case "add":
			// We need a name and description
			// << No error handling again. >>
			if(isset($v_name) and isset($v_description)) {
				// Lower the name
				$id = strtolower($v_name);
				// Replace spaces with nothing
				$id = str_replace(' ', '', $id);
				// Make sure name is valid
				// << Use preg instead. >>
				if(!ereg('^[A-Za-z][0-9A-Za-z ]*$', $v_name)) 
					die('Invalid forum name.');
				
				// << This should be entitied when pulling out. >>
				$v_description = htmlentities($v_description);
				
				// Start forum checking
				$query = sqlite_query($db, "
						SELECT `id` 
						FROM `forums` 
						WHERE `id` = '{$id}'
						LIMIT 0, 1
					", SQLITE_ASSOC);
				$check = sqlite_fetch_single($query);
				
				// << What exactly is this bit supposed to do? Check SQL schema. >>
				$count = 1;
				$orig_id = $id;
				while($check == $id) {
					$count++;
					$id = $orig_id.$count;

					$query = sqlite_query($db, "
							SELECT `id` 
							FROM `forums` 
							WHERE `id` = '{$id}'
							LIMIT 0, 1
						", SQLITE_ASSOC);
					$check = sqlite_fetch_single($query);
				}
				
				// Add the new forum
				$update = sqlite_exec($db, "
						INSERT INTO `forums` 
						VALUES (
							'{$id}', 
							'{$v_category}', 
							'{$v_name}', 
							'{$v_description}'
						)
					");
				// Redirect back
				header('Location: forums.php');
			}
			break;
		// Edit a forum name
		case "save":
			// New name, update all forum references
			if($v_id != $v_forum)
				$update = sqlite_exec($db, "
						UPDATE `topics` 
						SET 
							`forum` = '{$v_id}'
						WHERE `forum` = '{$v_forum}'
					");
			// Update the forum entry in the main table
			$update = sqlite_exec($db, "
					UPDATE `forums` 
					SET 
						`id` = '{$v_id}', 
						`category` = '{$v_category}', 
						`name` = '{$v_name}', 
						`blurb` = '{$v_description}' 
					WHERE `id` = '{$v_forum}'
				");
			// Redirect back
			header('Location: forums.php');
			break;
		// Delete a forum
		case "delete":
			// Get forum details
			$query = sqlite_query($db, "
					SELECT * 
					FROM `topics` 
					WHERE `forum` = '{$v_forum}'
				", SQLITE_ASSOC);
			$topics = sqlite_fetch_all($query);
			
			// For each topic and all its posts, nuke them
			foreach($topics as $topic) {
				$id = $topic['id'];
				$query = sqlite_query($db, "
						SELECT * 
						FROM `posts` 
						WHERE `topic` = '{$id}'
					", SQLITE_ASSOC);
				$posts = sqlite_fetch_all($query);
				foreach($posts as $post) {
					$update = sqlite_exec($db, "
						DELETE FROM `posts` 
						WHERE `id` = '{$post['id']}'
					");
				}
				$update = sqlite_exec($db, "
						DELETE FROM `topics` 
						WHERE `id` = '{$id}'
					");
			}
			// Delete the forum
			$update = sqlite_exec($db, "
					DELETE FROM `forums` 
					WHERE `id` = '{$v_forum}'
				");
			// Redirect back
			header('Location: forums.php');
			break;
	}
	// Assign the action to the template
	$tpl->assign('action', $v_action);
}

// Get all categories
$query = sqlite_query($db, "
		SELECT * 
		FROM `categories`
	", SQLITE_ASSOC);
$categories = sqlite_fetch_all($query);

// Get each category's forums
foreach($categories as $key => $category) {
	$query = sqlite_query($db, "
			SELECT * 
			FROM `forums` 
			WHERE `category` = '{$category['id']}'
		", SQLITE_ASSOC);
	$categories[$key]['forums'] = sqlite_fetch_all($query);
}

// Assign template and display
$tpl->assign('categories_count', count($categories));
$tpl->assign('categories', $categories);
$tpl->display('admin_forums.tpl');

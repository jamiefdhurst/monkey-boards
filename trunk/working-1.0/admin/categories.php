<?php

/**
 * Monkey Boards
 * /admin/categories.php
 * Manage forum categories
 * 
 * @version 1.0
 * @author Jamie Hurst
 */

// Require standard start file and admin authentication library
require('../include/start.inc.php');
require('../include/admin_auth.inc.php');

// We have an action to perform
if(!empty($v_action)) {
	
	// If we have the category, make sure we pull it
	if(isset($v_category)) {
		$query = sqlite_query($db, "
				SELECT * 
				FROM `categories` 
				WHERE `id` = '{$v_category}'
			", SQLITE_ASSOC);
		$category = sqlite_fetch_array($query);
		// Assign category to template
		$tpl->assign('category', $category);
	}
	
	// Choose action
	switch($v_action) {
		// Add a category
		case "add":
			// We need the name to add the category
			// << Need some way to specify error here. >>
			if(isset($v_name)) {
				// Category name must be valid (begin with letter)
				// << Use preg instead of ereg. >>
				if(!ereg('^[A-Za-z][0-9A-Za-z ]*$', $v_name))
					 die('Invalid category name.');
				
				// Make sure we don't have a category with this name already
				$query = sqlite_query($db, "
						SELECT `id` 
						FROM `categories` 
						WHERE `name` = '{$v_name}'
						LIMIT 0, 1
					", SQLITE_ASSOC);
				$check = sqlite_fetch_single($query);
				if(!empty($check))
					die('Category already exists with that name.');
				
				// Insert the new category and return
				$update = sqlite_exec($db, "
						INSERT INTO `categories` 
						VALUES (
							null, 
							'{$v_name}'
						)
					");
				header('Location: categories.php');
			}
			break;
		// Save a new category name
		// << Error checking is inconsistent. New category checks if name is set. >>
		case "save":
			$update = sqlite_exec($db, "
					UPDATE `categories` 
					SET 
						`name` = '{$v_name}' 
					WHERE `id` = '{$v_category}'
				");
			header('Location: categories.php');
			break;
		// Remove a category
		// << Doesn't handle forums beneath this category. >>
		case "delete":
			$update = sqlite_exec($db, "
					DELETE FROM `categories` 
					WHERE `id` = '{$v_category}'
				");
			header('Location: categories.php');
			break;
	}
	// Assign the action
	$tpl->assign('action', $v_action);
}

// Pull categories
$query = sqlite_query($db, "
		SELECT * 
		FROM `categories`
	", SQLITE_ASSOC);
$categories = sqlite_fetch_all($query);

// Assign everything and display template
$tpl->assign('categories_count', count($categories));
$tpl->assign('categories', $categories);
$tpl->display('admin_categories.tpl');
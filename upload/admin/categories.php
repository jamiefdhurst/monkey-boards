<?php

require('../include/start.inc.php');
require('../include/admin_auth.inc.php');

if(!empty($v_action)) {

	if(isset($v_category)) {
		$query = sqlite_query($db, "SELECT * FROM categories WHERE id = '$v_category'", SQLITE_ASSOC);
		$category = sqlite_fetch_array($query);
		$tpl->assign('category', $category);
	}

	switch($v_action) {
		case "add":
			if(isset($v_name)) {
				if(!ereg("^[A-Za-z][0-9A-Za-z ]*$", $v_name)) exit('Invalid category name.');

				$query = sqlite_query($db, "SELECT id FROM categories WHERE name = '$v_name' LIMIT 0, 1", SQLITE_ASSOC);
				$check = sqlite_fetch_single($query);

				if(!empty($check)) exit('Category already exists with that name.');

				$update = sqlite_exec($db, "INSERT INTO categories VALUES ( NULL, '$v_name' );");
				header('Location: categories.php');
			}
		break;
		case "save":
			$update = sqlite_exec($db, "UPDATE categories SET name = '$v_name' WHERE id = '$v_category'");
			header('Location: categories.php');
		break;
		case "delete":
			$update = sqlite_exec($db, "DELETE FROM categories WHERE id = '$v_category'");
			header('Location: categories.php');
		break;
	}
	$tpl->assign('action', $v_action);
}

$query = sqlite_query($db, "SELECT * FROM categories", SQLITE_ASSOC);
$categories = sqlite_fetch_all($query);

$categories_count = count($categories);
$tpl->assign('categories_count', $categories_count);

$tpl->assign('categories', $categories);
$tpl->display("admin_categories.tpl");

?>

<?php

require('../include/start.inc.php');
require('../include/admin_auth.inc.php');

if(!empty($v_action)) {

	if(isset($v_forum)) {
		$query = sqlite_query($db, "SELECT * FROM forums WHERE id = '$v_forum'", SQLITE_ASSOC);
		$forum = sqlite_fetch_array($query);
		$tpl->assign('forum', $forum);
	}

	switch($v_action) {
		case "add":
			if(isset($v_name) and isset($v_description)) {
				$id = strtolower($v_name);
				$id = eregi_replace(' ', '', $id);
				if(!ereg("^[A-Za-z][0-9A-Za-z ]*$", $v_name)) exit('Invalid forum name.');
				$v_description = htmlentities($v_description);

				$query = sqlite_query($db, "SELECT id FROM forums WHERE id = '$id' LIMIT 0, 1", SQLITE_ASSOC);
				$check = sqlite_fetch_single($query);

				$count = 1;
				$orig_id = $id;
				while($check == $id) {
					$count++;
					$id = $orig_id.$count;

					$query = sqlite_query($db, "SELECT id FROM forums WHERE id = '$id' LIMIT 0, 1", SQLITE_ASSOC);
					$check = sqlite_fetch_single($query);
				}

				$update = sqlite_exec($db, "INSERT INTO forums VALUES ('$id', $v_category, '$v_name', '$v_description');");
				header('Location: forums.php');
			}
		break;
		case "save":
			if($v_id !== $v_forum) {
				$update = sqlite_exec($db, "UPDATE topics SET forum = '$v_id' WHERE forum = '$v_forum'");
			}
			$update = sqlite_exec($db, "UPDATE forums SET id = '$v_id', category = $v_category, name = '$v_name', blurb = '$v_description' WHERE id = '$v_forum'");
			header('Location: forums.php');
		break;
		case "delete":
			$query = sqlite_query($db, "SELECT * FROM topics WHERE forum = '$v_forum'", SQLITE_ASSOC);
			$topics = sqlite_fetch_all($query);

			foreach($topics as $topic) {
				$id = $topic['id'];
				$query = sqlite_query($db, "SELECT * FROM posts WHERE topic = '$id'", SQLITE_ASSOC);
				$posts = sqlite_fetch_all($query);
				foreach($posts as $post) {
					$post_id = $post['id'];
					$update = sqlite_exec($db, "DELETE FROM posts WHERE id = '$post_id'");
				}
				$update = sqlite_exec($db, "DELETE FROM topics WHERE id = '$id'");
			}
			$update = sqlite_exec($db, "DELETE FROM forums WHERE id = '$v_forum'");
			header('Location: forums.php');
		break;
	}
	$tpl->assign('action', $v_action);
}

$query = sqlite_query($db, "SELECT * FROM categories", SQLITE_ASSOC);
$categories = sqlite_fetch_all($query);

foreach($categories as $key => $category) {
	$query = sqlite_query($db, "SELECT * FROM forums WHERE category = '".$category['id']."'", SQLITE_ASSOC);
	$categories[$key]['forums'] = sqlite_fetch_all($query);}


$categories_count = count($categories);
$tpl->assign('categories_count', $categories_count);

$tpl->assign('categories', $categories);
$tpl->display("admin_forums.tpl");

?>

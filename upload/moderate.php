<?php

require('include/start.inc.php');
$tpl->assign('action', $v_action);

if(isset($v_topic) and isset($v_action) and $user_type >= 2) {
	$query = sqlite_query($db, "SELECT * FROM topics WHERE id = '$v_topic' LIMIT 0, 1", SQLITE_ASSOC);
	$topic = sqlite_fetch_array($query);
	$tpl->assign('topic', $topic);

	switch($v_action) {
		case "delete":
			$delete_topic = sqlite_exec($db, "DELETE FROM topics WHERE id = '$v_topic'");
			$delete_posts = sqlite_exec($db, "DELETE FROM posts WHERE topic = '$v_topic'");
			header('Location: forum.php?n='.$topic['forum']);
		break;
		case "delete_confirm":
			$tpl->display('moderate.tpl');
		break;
		case "lock":
			$update = sqlite_exec($db, "UPDATE topics SET locked = 1 WHERE id = '$v_topic'");
			header('Location: topic.php?id='.$topic['id']);
		break;
		case "move":
			$update = sqlite_exec($db, "UPDATE topics SET forum = '$v_forum' WHERE id = '$v_topic'");
			header('Location: topic.php?id='.$topic['id']);
		break;
		case "move_select":
			$query = sqlite_query($db, "SELECT id, name FROM categories", SQLITE_ASSOC);
			$categories = sqlite_fetch_all($query);

			foreach($categories as $key => $category) {
				$query = sqlite_query($db, "SELECT id, name FROM forums WHERE category = '".$category['id']."'", SQLITE_ASSOC);
				$forums = sqlite_fetch_all($query);
				$categories[$key]['forums'] = $forums;
			}

			$tpl->assign('categories', $categories);
			$tpl->display('moderate.tpl');
		break;
		case "stick":
			$update = sqlite_exec($db, "UPDATE topics SET sticky = 1 WHERE id = '$v_topic'");
			header('Location: topic.php?id='.$topic['id']);
		break;
		case "unlock":
			$update = sqlite_exec($db, "UPDATE topics SET locked = 0 WHERE id = '$v_topic'");
			header('Location: topic.php?id='.$topic['id']);
		break;
		case "unstick":
			$update = sqlite_exec($db, "UPDATE topics SET sticky = 0 WHERE id = '$v_topic'");
			header('Location: topic.php?id='.$topic['id']);
		break;
	}
}

?>

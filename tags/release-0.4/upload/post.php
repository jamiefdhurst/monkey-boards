<?php

require('include/start.inc.php');
require('include/kses.inc.php');

if(!logged_in() or empty($v_mode)) header('Location: ./');
if(!isset($v_message)) exit($strings['missing_message']);

$allowed = array('strong' => array(),
		'abbr' => array('title' => 1),
		'a' => array('href' => 1, 'title' => 1, 'type' => 1, 'hreflang' => 1),
		'acronym' => array('title' => 1),
		'blockquote' => array(),
		'cite' => array('datetime' => 1, 'cite' => 1),
		'code' => array(),
		'del' => array(),
		'dfn' => array('title' => 1),
		'em' => array(),
		'ins' => array(),
		'kbd' => array(),
		'q' => array(),
		'samp' => array(),
		'var' => array(),
		'pre' => array());

$v_message = kses($v_message, $allowed);
$v_message = sqlite_escape_string($v_message);

if(empty($v_message)) exit($strings['missing_message']);

$username = $_SESSION['username'];
$time = time();

switch($v_mode) {
	case "topic":
		$v_forum = trim($v_forum);
		$v_subject = trim($v_subject);
		$v_message = trim($v_message);

		$v_subject = htmlentities($v_subject);

		// Not sure what to do, hack to fix ampersand parsing error
		$v_message = htmlentities($v_message);
		$v_message = eregi_replace('&amp;amp;', '&amp;', $v_message);

		if(empty($v_forum) or !validate_forum($v_forum)) header('Location: ./');
		if(empty($v_subject)) exit($strings['missing_subject']);
		if(strlen($v_subject) > 60) exit($strings['subject_len']);
		if(strlen($v_message) > 1000) exit($strings['message_len']);

		$v_forum = strip_tags($v_forum);
		$v_subject = strip_tags($v_subject);
		$v_subject = sqlite_escape_string($v_subject);

		if(!forum_exists($v_forum)) header('Location : ./');

		// now xml parse input
		$parser = xml_parser_create();
		$xml = xml_parse($parser, '<begin>'.$v_message.'</begin>', true);
		if(!$xml) {
			$error_code = xml_get_error_code($parser);
			$error_string = xml_error_string($error_code);
			exit($error_string);
		}

		$insert = sqlite_exec($db, "INSERT INTO topics VALUES (NULL, '$v_forum', '$v_subject', 0, 0, 0)") or die($settings['msg_db_error']);

		$query = sqlite_query($db, "SELECT id FROM topics ORDER BY id DESC LIMIT 0, 1", SQLITE_ASSOC);
		$topic_id = sqlite_fetch_single($query);

		$insert = sqlite_exec($db, "INSERT INTO posts VALUES (NULL, '$topic_id', '$username', '$time', '$v_message')") or die($settings['msg_db_error']);

		header('Location: ./topic.php?id='.$topic_id);

	break;

	case "reply":

	$v_topic = strip_tags($v_topic);
	if(empty($v_topic)) header('Location: ./');

	$query = sqlite_query($db, "SELECT locked FROM topics WHERE id = '$v_topic' LIMIT 0, 1", SQLITE_ASSOC);
	$locked = sqlite_fetch_single($query);

	if($locked == "1") {
		header('Location: topic.php?id='.$v_topic);
		exit;
	}

		// now xml parse input
		$parser = xml_parser_create();
		$xml = xml_parse($parser, $v_message, true);
		if(!$xml) {
			$error_code = xml_get_error_code($parser);
			$error_string = xml_error_string($error_code);
			if($error_string !== 'Empty document') exit($error_string);
		}

	$insert = sqlite_exec($db, "INSERT INTO posts VALUES (NULL, '$v_topic', '$username', '$time', '$v_message')") or die($settings['msg_db_error']);

	header('Location: topic.php?id='.$v_topic.'#p'.$time);

	break;
}

?>

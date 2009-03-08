<?php

/**
 * Monkey Boards
 * /post.php
 * Either post a reply or topic
 * 
 * @package MonkeyBoards
 * @version 1.0
 * @author Jamie Hurst
 */

// Include standard start file and kses library
require('include/start.inc.php');
require('include/kses.inc.php');

// User must be logged in, and must have a message
if(!logged_in() or empty($v_mode))
	header('Location: ./');
if(!isset($v_message))
	die($strings['missing_message']);

// List of allowed HTML tags
$allowed = array(
	'strong' 		=>	array(),
	'abbr' 			=> 	array(
							'title' 	=>	1
						),
	'a' 			=> 	array(
							'href' 		=> 	1, 
							'title' 	=>	1, 
							'type' 		=> 	1, 
							'hreflang' 	=> 	1
						),
	'acronym' 		=>	array(
							'title' 	=> 	1
						),
	'blockquote' 	=> 	array(),
	'cite' 			=> 	array(
							'datetime' 	=>	1, 
							'cite' 		=> 	1
						),
	'code' 			=> 	array(),
	'del' 			=> 	array(),
	'dfn' 			=> 	array(
							'title' 	=> 	1
						),
	'em' 			=> 	array(),
	'ins' 			=> 	array(),
	'kbd' 			=> 	array(),
	'q' 			=> 	array(),
	'samp' 			=> 	array(),
	'var' 			=> 	array(),
	'pre' 			=> 	array()
);

// Sort out the message with the allowed tags and escape the string
$v_message = kses($v_message, $allowed);
$v_message = sqlite_escape_string($v_message);

// If we've pulled everything out of the string, die with an error
if(empty($v_message))
	die($strings['missing_message']);

// Get the username and current time
$username = $_SESSION['username'];
$time = time();

// Do stuff based on whether we have a topic or reply
switch($v_mode) {
	case "topic":
		// Posting a topic, get the forum, subject and message
		$v_forum = trim($v_forum);
		$v_subject = htmlentities(trim($v_subject));
		$v_message = htmlentities(trim($v_message));
		
		// Not sure what to do, hack to fix ampersand parsing error
		$v_message = eregi_replace('&amp;amp;', '&amp;', $v_message);
		
		// If we don't have a forum or it is invalid, back to index
		// << Could do with an error here of some sort. >>
		if(empty($v_forum) or !validate_forum($v_forum))
			header('Location: ./');
		// Validate subject and message length
		if(empty($v_subject))
			die($strings['missing_subject']);
		if(strlen($v_subject) > 60)
			die($strings['subject_len']);
		if(strlen($v_message) > 1000)
			die($strings['message_len']);
		
		// Strip HTML from everything EXCEPT message
		$v_forum = strip_tags($v_forum);
		$v_subject = strip_tags($v_subject);
		$v_subject = sqlite_escape_string($v_subject);
		
		// If the forum NOW doesn't exist, also redirect
		// << Again, error problem. >>
		if(!forum_exists($v_forum))
			header('Location : ./');
		
		// XML parse input to test validity
		$parser = xml_parser_create();
		$xml = xml_parse($parser, '<begin>' . $v_message . '</begin>', true);
		if(!$xml) {
			$error_code = xml_get_error_code($parser);
			$error_string = xml_error_string($error_code);
			die($error_string);
		}
		
		// Insert topic into database
		$insert = sqlite_exec($db, "
				INSERT INTO `topics` 
				VALUES (
					NULL,
					'{$v_forum}', 
					'{$v_subject}', 
					'0', 
					'0', 
					'0'
				)
			") or die($settings['msg_db_error']);
		
		// Get just entered topic info
		// << Possibly get insert ID instead. >>
		$query = sqlite_query($db, "
				SELECT `id` 
				FROM `topics` 
				ORDER BY `id` DESC 
				LIMIT 0, 1
			", SQLITE_ASSOC);
		$topic_id = sqlite_fetch_single($query);
		
		// Insert message as first post
		$insert = sqlite_exec($db, "
				INSERT INTO `posts` 
				VALUES (
					NULL, 
					'{$topic_id}', 
					'{$username}', 
					'{$time}', 
					'{$v_message}'
				)
			") or die($settings['msg_db_error']);
		
		// Go to new topic
		header('Location: ./topic.php?id=' . $topic_id);
		
		break;
	
	case "reply":
		// Insert reply
		
		// Stip tags from topic name and check it isn't empty
		$v_topic = strip_tags($v_topic);
		if(empty($v_topic))
			header('Location: ./');
		
		// Pull topic locked data
		$query = sqlite_query($db, "
				SELECT `locked` 
				FROM `topics` 
				WHERE `id` = '{$v_topic}' 
				LIMIT 0, 1
			", SQLITE_ASSOC);
		$locked = sqlite_fetch_single($query);
		
		// If topic is locked, redirect back, posting is disallowed
		if($locked == '1')
			header('Location: topic.php?id=' . $v_topic);
		
		// XML parse input to check validity
		$parser = xml_parser_create();
		$xml = xml_parse($parser, $v_message, true);
		if(!$xml) {
			$error_code = xml_get_error_code($parser);
			$error_string = xml_error_string($error_code);
			if($error_string !== 'Empty document')
				die($error_string);
		}
		
		// Insert into database
		$insert = sqlite_exec($db, "
				INSERT INTO `posts` 
				VALUES (
					NULL, 
					'{$v_topic}', 
					'{$username}', 
					'{$time}', 
					'{$v_message}'
				)
			") or die($settings['msg_db_error']);
		
		// Redirect to new post
		header('Location: topic.php?id=' . $v_topic . '#p' . $time);
		break;
}
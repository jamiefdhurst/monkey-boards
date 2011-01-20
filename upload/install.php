<?php

include('include/config.inc.php');
if(file_exists($database)) {
	header('Location: ./index.php');
	exit;
}

$schema = "CREATE TABLE categories ( id INTEGER PRIMARY KEY, name TEXT(50) NOT NULL );
CREATE TABLE forums ( id TEXT(50) NOT NULL, category INTEGER NOT NULL, name TEXT(50) NOT NULL, blurb VARCHAR(100) NOT NULL );
CREATE TABLE emoticons (id INTEGER PRIMARY KEY, image VARCHAR NOT NULL, pattern VARCHAR UNIQUE, title TEXT);
INSERT INTO emoticons VALUES (NULL, 'include/emoticons/smile.png', ':-)', 'smile');
INSERT INTO emoticons VALUES (NULL, 'include/emoticons/frown.png', ':-(', 'frown');
INSERT INTO emoticons VALUES (NULL, 'include/emoticons/grin.png', ':-D', 'grin');
INSERT INTO emoticons VALUES (NULL, 'include/emoticons/cry.png', ';-(', 'cry');
INSERT INTO emoticons VALUES (NULL, 'include/emoticons/wink.png', ';-)', 'wink');
INSERT INTO emoticons VALUES (NULL, 'include/emoticons/tongue.png', ':-p', 'tongue');
INSERT INTO emoticons VALUES (NULL, 'include/emoticons/surprised.png', ':-o', 'surprised');
INSERT INTO emoticons VALUES (NULL, 'include/emoticons/cool.png', '8-)', 'cool');
INSERT INTO emoticons VALUES (NULL, 'include/emoticons/undecided.png', ':-/', 'undecided');
INSERT INTO emoticons VALUES (NULL, 'include/emoticons/embarassed.png', ':-$', 'embarassed');
INSERT INTO emoticons VALUES (NULL, 'include/emoticons/smile.png', ':)', 'smile');
INSERT INTO emoticons VALUES (NULL, 'include/emoticons/frown.png', ':(', 'frown');
INSERT INTO emoticons VALUES (NULL, 'include/emoticons/grin.png', ':D', 'grin');
INSERT INTO emoticons VALUES (NULL, 'include/emoticons/cry.png', ';(', 'cry');
INSERT INTO emoticons VALUES (NULL, 'include/emoticons/wink.png', ';)', 'wink');
INSERT INTO emoticons VALUES (NULL, 'include/emoticons/tongue.png', ':p', 'tongue');
INSERT INTO emoticons VALUES (NULL, 'include/emoticons/surprised.png', ':o', 'surprised');
INSERT INTO emoticons VALUES (NULL, 'include/emoticons/cool.png', '8)', 'cool');
INSERT INTO emoticons VALUES (NULL, 'include/emoticons/undecided.png', ':/', 'undecided');
INSERT INTO emoticons VALUES (NULL, 'include/emoticons/embarassed.png', ':$', 'embarassed');
CREATE TABLE posts (id INTEGER NOT NULL PRIMARY KEY, topic INTEGER NOT NULL, username VARCHAR(50) NOT NULL, timestamp TIMESTAMP(10) NOT NULL, message VARCHAR NOT NULL);
CREATE TABLE settings (id TEXT NOT NULL PRIMARY KEY, value VARCHAR NOT NULL, preserve BOOLEAN NOT NULL);
INSERT INTO settings VALUES ('email_from', 'nobody@nowhere.com', 0);
INSERT INTO settings VALUES ('date_format', 'Y-m-d H:i:s', 0);
INSERT INTO settings VALUES ('style', 'default', 0);
CREATE TABLE topics ( id INTEGER NOT NULL PRIMARY KEY, forum TEXT(50) NOT NULL, subject VARCHAR(70) NOT NULL, views INTEGER NOT NULL DEFAULT '0', locked BOOLEAN NOT NULL DEFAULT '0', sticky BOOLEAN NOT NULL DEFAULT '0' );
CREATE TABLE users ( username VARCHAR(50) NOT NULL PRIMARY KEY, password BLOB(40) NOT NULL, email VARCHAR(300) NOT NULL, type INTEGER(1) DEFAULT '1', disabled BOOLEAN(1) DEFAULT '0', registered TIMESTAMP(10) NOT NULL, name TEXT(100));";

require('include/start.inc.php');

$phpversion = phpversion();
$compatible = version_compare($phpversion, "5.0.0");
$magic_quotes = get_magic_quotes_gpc();

if($compatible < 0) $install_errors[] = '<strong>PHP version:</strong> You must have PHP version 5.0.0 or greater to use this script.';
if(!function_exists('sqlite_open')) $install_errors[] = '<strong>SQLite support:</strong> You do not appear to have SQLite support. To use this script you need to load the module in PHP.';
if($magic_quotes == 1) $install_errors[] = '<strong>Magic quotes:</strong> To use this script <var>magic_quotes_gpc</var> <strong>must</strong> be set to <var>off</var> in the php.ini configuration file.';

if(isset($install_errors)) {
	$tpl->assign('install_errors', $install_errors);
}
else {
	$tpl->assign('install_errors', 'none');
}

if(!empty($v_database)) {

$uri = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$base_address = dirname($uri);

if(!ereg("^[a-z][a-z0-9-]*$", $v_database)) exit('Database name invalid. Alphanumeric characters only.');

validate_username($v_username);
if(empty($v_password) or empty($v_cpassword)) exit('Password missing. Please go back and type your password twice.');
if($v_password !== $v_cpassword) exit('Passwords do not match. Please go back and try again.');
if(empty($v_email)) exit('Email address missing. Please go back and type your email address.');
validate_email($v_email);
if(empty($v_site_title)) exit('Please go back and type a site title.');
$v_site_title = sqlite_escape_string($v_site_title);

$db_path = 'include/'.$v_database.'.db';
if(file_exists($db_path)) exit('Database already in use. Please go back and choose another.');

$db = sqlite_open($db_path) or die('Could not open or create database file: '.$db_path.'. Check write permissions.');

$lines = count($schema);
$schema = explode("\r\n", $schema);
$count = 0;
foreach($schema  as $line) {
	$count++;
	$query = sqlite_exec($db, $line) or die("Error executing line $count of $lines");
}

$hashed_password = sha1($v_password);
$create_admin_user = sqlite_exec($db, "INSERT INTO users VALUES ('$v_username', '$hashed_password', '$v_email', '3', '0', '$time', NULL);");

$insert = "INSERT INTO settings VALUES ('site_title', '$v_site_title', '1');
INSERT INTO settings VALUES ('base_address', '$base_address', '0');
INSERT INTO categories VALUES ( NULL, 'Test Category');
INSERT INTO forums VALUES ('test', 1, 'Test Forum', 'This is a test forum.');
INSERT INTO topics VALUES ('1', 'test', 'Test post', '2', '0', '0');
INSERT INTO posts VALUES ('1', '1', '$v_username', '$time', 'If you are looking at this, the installation of Monkey Boards appears to have worked! Now log in and head over to the administration control panel to configure your forum.');";

$query = sqlite_exec($db, $insert);

$handle = fopen('include/config.inc.php', 'w');
fwrite($handle, '<?php $database = "'.$db_path.'"; ?>');
fclose($handle);

header('Location: index.php');

}

$tpl->assign('style', 'default');
$tpl->display("install.tpl");

?>

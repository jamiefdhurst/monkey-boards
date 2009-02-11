CREATE TABLE `categories` (
	`id` INTEGER PRIMARY KEY,
	`name` TEXT(50) NOT NULL
);
CREATE TABLE `forums` (
	`id` TEXT(50) NOT NULL,
	`category` INTEGER NOT NULL, 
	`name` TEXT(50) NOT NULL, 
	`blurb` VARCHAR(100) NOT NULL
);
CREATE TABLE `emoticons` (
	`id` INTEGER PRIMARY KEY,
	`image` VARCHAR NOT NULL,
	`pattern` VARCHAR UNIQUE,
	`title` TEXT
);
INSERT INTO `emoticons`
VALUES (
	NULL,
	'include/emoticons/smile.png', ':-)',
	'smile'
);
INSERT INTO `emoticons` 
VALUES (
	NULL, 
	'include/emoticons/frown.png', 
	':-(', 
	'frown'
);
INSERT INTO `emoticons` 
VALUES (
	NULL, 
	'include/emoticons/grin.png', 
	':-D', 
	'grin'
);
INSERT INTO `emoticons` 
VALUES (
	NULL, 
	'include/emoticons/cry.png', 
	';-(', 
	'cry'
);
INSERT INTO `emoticons` 
VALUES (
	NULL, 
	'include/emoticons/wink.png', 
	';-)', 
	'wink'
);
INSERT INTO `emoticons` 
VALUES (
	NULL, 
	'include/emoticons/tongue.png', 
	':-p', 
	'tongue'
);
INSERT INTO `emoticons` 
VALUES (
	NULL, 
	'include/emoticons/surprised.png', 
	':-o', 
	'surprised'
);
INSERT INTO `emoticons` 
VALUES (
	NULL, 
	'include/emoticons/cool.png', 
	'8-)', 
	'cool'
);
INSERT INTO `emoticons` 
VALUES (
	NULL, 
	'include/emoticons/undecided.png', 
	':-/', 
	'undecided'
);
INSERT INTO `emoticons` 
VALUES (
	NULL, 
	'include/emoticons/embarassed.png', 
	':-$', 
	'embarrassed'
);
INSERT INTO `emoticons` 
VALUES (
	NULL, 
	'include/emoticons/smile.png', 
	':)', 
	'smile'
);
INSERT INTO `emoticons` 
VALUES (
	NULL, 
	'include/emoticons/frown.png', 
	':(',
	'frown'
);
INSERT INTO `emoticons` 
VALUES (
	NULL, 
	'include/emoticons/grin.png', 
	':D', 
	'grin'
);
INSERT INTO `emoticons` 
VALUES (
	NULL, 
	'include/emoticons/cry.png', 
	';(', 
	'cry'
);
INSERT INTO `emoticons` 
VALUES (
	NULL, 
	'include/emoticons/wink.png', 
	';)', 
	'wink'
);
INSERT INTO `emoticons` 
VALUES (
	NULL, 
	'include/emoticons/tongue.png', 
	':p', 
	'tongue'
);
INSERT INTO `emoticons` 
VALUES (
	NULL, 
	'include/emoticons/surprised.png', 
	':o',
	'surprised'
);
INSERT INTO `emoticons` 
VALUES (
	NULL, 
	'include/emoticons/cool.png', 
	'8)', 
	'cool'
);
INSERT INTO `emoticons` 
VALUES (
	NULL, 
	'include/emoticons/undecided.png', 
	':/', 
	'undecided'
);
INSERT INTO `emoticons` 
VALUES (
	NULL, 
	'include/emoticons/embarassed.png', 
	':$', 
	'embarrassed'
);
CREATE TABLE `posts` (
	`id` INTEGER NOT NULL PRIMARY KEY, 
	`topic` INTEGER NOT NULL, 
	`username` VARCHAR(50) NOT NULL, 
	`timestamp` TIMESTAMP(10) NOT NULL, 
	`message` VARCHAR NOT NULL
);
CREATE TABLE `settings` (
	`id` TEXT NOT NULL PRIMARY KEY, 
	`value` VARCHAR NOT NULL, 
	`preserve` BOOLEAN NOT NULL
);
INSERT INTO `settings` VALUES (
	'email_from', 
	'nobody@nowhere.com', 
	0
);
INSERT INTO `settings` VALUES (
	'date_format', 
	'Y-m-d H:i:s', 
	0
);
INSERT INTO `settings` VALUES (
	'style', 
	'default', 
	0
);
CREATE TABLE `topics` ( 
	`id` INTEGER NOT NULL PRIMARY KEY, 
	`forum` TEXT(50) NOT NULL, 
	`subject` VARCHAR(70) NOT NULL, 
	`views` INTEGER NOT NULL DEFAULT '0', 
	`locked` BOOLEAN NOT NULL DEFAULT '0', 
	`sticky` BOOLEAN NOT NULL DEFAULT '0'
);
CREATE TABLE `users` (
	`username` VARCHAR(50) NOT NULL PRIMARY KEY, 
	`password` BLOB(40) NOT NULL, 
	`email` VARCHAR(300) NOT NULL, 
	`type` INTEGER(1) DEFAULT '1', 
	`disabled` BOOLEAN(1) DEFAULT '0', 
	`registered` TIMESTAMP(10) NOT NULL, 
	`name` TEXT(100)
);

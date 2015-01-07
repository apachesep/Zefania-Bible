CREATE TABLE IF NOT EXISTS `#__zefaniabible_bible_names` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`bible_name` VARCHAR(255) NOT NULL,
	`bible_xml_file` TEXT NOT NULL,
	`xml_audio_url` TEXT NOT NULL,
	`alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
	`ordering` int(11) NOT NULL DEFAULT '0',
	`published` tinyint(3) NOT NULL DEFAULT '0',
	`checked_out` int(11) unsigned NOT NULL DEFAULT '0',
	`checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created_by` int(11) unsigned NOT NULL DEFAULT '0',
	`modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified_by` int(11) unsigned NOT NULL DEFAULT '0',
	`access` int(11) unsigned NOT NULL DEFAULT '0',
	`language` char(7) NOT NULL COMMENT 'The language code for the article.',
	`metadata` text NOT NULL,
	`metakey` text NOT NULL,
	`metadesc` text NOT NULL,
	PRIMARY KEY (id)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;


CREATE TABLE IF NOT EXISTS `#__zefaniabible_bible_text` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`bible_id` INT(3) NOT NULL,
	`book_id` INT(3) NOT NULL,
	`chapter_id` INT(3) NOT NULL,
	`verse_id` INT(3) NOT NULL,
	`verse` LONGTEXT NOT NULL,
	PRIMARY KEY (id)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;


CREATE TABLE IF NOT EXISTS `#__zefaniabible_dictionary_info` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`name` TEXT NOT NULL,
	`xml_file_url` VARCHAR(500) NOT NULL,
	`alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
	`ordering` int(11) NOT NULL DEFAULT '0',
	`published` tinyint(3) NOT NULL DEFAULT '0',
	`checked_out` int(11) unsigned NOT NULL DEFAULT '0',
	`checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created_by` int(11) unsigned NOT NULL DEFAULT '0',
	`modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified_by` int(11) unsigned NOT NULL DEFAULT '0',
	`access` int(11) unsigned NOT NULL DEFAULT '0',
	`language` char(7) NOT NULL COMMENT 'The language code for the article.',
	`metadata` text NOT NULL,
	`metakey` text NOT NULL,
	`metadesc` text NOT NULL,
	PRIMARY KEY (id)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;


CREATE TABLE IF NOT EXISTS `#__zefaniabible_dictionary_detail` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`dict_id` INT(11) NOT NULL,
	`item` VARCHAR(255) NOT NULL,
	`description` TEXT NOT NULL,
	PRIMARY KEY (id)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;


CREATE TABLE IF NOT EXISTS `#__zefaniabible_zefaniacomment` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(255) NOT NULL,
	`full_name` VARCHAR(255) NOT NULL,
	`file_location` VARCHAR(255) NOT NULL,
	`alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
	`ordering` int(11) NOT NULL DEFAULT '0',
	`published` tinyint(3) NOT NULL DEFAULT '0',
	`checked_out` int(11) unsigned NOT NULL DEFAULT '0',
	`checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created_by` int(11) unsigned NOT NULL DEFAULT '0',
	`modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified_by` int(11) unsigned NOT NULL DEFAULT '0',
	`access` int(11) unsigned NOT NULL DEFAULT '0',
	`language` char(7) NOT NULL COMMENT 'The language code for the article.',
	`metadata` text NOT NULL,
	`metakey` text NOT NULL,
	`metadesc` text NOT NULL,
	PRIMARY KEY (id)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;


CREATE TABLE IF NOT EXISTS `#__zefaniabible_comment_text` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`bible_id` INT(11) NOT NULL,
	`book_id` INT(11) NOT NULL,
	`chapter_id` INT(11) NOT NULL,
	`verse_id` INT(11) NOT NULL,
	`verse` LONGTEXT NOT NULL,
	PRIMARY KEY (id)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__zefaniabible_zefaniareading` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL,
	`description` LONGTEXT NOT NULL,
	`alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
	`ordering` int(11) NOT NULL DEFAULT '0',
	`published` tinyint(3) NOT NULL DEFAULT '0',
	`checked_out` int(11) unsigned NOT NULL DEFAULT '0',
	`checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created_by` int(11) unsigned NOT NULL DEFAULT '0',
	`modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`modified_by` int(11) unsigned NOT NULL DEFAULT '0',
	`access` int(11) unsigned NOT NULL DEFAULT '0',
	`language` char(7) NOT NULL COMMENT 'The language code for the article.',
	`metadata` text NOT NULL,
	`metakey` text NOT NULL,
	`metadesc` text NOT NULL,
	PRIMARY KEY (id)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;


CREATE TABLE IF NOT EXISTS `#__zefaniabible_zefaniareadingdetails` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`plan` INT(11) NOT NULL,
	`book_id` INT(11) NOT NULL,
	`begin_chapter` INT(11) NOT NULL,
	`begin_verse` INT(11) NOT NULL,
	`end_chapter` INT(11) NOT NULL,
	`end_verse` INT(11) NOT NULL,
	`day_number` INT(11) NOT NULL,
	`description` TEXT NOT NULL,
	`ordering` int(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (id)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__zefaniabible_zefaniauser` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`user_name` VARCHAR(255) NOT NULL,
	`plan` INT(11) NOT NULL,
	`bible_version` INT(11) NOT NULL,
	`user_id` INT(11) NOT NULL,
	`email` VARCHAR(255) NOT NULL,
	`send_reading_plan_email` BOOLEAN NOT NULL,
	`send_verse_of_day_email` BOOLEAN NOT NULL,
	`reading_start_date` DATE NOT NULL,
	PRIMARY KEY (id)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;


CREATE TABLE IF NOT EXISTS `#__zefaniabible_zefaniapublish` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(255) NOT NULL,
	`last_send_date` DATE NOT NULL,
	PRIMARY KEY (id)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;

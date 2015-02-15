CREATE TABLE IF NOT EXISTS `#__zefaniabible_bible_names` (
	`id` int(11) unsigned  AUTO_INCREMENT,
	`params` text,
	`bible_name` VARCHAR(255) ,
	`bible_xml_file` TEXT ,
	`xml_audio_url` TEXT ,
	`alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin  DEFAULT '',
	`ordering` int(11)  ,
	`published` tinyint(3) ,
	`checked_out` int(11) ,
	`checked_out_time` datetime  ,
	`created` datetime  ,
	`created_by` int(11) ,
	`modified` datetime  ,
	`modified_by` int(11) ,
	`access` int(11) ,
	`language` char(7) ,
	`metadata` text ,
	`metakey` text ,
	`metadesc` text ,
	PRIMARY KEY (id)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;


CREATE TABLE IF NOT EXISTS `#__zefaniabible_bible_text` (
	`id` int(11) unsigned  AUTO_INCREMENT,
	`params` text,
	`bible_id` INT(3) ,
	`book_id` INT(3) ,
	`chapter_id` INT(3) ,
	`verse_id` INT(3) ,
	`verse` LONGTEXT ,
	PRIMARY KEY (id)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;


CREATE TABLE IF NOT EXISTS `#__zefaniabible_dictionary_info` (
	`id` int(11) unsigned  AUTO_INCREMENT,
	`params` text,
	`name` TEXT ,
	`xml_file_url` VARCHAR(500) ,
	`alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin  DEFAULT '',
	`ordering` int(11)  DEFAULT '0',
	`published` tinyint(3)  DEFAULT '0',
	`checked_out` int(11) unsigned  DEFAULT '0',
	`checked_out_time` datetime  DEFAULT '0000-00-00 00:00:00',
	`created` datetime  DEFAULT '0000-00-00 00:00:00',
	`created_by` int(11) unsigned  DEFAULT '0',
	`modified` datetime  DEFAULT '0000-00-00 00:00:00',
	`modified_by` int(11) unsigned  DEFAULT '0',
	`access` int(11) unsigned  DEFAULT '0',
	`language` char(7)  COMMENT 'The language code for the article.',
	`metadata` text ,
	`metakey` text ,
	`metadesc` text ,
	PRIMARY KEY (id)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;


CREATE TABLE IF NOT EXISTS `#__zefaniabible_dictionary_detail` (
	`id` int(11) unsigned  AUTO_INCREMENT,
	`params` text,
	`dict_id` INT(11) ,
	`item` VARCHAR(255) ,
	`description` TEXT ,
	PRIMARY KEY (id)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;


CREATE TABLE IF NOT EXISTS `#__zefaniabible_zefaniacomment` (
	`id` int(11) unsigned  AUTO_INCREMENT,
	`params` text,
	`title` VARCHAR(255) ,
	`full_name` VARCHAR(255) ,
	`file_location` VARCHAR(255) ,
	`alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin  DEFAULT '',
	`ordering` int(11)  DEFAULT '0',
	`published` tinyint(3)  DEFAULT '0',
	`checked_out` int(11) unsigned  DEFAULT '0',
	`checked_out_time` datetime  DEFAULT '0000-00-00 00:00:00',
	`created` datetime  DEFAULT '0000-00-00 00:00:00',
	`created_by` int(11) unsigned  DEFAULT '0',
	`modified` datetime  DEFAULT '0000-00-00 00:00:00',
	`modified_by` int(11) unsigned  DEFAULT '0',
	`access` int(11) unsigned  DEFAULT '0',
	`language` char(7)  COMMENT 'The language code for the article.',
	`metadata` text ,
	`metakey` text ,
	`metadesc` text ,
	PRIMARY KEY (id)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;


CREATE TABLE IF NOT EXISTS `#__zefaniabible_comment_text` (
	`id` int(11) unsigned  AUTO_INCREMENT,
	`params` text,
	`bible_id` INT(11) ,
	`book_id` INT(11) ,
	`chapter_id` INT(11) ,
	`verse_id` INT(11) ,
	`verse` LONGTEXT ,
	PRIMARY KEY (id)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__zefaniabible_zefaniauser` (
	`id` int(11) unsigned  AUTO_INCREMENT,
	`params` text,
	`user_name` VARCHAR(255) ,
	`plan` INT(11) ,
	`bible_version` INT(11) ,
	`user_id` INT(11) ,
	`email` VARCHAR(255) ,
	`send_reading_plan_email` BOOLEAN ,
	`send_verse_of_day_email` BOOLEAN ,
	`reading_start_date` DATE ,
	PRIMARY KEY (id)
)
CHARACTER SET utf8
COLLATE utf8_general_ci;


CREATE TABLE IF NOT EXISTS `#__zefaniabible_zefaniapublish` (
	`id` int(11) NOT NULL auto_increment,
	`params` text NOT NULL default '',
	`title` VARCHAR(255) ,
	`last_send_date` DATE ,

	PRIMARY KEY  (`id`)
	
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `#__zefaniabible_zefaniapublish` (`id`, `params`, `title`, `last_send_date`) VALUES
(1,'','COM_ZEFANIABIBLE_VERSE_OF_DAY_EMAIL', '2012-01-01'),
(2,'','COM_ZEFANIABIBLE_READING_PLAN_EMAIL', '2012-01-01'),
(3,'','COM_ZEFANIABIBLE_FACEBOOK', '2012-01-01');
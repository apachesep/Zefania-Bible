CREATE TABLE IF NOT EXISTS `#__zefaniabible_dictionary_detail` (
	`id` int(11) NOT NULL auto_increment,
	`params` text NOT NULL default '',
	`dict_id` int(11) NOT NULL,	
	`item` VARCHAR(255) ,
	`description` TEXT ,
	
	PRIMARY KEY  (`id`)
	
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__zefaniabible_dictionary_info` (
	`id` int(11) NOT NULL auto_increment,
	`params` text NOT NULL default '',
	`name` VARCHAR(255) ,
	`alias` VARCHAR(255) ,
	`xml_file_url` text NOT NULL,
	`ordering` INT(11) ,
	`publish` TINYINT ,
	
	PRIMARY KEY  (`id`)
	
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__zefaniabible_bible_text` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `params` text NOT NULL,
  `bible_id` int(11) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `chapter_id` int(11) DEFAULT NULL,
  `verse_id` int(11) DEFAULT NULL,
  `verse` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__zefaniabible_bible_names` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `params` text NOT NULL,
  `bible_name` varchar(255) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  `xml_file_url` text NOT NULL,
  `xml_audio_url` text NOT NULL,
  `publish` tinyint(4) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__zefaniabible_zefaniauser` (
	`id` int(11) NOT NULL auto_increment,
	`params` text NOT NULL default '',
	`user_name` VARCHAR(255) ,
	`plan` INT(11) ,
	`bible_version` INT(11) ,
	`user_id` INT(11) ,
	`email` VARCHAR(255) ,
	`send_reading_plan_email` TINYINT ,
	`send_verse_of_day_email` TINYINT ,
	`reading_start_date` DATE ,

	PRIMARY KEY  (`id`)
	
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__zefaniabible_zefaniacomment` (
	`id` int(11) NOT NULL auto_increment,
	`params` text NOT NULL default '',
	`title` VARCHAR(255) ,
	`alias` VARCHAR(255) ,
	`full_name` VARCHAR(255) ,
	`file_location` VARCHAR(255) ,
	`ordering` INT(11) ,
	`publish` TINYINT ,

	PRIMARY KEY  (`id`)
	
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__zefaniabible_comment_text` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `params` text NOT NULL,
  `bible_id` int(11) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `chapter_id` int(11) DEFAULT NULL,
  `verse_id` int(11) DEFAULT NULL,
  `verse` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__zefaniabible_zefaniabiblebooknames` (
	`id` int(11) NOT NULL auto_increment,
	`params` text NOT NULL default '',
	`bible_book_name` VARCHAR(255) ,
	`ordering` INT(11) ,
	`publish` TINYINT ,

	PRIMARY KEY  (`id`)
	
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
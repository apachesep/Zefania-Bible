CREATE TABLE IF NOT EXISTS `#__zefaniabible_biblenames` (
	`id` int(11) NOT NULL auto_increment,
	`bible_name` VARCHAR(255) ,
	`alias` VARCHAR(255) ,
	`published` INT(11) ,
	`xml_bible_file_location` VARCHAR(255) ,
	`xml_audio_file_location` VARCHAR(255) ,
	`ordering` INT(11) ,
	`access` INT(11) DEFAULT 1 ,

	PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__zefaniabible_zefaniacomment` (
	`id` int(11) NOT NULL auto_increment,
	`title` VARCHAR(255) ,
	`alias` VARCHAR(255) ,
	`full_name` VARCHAR(255) ,
	`file_location` VARCHAR(255) ,
	`ordering` INT(11) ,
	`access` INT(11) DEFAULT 1 ,
	`published` INT(11) ,

	PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__zefaniabible_zefaniareading` (
	`id` int(11) NOT NULL auto_increment,
	`name` VARCHAR(255) ,
	`alias` VARCHAR(255) ,
	`description` TEXT ,
	`ordering` INT(11) ,
	`published` INT(11) ,

	PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__zefaniabible_zefaniareadingdetails` (
	`id` int(11) NOT NULL auto_increment,
	`plan` INT(11) ,
	`book_id` INT(11) ,
	`begin_chapter` INT(11) ,
	`begin_verse` INT(11) ,
	`end_chapter` INT(11) ,
	`end_verse` INT(11) ,
	`day_number` INT(11) ,
	`description` TEXT ,
	`ordering` INT(11) ,

	PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__zefaniabible_zefaniauser` (
	`id` int(11) NOT NULL auto_increment,
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

CREATE TABLE IF NOT EXISTS `#__zefaniabible_zefaniaverseofday` (
	`id` int(11) NOT NULL auto_increment,
	`book_name` INT(11) ,
	`chapter_number` INT(11) ,
	`begin_verse` INT(11) ,
	`end_verse` INT(11) ,
	`ordering` INT(11) ,
	`published` INT(11) ,

	PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__zefaniabible_zefaniabibledictionaryinfo` (
	`id` int(11) NOT NULL auto_increment,
	`name` VARCHAR(255) ,
	`alias` VARCHAR(10) ,
	`xml_file_url` VARCHAR(255) ,
	`ordering` INT(11) ,
	`published` INT(11) ,
	`access` INT(11) DEFAULT 1 ,

	PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




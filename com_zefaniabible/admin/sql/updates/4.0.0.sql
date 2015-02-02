ALTER TABLE `#__zefaniabible_bible_names`
	CHANGE `publish` `published` tinyint(3),
	CHANGE `bible_xml_file` `xml_file_url` text,
	ADD `checked_out` int(11) ,
	ADD `checked_out_time` datetime  ,
	ADD `created` datetime  ,
	ADD `created_by` int(11) ,
	ADD `modified` datetime  ,
	ADD `modified_by` int(11) ,
	ADD `access` int(11) ,
	ADD `language` char(7) ,
	ADD `metadata` text ,
	ADD `metakey` text ,
	ADD `metadesc` text,
	DROP COLUMN `desc`;
	
ALTER TABLE `#__zefaniabible_dictionary_info` 
	CHANGE `publish` `published` tinyint(3),
	ADD `checked_out` int(11) unsigned  DEFAULT '0',
	ADD `checked_out_time` datetime  DEFAULT '0000-00-00 00:00:00',
	ADD `created` datetime  DEFAULT '0000-00-00 00:00:00',
	ADD `created_by` int(11) unsigned  DEFAULT '0',
	ADD `modified` datetime  DEFAULT '0000-00-00 00:00:00',
	ADD `modified_by` int(11) unsigned  DEFAULT '0',
	ADD `access` int(11) unsigned  DEFAULT '0',
	ADD `language` char(7)  COMMENT 'The language code for the article.',
	ADD `metadata` text ,
	ADD `metakey` text ,
	ADD `metadesc` text;

ALTER TABLE `#__zefaniabible_zefaniacomment`
	CHANGE `publish` `published` tinyint(3),
	ADD `checked_out` int(11) unsigned  DEFAULT '0',
	ADD `checked_out_time` datetime  DEFAULT '0000-00-00 00:00:00',
	ADD `created` datetime  DEFAULT '0000-00-00 00:00:00',
	ADD `created_by` int(11) unsigned  DEFAULT '0',
	ADD `modified` datetime  DEFAULT '0000-00-00 00:00:00',
	ADD `modified_by` int(11) unsigned  DEFAULT '0',
	ADD `access` int(11) unsigned  DEFAULT '0',
	ADD `language` char(7)  COMMENT 'The language code for the article.',
	ADD `metadata` text ,
	ADD `metakey` text ,
	ADD `metadesc` text;
 
ALTER TABLE `#__zefaniabible_zefaniareading`
	CHANGE `publish` `published` tinyint(3),
	ADD `checked_out` int(11) ,
	ADD `checked_out_time` datetime ,
	ADD `created` datetime ,
	ADD `created_by` int(11) ,
	ADD `modified` datetime ,
	ADD `modified_by` int(11) ,
	ADD `access` int(11) ,
	ADD `language` char(7) ,
	ADD `metadata` text ,
	ADD `metakey` text ,
	ADD `metadesc` text;

ALTER TABLE `#__zefaniabible_zefaniaverseofday`
	CHANGE `publish` `published` tinyint(3),
	ADD `checked_out` int(11),
	ADD `checked_out_time` datetime,
	ADD `created` datetime,
	ADD `created_by` int(11),
	ADD `modified` datetime,
	ADD `modified_by` int(11),
	ADD `access` int(11);





CREATE TABLE IF NOT EXISTS `#__zefaniabible_crossref` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `params` text NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `chapter_id` int(11) DEFAULT NULL,
  `verse_id` int(11) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL,
  `word` longtext NOT NULL,
  `reference` longtext NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rex_51_cache_article` (
  `cache_id` int(10) unsigned NOT NULL auto_increment,
  `article_id` int(11) NOT NULL default '',
  `clang` int(11) NOT NULL default 0,
  `active` tinyint(4) NOT NULL default '0',
  `lifetime` int(11) NOT NULL default '0',
  `createdate` int(11) NOT NULL default '0',
  `updatedate` int(11) NOT NULL default '0',
  `createuser` varchar(255) NOT NULL default '',
  `updateuser` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`cache_id`)
) TYPE=MyISAM;
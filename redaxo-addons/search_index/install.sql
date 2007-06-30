CREATE TABLE `%TABLE_PREFIX%%TEMP_PREFIX%12_search_index` 
(
  `id` int(100) NOT NULL default '0',
  `path` varchar(100) NOT NULL default '',
  `status` tinyint(2) NOT NULL default '0',
  `clang` tinyint(2) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `keywords` text NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY  (`id`,`clang`)
) TYPE=MyISAM;
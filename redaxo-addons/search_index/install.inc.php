<?php

// CREATE/UPDATE DATABASE AND CREATE/UPDATE MODULES
$sql = new sql();
$sql->query("
CREATE TABLE `". $REX['TABLE_PREFIX'] . $REX['TEMP_PREFIX'] ."12_search_index` (
  `id` int(100) NOT NULL default '0',
  `path` varchar(100) NOT NULL default '',
  `status` tinyint(2) NOT NULL default '0',
  `clang` tinyint(2) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `keywords` text NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY  (`id`,`clang`)
) TYPE=MyISAM;
");

$REX['ADDON']['install']["search_index"] = 1;
 
// ERRMSG IN CASE: $REX[ADDON][installmsg]["search_index"] = "Leider konnte nichts installiert werden da.";

?>
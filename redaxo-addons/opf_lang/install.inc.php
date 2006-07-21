<?php

// CREATE/UPDATE DATABASE
$sql = new sql;
// $sql->debugsql=1;


// ----- usertabelle
$sql->setQuery("
CREATE TABLE `rex_opf_lang` (
`id` INT NOT NULL AUTO_INCREMENT ,
`clang` VARCHAR( 255 ) NOT NULL ,
`name` TEXT NOT NULL ,
`replacename` VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY ( `id` )
);");

$sql->setQuery("ALTER TABLE `rex_opf_lang` CHANGE `name` `name` TEXT NOT NULL");


$REX[ADDON][install]["opf_lang"] = 1;
// ERRMSG IN CASE: $REX[ADDON][installmsg]["import_export"] = "Leider konnte nichts installiert werden da.";

?>
<?php

$sql = new sql;

// Tabelle erstellen - wenn noch nicht existent
$sql->setQuery("
CREATE TABLE `rex_8_newsletter` (
`id` INT NOT NULL AUTO_INCREMENT ,
`email` VARCHAR( 255 ) NOT NULL ,
`firstname` VARCHAR( 255 ) NOT NULL ,
`name` VARCHAR( 255 ) NOT NULL ,
`status` INT NOT NULL ,
PRIMARY KEY ( `id` )
) ");

// alter table wenn felder noch nicht da.
$sql->setQuery("
ALTER TABLE `rex_8_newsletter` ADD `bounce` VARCHAR( 255 ) NOT NULL AFTER `name` ,
ADD `last_nlid` VARCHAR( 255 ) NOT NULL AFTER `bounce` ,
ADD `code` VARCHAR( 255 ) NOT NULL AFTER `last_nlid` ");

$REX[ADDON][install]["newsletter"] = 1;
// MSG IN CASE: $REX[ADDON][installmsg]["import_export"] = "Leider konnte nichts installiert werden da.";



?>
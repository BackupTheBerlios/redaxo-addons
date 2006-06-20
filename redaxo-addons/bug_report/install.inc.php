<?php

// CREATE/UPDATE DATABASE
$sql = new sql;
// $sql->debugsql=1;


// ----- usertabelle
$sql->setQuery("
CREATE TABLE `rex_21_bug_report` (
`id` INT NOT NULL AUTO_INCREMENT ,
`title` VARCHAR( 255 ) NOT NULL ,
`description` TEXT NOT NULL ,
`status` INT NOT NULL ,
PRIMARY KEY ( `id` )
);");


$REX[ADDON][install]["bug_report"] = 1;

// ERRMSG IN CASE: $REX[ADDON][installmsg]["import_export"] = "Leider konnte nichts installiert werden da.";


?>
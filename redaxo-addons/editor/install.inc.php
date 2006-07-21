<?php
/** 
 * Addon: Editor
 * @package redaxo3 
 * @version $Id: install.inc.php,v 1.1 2006/07/21 13:53:09 kills Exp $
 */


global $DB;


if (isset($_GET['phpmyadmin']) and $_GET['phpmyadmin'] == 1):
  $REX['ADDON']['install']['editor'] = 1;
  
else: 
// INSTALL DB

$installsql = new sql;

$installsql->query("DROP TABLE IF EXISTS `rex_14_setting`");

$installsql->query("CREATE TABLE `rex_14_setting` (
`id` INT NOT NULL AUTO_INCREMENT ,
`showJsElements` tinyint(1) NOT NULL,
`showProperty` tinyint(1) NOT NULL,
`showColor` tinyint(1) NOT NULL,
`showHighlight` tinyint(1) NOT NULL,
`editorWindow` varchar(5) NOT NULL DEFAULT '',
`hltSelector` varchar(6) NOT NULL DEFAULT '',
`hltProperty` varchar(6) NOT NULL DEFAULT '',
`hltValue` varchar(6) NOT NULL DEFAULT '',
`hltDeclaration` varchar(6) NOT NULL DEFAULT '',
`color1` varchar(6) NOT NULL DEFAULT '',
`color2` varchar(6) NOT NULL DEFAULT '',
`color3` varchar(6) NOT NULL DEFAULT '',
`color4` varchar(6) NOT NULL DEFAULT '',
`color5` varchar(6) NOT NULL DEFAULT '',
`color6` varchar(6) NOT NULL DEFAULT '',
`color7` varchar(6) NOT NULL DEFAULT '',
`color8` varchar(6) NOT NULL DEFAULT '',
`color9` varchar(6) NOT NULL DEFAULT '',
`color10` varchar(6) NOT NULL DEFAULT '',
`color11` varchar(6) NOT NULL DEFAULT '',
`color12` varchar(6) NOT NULL DEFAULT '',
`color13` varchar(6) NOT NULL DEFAULT '',
`color14` varchar(6) NOT NULL DEFAULT '',
`color15` varchar(6) NOT NULL DEFAULT '',
`color16` varchar(6) NOT NULL DEFAULT '',
PRIMARY KEY ( `id` )
) TYPE=MyISAM; ");
  $installsql->query("INSERT INTO `rex_14_setting` (`id`, `showJsElements`, `showProperty`, `showColor`, `showHighlight`, `editorWindow`, `hltSelector`, `hltProperty`, `hltValue`, `hltDeclaration`, `color1`, `color2`, `color3`, `color4`, `color5`, `color6`, `color7`, `color8`, `color9`, `color10`, `color11`, `color12`, `color13`, `color14`, `color15`, `color16`) VALUES (1, '0', '0', '0', '0', '350', 'ff00ff', 'ff0000', '008000', '000080', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '')");

  $dbname = $DB['1']['NAME'];
  $result = mysql_list_tables($dbname);

  if (!$result):
    echo "DB Fehler<br>";
    echo "MySql-Fehler" . mysql_error();
  endif;

  $table_arr = array();
  while ($row = mysql_fetch_row($result)) {
    $table_arr[] .= $row[0];
  }

  $table = "rex_14_setting";
  
  if (in_array($table, $table_arr)) :
    $REX['ADDON']['install']['editor'] = 1;
  else:
      $REX['ADDON']['installmsg']['editor'] = 'Die Tabelle konnte nicht in die Datenbank installiert werden. Bitte benutzen Sie die beiliegende "install.sql" Datei und installieren Sie die Tabelle über phpMyAdmin!<br /><br/> Bitte im Anschluss hier auf <strong><a href="/redaxo/index.php?page=addon&addonname=editor&install=1&phpmyadmin=1">installiert</a></strong> klicken!';
    $REX['ADDON']['install']['editor'] = 0;
  endif;
  
  mysql_free_result($result);

endif;




?>
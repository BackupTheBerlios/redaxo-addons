<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: function_rex_installation.inc.php,v 1.2 2006/12/30 19:14:42 kills Exp $
 */

/**
 * Installiert ein Modul
 * @param $file Dateiname des Moduls
 * @param $module_name Name mit dem das Modul installiert werden soll
 * @param [$debug=false] Debugflag 
 */
function rex_installModule($file, $module_name, $debug = false)
{
  global $REX, $REX_USER;
  
  
  $output = sql::escape(file_get_contents($file.'.out.tpl'));
  $input = sql::escape(file_get_contents($file.'.in.tpl'));
  
  $sql = new sql();
  $sql->debugsql = $debug;
  $qry = 'INSERT INTO '. $REX['TABLE_PREFIX'].'modultyp SET `name` = '. sql::escape($module_name) .', `eingabe` = '. $input .', `ausgabe` = '. $output .', `createdate` = '. sql::escape(time()) .', `createuser` = '. sql::escape($REX_USER->getValue('login'));
  $sql->setQuery(_prepare_query($qry));
  
  return $sql->getError();
}

/**
 * Installiert ein Template
 * @param $file Dateiname des Templates
 * @param $template_name Name mit dem das Template installiert werden soll
 * @param [$debug=false] Debugflag 
 */
function rex_installTemplate($file, $template_name, $debug = false)
{
  global $REX, $REX_USER;
  
  $content = sql::escape(file_get_contents($file. '.tpl'));
  
  $sql = new sql();
  $sql->debugsql = $debug;
  $qry = 'INSERT INTO '. $REX['TABLE_PREFIX'].'template SET `name` = '. sql::escape($template_name) .', `content` = '. $content .', `createdate` = '. sql::escape(time()) .', `createuser` = '. sql::escape($REX_USER->getValue('login'));
  $sql->setQuery(_prepare_query($qry));
  
  return $sql->getError();
}
?>
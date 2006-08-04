<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: function_rex_installation.inc.php,v 1.1 2006/08/04 17:46:28 kills Exp $
 */

/**
 * Installiert ein Addon
 * @param $file Dateiname des SQL Dumps der ausgeführt werden soll
 */
function rex_installAddon($file, $debug = false)
{
  rex_valid_type($file, 'file', __FILE__, __LINE__);
  rex_valid_type($debug, 'boolean', __FILE__, __LINE__);

  return _rex_installDump($file, $debug);
}

/**
 * De-Installiert ein Addon
 * @param $file Dateiname des SQL Dumps der ausgeführt werden soll
 */
function rex_uninstallAddon($file, $debug = false)
{
  rex_valid_type($file, 'file', __FILE__, __LINE__);
  rex_valid_type($debug, 'boolean', __FILE__, __LINE__);

  return _rex_installDump($file, $debug);
}

function _rex_installDump($file, $debug = false)
{
  $sql = new sql();
  $sql->debugsql = $debug;
  $error = '';

  foreach (readSqlDump($file) as $query)
  {
    $sql->setQuery(_prepare_query($query));

    if (($sqlerr = $sql->getError()) != '')
    {
      $error .= $sqlerr."\n<br/>";
    }
  }

  return $error;
}

function _prepare_query($qry)
{
  global $REX, $REX_USER;
  
  $qry = str_replace('%USER%', $REX_USER->getValue('login'), $qry); 
  $qry = str_replace('%TIME%', time(), $qry); 
  $qry = str_replace('%TABLE_PREFIX%', $REX['TABLE_PREFIX'], $qry);
  
  return $qry;
}

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
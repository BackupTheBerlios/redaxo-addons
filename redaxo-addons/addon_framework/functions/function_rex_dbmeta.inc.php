<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: function_rex_dbmeta.inc.php,v 1.3 2007/08/24 10:35:36 kills Exp $
 */
 
/**
 * Gibt die Tabellen einer Datenbank zur�ck
 * @access public
 */
function rex_dbmeta_db_tables($database = '')
{
  global $REX;
  $database = _rex_dbmeta_get_db($database);

  if (empty ($REX['DB']['META'][$database]['TABLES']))
  {
    // fetch db infos
    _rex_dbmeta_get_tables($database);
  }

  return $REX['DB']['META'][$database]['TABLES'];
}

/**
 * Gibt die Spaltennamen einer Tabelle zur�ck
 * @access public
 */
function rex_dbmeta_table_cols($table, $database = '')
{
  global $REX;
  $database = _rex_dbmeta_get_db($database);

  if (empty ($REX['DB']['META'][$database]['TABLES'][$table]['COLNAMES']))
  {
    // fetch db infos
    _rex_dbmeta_get_colinfos($table, $database);
  }

  return $REX['DB']['META'][$database]['TABLES'][$table]['COLNAMES'];
}

/**
 * Gibt ein Array mit den Spaltennanmen des PrimaryKeys zur�ck 
 * @access public
 */
function rex_dbmeta_table_primkey($table, $database = '')
{
  global $REX;
  $database = _rex_dbmeta_get_db($database);

  if (empty ($REX['DB']['META'][$database]['TABLES'][$table]['PRIMKEYS']))
  {
    // fetch db infos
    _rex_dbmeta_get_colinfos($table, $database);
  }

  return $REX['DB']['META'][$database]['TABLES'][$table]['PRIMKEYS'];
}

/**
 * Gibt den Namen der AutoIncrement Spalte zur�ck
 * @access public
 */
function rex_dbmeta_table_autoinccol($table, $database = '')
{
  global $REX;
  $database = _rex_dbmeta_get_db($database);

  if (empty ($REX['DB']['META'][$database]['TABLES'][$table]['AUTOINC']))
  {
    // fetch db infos
    _rex_dbmeta_get_colinfos($table, $database);
  }

  return $REX['DB']['META'][$database]['TABLES'][$table]['AUTOINC'];
}

/**
 * Gibt erweiterte Spalteninformationen zur�ck
 * @access public
 */
function rex_dbmeta_table_col_details($table, $database = '')
{
  global $REX;
  $database = _rex_dbmeta_get_db($database);

  if (empty ($REX['DB']['META'][$database]['TABLES'][$table]['COLUMNS']))
  {
    // fetch db infos
    _rex_dbmeta_get_colinfos($table, $database);
  }
  return $REX['DB']['META'][$database]['TABLES'][$table]['COLUMNS'];
}

/**
 * @access private
 */
function _rex_dbmeta_get_db($database = '')
{
  if (strlen($database) == 0)
  {
    global $REX;
    return $REX['DB']['1']['NAME'];
  }
  return $database;
}

/**
 * @access private
 */
function _rex_dbmeta_get_tables($database)
{
  global $REX;

  // Validate Arguments
  _rex_dbmeta_validate($database, ' ', __FILE__, __LINE__);

  if (empty ($REX['DB']['META'][$database]['TABLES']))
  {
    $sql = new rex_sql();
    $result = $sql->getArray('SHOW TABLES FROM '.$database, MYSQL_NUM);
    $tables = array ();

    if (is_array($result))
    {
      foreach ($result as $row)
      {
        $tables[] = $row[0];
      }
    }

    $REX['DB']['META'][$database]['TABLES'] = $tables;
  }
}

/**
 * @access private
 */
function _rex_dbmeta_get_colinfos($table, $database)
{
  global $REX;

  // Validate Arguments
  _rex_dbmeta_validate($table, $database, __FILE__, __LINE__);

  if (empty ($REX['DB']['META'][$database]['TABLES'][$table]['COLNAMES']))
  {
    $sql = new rex_sql();
    $result = $sql->getArray('SHOW FULL COLUMNS FROM '.$table.' FROM '.$database, MYSQL_NUM);

    $colums = array ();
    $colnames = array ();
    $primkeys = array ();
    $autoinc = '';
    if (is_array($result))
    {
      $serverVersion = sql::getServerVersion();
      $mainVersion = $serverVersion{0};
      
      foreach ($result as $row)
      {
        $column = array ();
        $column['NAME'] = $row[0];
        $column['TYPE'] = $row[1];
        $column['NULL'] = $row[2];
        
        // Mysql 4<->5 versionsweiche
        switch($mainVersion)
        {
          case 4:
          {
            $column['KEY'] = $row[3];
            $column['DEFAULT'] = $row[4];
            $column['EXTRA'] = $row[5];
            break;
          }
          case 5:
          {
            $column['KEY'] = $row[4];
            $column['DEFAULT'] = $row[5];
            $column['EXTRA'] = $row[6];
            break;
          }
        }

        $colums[] = $column;
        $colnames[] = $column['NAME'];

        // AutoInc
        if ($column['EXTRA'] == 'auto_increment')
        {
          $autoinc = $column['NAME'];
        }

        // PrimaryKeys
        if ($column['KEY'] == 'PRI')
        {
          $primkeys[] = $column['NAME'];
        }
      }
    }

    $REX['DB']['META'][$database]['TABLES'][$table]['COLUMNS'] = $colums;
    $REX['DB']['META'][$database]['TABLES'][$table]['COLNAMES'] = $colnames;
    $REX['DB']['META'][$database]['TABLES'][$table]['PRIMKEYS'] = $primkeys;
    $REX['DB']['META'][$database]['TABLES'][$table]['AUTOINC'] = $autoinc;
  }
}

/**
 * @access private
 */
function _rex_dbmeta_validate($table, $database, $file, $line)
{
  if (empty ($table))
  {
    trigger_error('rexDBMeta: Table name $table is empty in <b>'.$file.'</b> on Line <b>'.$line.'</b>', E_USER_ERROR);
  }

  if (empty ($database))
  {
    trigger_error('rexDBMeta: Database name $database is empty in <b>'.$file.'</b> on Line <b>'.$line.'</b>', E_USER_ERROR);
  }
}
?>
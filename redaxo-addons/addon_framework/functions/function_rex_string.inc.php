<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: function_rex_string.inc.php,v 1.2 2007/06/25 12:01:45 kills Exp $
 */

/**
 * Returns true if $string starts with $start
 * 
 * @param $string String Searchstring
 * @param $start String Prefix to search for
 * @author Markus Staab <staab@public-4u.de>
 */
if (!function_exists('startsWith'))
{
  function startsWith($string, $start)
  {
    return strstr($string, $start) == $string;
  }
}

/**
 * Returns true if $string ends with $end
 * 
 * @param $string String Searchstring
 * @param $start String Suffix to search for
 * @author Markus Staab <staab@public-4u.de>
 */
if (!function_exists('endsWith'))
{
  function endsWith($string, $end)
  {
    return (substr($string, strlen($string) - strlen($end)) == $end);
  }
}

/**
 * Returns the truncated $string
 * 
 * @param $string String Searchstring
 * @param $start String Suffix to search for
 * @author Markus Staab <staab@public-4u.de>
 */
if (!function_exists('truncate'))
{
  function truncate($string, $length = 80, $etc = '...', $break_words = false)
  {
    if ($length == 0)
      return '';

    if (strlen($string) > $length)
    {
      $length -= strlen($etc);
      if (!$break_words)
        $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length +1));

      return substr($string, 0, $length).$etc;
    }
    else
      return $string;
  }
}

/**
 * Reads a file and split all statements in it.
 * 
 * @param $file String Path to the SQL-dump-file
 * @author Markus Staab <staab@public-4u.de>
 */
if (!function_exists('readSqlDump'))
{
  function readSqlDump($file)
  {
    if (is_file($file) && is_readable($file))
    {
      $ret = array ();
      $sqlsplit = '';
      $fileContent = file_get_contents($file);
      PMA_splitSqlFile($sqlsplit, $fileContent, '');

      if (is_array($sqlsplit))
      {
        foreach ($sqlsplit as $qry)
        {
          $ret[] = $qry['query'];
        }
      }

      return $ret;
    }

    return false;
  }
}
?>
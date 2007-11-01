<?php

/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: function_rex_string.inc.php,v 1.4 2007/11/01 21:48:40 kills Exp $
 */

/**
 * Returns true if $string starts with $start
 *
 * @param $string String Searchstring
 * @param $start String Prefix to search for
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
 */
if (!function_exists('endsWith'))
{
  function endsWith($string, $end)
  {
    return (substr($string, strlen($string) - strlen($end)) == $end);
  }
}

?>
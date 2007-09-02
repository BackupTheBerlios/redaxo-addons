<?php

/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: function_rex_form.inc.php,v 1.2 2007/09/02 14:00:29 kills Exp $
 */

function array_delete_key(& $array, $keyname)
{
  unset ($array[$keyname]);
  $array = array_values($array);
}

function array_resort_keys(& $array)
{
  return array_values($array);
}

?>
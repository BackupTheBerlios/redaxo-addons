<?php

/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: function_rex_list.inc.php,v 1.3 2007/12/19 12:37:31 kills Exp $
 */

/**
 * Formatiert einen übergebenen Wert als Link mit den entsprechenden Parametern
 * @param $value Wert der Link formatiert werden soll
 * @param $global_params Globale Parameter (können überschrieben werden)
 * @param $local_params Lokale Parameter (Überschreiben die globalen Parameter)
 * @param $attr Attribute, die in den Link direkt eingefügt werden sollen
 */
function rex_listlink($value, $global_params = array (), $local_params = array (), $attr = array())
{
  if (count($local_params) == 0 || $value == '')
  {
    return $value;
  }

  $_params = array_merge($global_params, $local_params);
  $params = '';
  $first = true;
  foreach ($_params as $_name => $_value)
  {
    if ($_value == '')
    {
      continue;
    }

    if ($first)
    {
      $first = false;
      $params .= '?'.$_name.'='.$_value;
    }
    else
    {
      $params .= '&amp;'.$_name.'='.$_value;
    }
  }

  if(is_array($attr))
  {
    $tmp = '';
    foreach($attr as $_name => $_value)
      $tmp .= ' '. $_name .'="'. htmlspecialchars($_value) .'"';

    $attr = $tmp;
  }
  else if (is_string($attr) && $attr != '')
  {
    $attr = ' '. trim($attr);
  }
  else
  {
    $attr = '';
  }

  return sprintf('<a href="%s"%s>%s</a>', $params, $attr, $value);
}

?>
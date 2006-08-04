<?php

/**
 * Glossar Addon
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: function_replace.inc.php,v 1.1 2006/08/04 17:46:48 kills Exp $
 */

function rex_glossar_replace($params)
{
  global $REX, $mypage, $I18N_GLOSSAR;
  $string = $params['subject'];
  
  // Aufteilen des Strings, damit nur im Body ersetzt wird
  $bodystart = strpos( $string, '<body>');
  $header = substr($string, 0, $bodystart);
  $body = substr($string, $bodystart);
 
  $sql = new sql;
  //$sql->debugsql = true;
  $result = $sql->get_array('SELECT * FROM rex_13_glossar, rex_13_glossar_lang WHERE language = lang_id ORDER BY CHAR_LENGTH(shortcut) DESC');

  // IE dont support <abbr>
  if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE'))
  {
    $replacetag = 'acronym';
  }
  else
  {
    $replacetag = 'abbr';
  }

  $replaceformat = '<'.$replacetag.' class=\"abbr\" title=\"%desc% (%lang%)\">%short%</'.$replacetag.'>';

  foreach ($result as $row)
  {
    $language = htmlspecialchars($row['lang_name']);
    $shortcut = htmlspecialchars($row['shortcut']);
    $description = htmlspecialchars($row['description']);
    $casesense = $row['casesense'];

    // Escape Shortcut for preg_match
    $escapedshortcut = preg_quote($shortcut);

    $search = '/((<[^>]*)|'.$escapedshortcut.')/e';
    //    $replaceformat = '<abbr lang=\"'.$language.'\" title=\"'.$language.': '.$description.'\">'.$shortcut.'</abbr>';

    $replacer = _rex_glossar_parse_replace_format($replaceformat, array ('lang' => $language, 'desc' => $description, 'short' => $shortcut));
    $replace = '"\2"=="\1" && strpos( "\1", "<'. $replacetag .'>") === false ? "\1":"'.$replacer.'"';

    if ($casesense == 0)
    {
      $search .= 'i';
    }

    $body = stripslashes(preg_replace($search, $replace, $body));
  }

  return $header . $body;
}

function _rex_glossar_parse_replace_format($replaceformat, $vars)
{
  if (!is_array($vars))
  {
    trigger_error('rexAddon[Glossar]: Unexpected type "'.gettype($vars).'" for $vars. Expecting type Array!');
  }

  if (count($vars) == 0)
  {
    trigger_error('rexAddon[Glossar]: Array $vars must not be empty!');
  }

  foreach ($vars as $search => $replace)
  {
    $replaceformat = str_replace('%'.$search.'%', $replace, $replaceformat);
  }

  return $replaceformat;
}
?>
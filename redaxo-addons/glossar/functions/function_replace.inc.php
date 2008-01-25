<?php

/**
 * Glossar Addon
 * <
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: function_replace.inc.php,v 1.4 2008/01/25 09:48:36 kills Exp $
 */

function rex_glossar_replace($params)
{
  global $REX, $mypage, $I18N_GLOSSAR;
  $string = $params['subject'];

  // Aufteilen des Strings, damit nur im Body ersetzt wird
  $bodystart = strpos($string, '<body>');
  $header = substr($string, 0, $bodystart);
  $body = substr($string, $bodystart);

  // Bereiche ersetzen, in denen keine Glossar ersetzungen durchgeführt werden sollen
  // welche nicht innerhalb des Tags sind
  $back_srch = array ();
  $back_rplc = array ();
  $mtchs = array ();
  if (preg_match_all('/(<textarea.*?>(.*?)<\/textarea>)/s', $body, $mtchs))
  {
    foreach ($mtchs[2] as $key => $mtch)
    {
      $back_srch[$key] = '###SPACER###' . $key . '###';
      $back_rplc[$key] = $mtch;
      $body = str_replace($mtch, '###SPACER###' . $key . '###', $body);
    }
  }

  $sql = new sql;
  //$sql->debugsql = true;
  $sql->setQuery('SELECT * FROM rex_13_glossar, rex_13_glossar_lang WHERE language = lang_id ORDER BY CHAR_LENGTH(shortcut) DESC');

  // IE doesnt support <abbr>
  if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE'))
  {
    $replacetag = 'acronym';
  }
  else
  {
    $replacetag = 'abbr';
  }

  $replaceformat = '<' . $replacetag . ' class=\"abbr\" title=\"%desc% (%lang%)\">%short%</' . $replacetag . '>';

  $searches = array ();
  $replaces = array ();
  for ($i = 0; $i < $sql->getRows(); $i++)
  {
    $language = htmlspecialchars($sql->getValue('lang_name'));
    $shortcut = htmlspecialchars($sql->getValue('shortcut'));
    $description = htmlspecialchars($sql->getValue('description'));
    $casesense = $sql->getValue('casesense');

    // Escape Shortcut for preg_match
    $escapedshortcut = preg_quote($shortcut, '/');
    $escapedentitiesshortuct = htmlentities($escapedshortcut);

    if($escapedentitiesshortuct == $escapedshortcut)
      $search = '/((<[^>]*)|' . $escapedshortcut . ')/e';
    else
      $search = '/((<[^>]*)|' . $escapedshortcut . '|'. $escapedentitiesshortuct .')/e';

    $replacer = _rex_glossar_parse_replace_format($replaceformat, array (
      'lang' => $language,
      'desc' => $description,
      'short' => $shortcut
    ));
    $replace = '"\2"=="\1" && strpos( "\1", "<' . $replacetag . '>") === false ? "\1":"' . $replacer . '"';

    if ($casesense == 0)
    {
      $search .= 'i';
    }

    $searches[] = $search;
    $replaces[] = $replace;

    $sql->next();
  }

  // Ersetzungen durchführen
  $body = stripslashes(preg_replace($searches, $replaces, $body));

  // Vorher ausgeschlossene Bereiche wieder einpflegen
  $body = str_replace($back_srch, $back_rplc, $body);

  return $header . $body;
}

function _rex_glossar_parse_replace_format($replaceformat, $vars)
{
  if (!is_array($vars))
  {
    trigger_error('rexAddon[Glossar]: Unexpected type "' . gettype($vars) . '" for $vars. Expecting type Array!');
  }

  if (count($vars) == 0)
  {
    trigger_error('rexAddon[Glossar]: Array $vars must not be empty!');
  }

  foreach ($vars as $search => $replace)
  {
    $replaceformat = str_replace('%' . $search . '%', $replace, $replaceformat);
  }

  return $replaceformat;
}
?>
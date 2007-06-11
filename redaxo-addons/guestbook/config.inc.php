<?php

/**
 * Guestbook Addon 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: config.inc.php,v 1.1 2007/06/11 11:54:26 kills Exp $
 */
 
$mypage = 'guestbook'; // only for this file

if (!defined('TBL_GBOOK'))
{
  define('TBL_GBOOK', 'rex_9_gbook');
}

// CREATE LANG OBJ FOR THIS ADDON
$I18N_GBOOK = new i18n($REX['LANG'], $REX['INCLUDE_PATH'].'/addons/'.$mypage.'/lang');

$REX['ADDON']['page'][$mypage] = $mypage;
$REX['ADDON']['rxid'][$mypage] = "9";
$REX['ADDON']['name'][$mypage] = $I18N_GBOOK->msg('menu_title');
$REX['ADDON']['perm'][$mypage] = 'guestbook[]';

$REX['PERM'][] = 'guestbook[]';

// Im Frontend CSS einfügen
if (!$REX['REDAXO'])
{
  rex_register_extension('OUTPUT_FILTER', 'rex_gbook_insert_css');

  function rex_gbook_insert_css($params)
  {
    $content = $params['subject'];
    $styles = '';

    $files = array ('guestbook.css');

    foreach ($files as $file)
    {
      $css_file = dirname(__FILE__).'/css/'.$file;
      $size = filesize($css_file);
      if ($size > 0)
      {
        $handle = fopen($css_file, 'r');
        $styles .= fread($handle, $size);
        fclose($handle);
      }
    }

    if (strlen($styles) > 0)
    {
      $styles = '<style type="text/css">
               <!--
               '.$styles.'
               -->
               </style>';
    }

    return str_replace('</head>', $styles.'</head>', $content);
  }
}
?>
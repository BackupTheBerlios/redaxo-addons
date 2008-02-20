<?php

/**
 * markitup Addon
 *
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 *
 * @package redaxo4
 * @version $Id: config.inc.php,v 1.1 2008/02/20 13:50:47 kills Exp $
 */

$mypage = 'markitup';

$REX['ADDON']['rxid'][$mypage] = '287';
$REX['ADDON']['page'][$mypage] = $mypage;
$REX['ADDON']['name'][$mypage] = 'MarkItUp';
$REX['ADDON']['perm'][$mypage] = 'markitup[]';
$REX['ADDON']['version'][$mypage] = '1.0';
$REX['ADDON']['author'][$mypage] = 'Markus Staab';
$REX['ADDON']['supportpage'][$mypage] = 'forum.redaxo.de';

$I18N_A287 = new i18n($REX['LANG'], $REX['INCLUDE_PATH'].'/addons/'.$mypage.'/lang/');

if($REX['REDAXO'])
{
  $markitup = rex_post('markitup_preview', 'string');
  if($markitup != '')
  {
    echo stripslashes($markitup);
    exit();
  }
  else
  {
    $path = $REX['HTDOCS_PATH'] .'files/'. $REX['TEMP_PREFIX'] .'/markitup/';
    $links  = '  <script type="text/javascript" src="'. $path .'jquery.pack.js"></script>'."\n".
              '  <script type="text/javascript" src="'. $path .'jquery.markitup.pack.js"></script>'."\n".
              '  <script type="text/javascript" src="'. $path .'sets/redaxo/set.js"></script>'."\n".
              '  <script type="text/javascript">$(document).ready(function(){$(".markitup").markItUp(redaxo);})</script>'."\n".
              '  <link rel="stylesheet" type="text/css" href="'. $path .'skins/markitup/style.css" />'."\n".
              '  <link rel="stylesheet" type="text/css" href="'. $path .'sets/redaxo/style.css" />'."\n";
    rex_register_extension('PAGE_HEADER', create_function('$params', 'return $params[\'subject\'].\''. $links .'\';'));
  }
}

?>
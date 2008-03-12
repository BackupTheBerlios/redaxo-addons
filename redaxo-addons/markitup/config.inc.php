<?php

/**
 * markitup Addon
 *
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 *
 * @package redaxo4
 * @version $Id: config.inc.php,v 1.3 2008/03/12 14:52:11 kills Exp $
 */

$mypage = 'markitup';

$REX['ADDON']['rxid'][$mypage] = '287';
$REX['ADDON']['page'][$mypage] = $mypage;
$REX['ADDON']['name'][$mypage] = 'MarkItUp';
$REX['ADDON']['perm'][$mypage] = 'markitup[]';
$REX['ADDON']['version'][$mypage] = '1.0';
$REX['ADDON']['author'][$mypage] = 'Markus Staab';
$REX['ADDON']['supportpage'][$mypage] = 'forum.redaxo.de';

// redaxo o. redaxo_textile
//$REX['ADDON']['settings'][$mypage]['set'] = 'redaxo';
$REX['ADDON']['settings'][$mypage]['set'] = 'redaxo_textile';

$I18N_A287 = new i18n($REX['LANG'], $REX['INCLUDE_PATH'].'/addons/'.$mypage.'/lang/');

if($REX['REDAXO'])
{
  $markitup = rex_post('markitup_preview', 'string');
  $markitup_textile = rex_post('markitup_textile_preview', 'string');
  if($markitup != '')
  {
    echo stripslashes($markitup);
    exit();
  }
  else if($markitup_textile != '')
  {
    if(!OOAddon::isAvailable('textile'))
      echo 'Dieses Modul benötigt das "textile" Addon!';
    else
    {
      require_once($REX['INCLUDE_PATH']. '/addons/textile/classes/class.textile.inc.php');
      require_once $REX['INCLUDE_PATH']. '/addons/textile/functions/function_textile.inc.php';

      echo rex_a79_textile(stripslashes($markitup_textile));
    }
    exit();
  }
  else
  {
    $path = $REX['HTDOCS_PATH'] .'files/'. $REX['TEMP_PREFIX'] .'/markitup/';
    $set = $REX['ADDON']['settings'][$mypage]['set'];

    $links  = '  <script type="text/javascript" src="'. $path .'jquery.pack.js"></script>'."\n".
              '  <script type="text/javascript" src="'. $path .'jquery.markitup.js"></script>'."\n".
              '  <script type="text/javascript" src="'. $path .'sets/'. $set .'/set.js"></script>'."\n".
              '  <script type="text/javascript">$(document).ready(function(){$(".markitup").markItUp('. $set .');})</script>'."\n".
              '  <link rel="stylesheet" type="text/css" href="'. $path .'skins/markitup/style.css" />'."\n".
              '  <link rel="stylesheet" type="text/css" href="'. $path .'sets/'. $set .'/style.css" />'."\n";

    rex_register_extension('PAGE_HEADER', create_function('$params', 'return $params[\'subject\'].\''. $links .'\';'));
  }
}

?>
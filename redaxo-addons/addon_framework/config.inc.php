<?php

/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: config.inc.php,v 1.9 2008/03/11 11:31:40 kills Exp $
 */

$mypage = 'addon_framework'; // only for this file

$REX['ADDON']['page'][$mypage] = $mypage;
$REX['ADDON']['rxid'][$mypage] = '22';
$REX['ADDON']['name'][$mypage] = 'Addon Framework';
$REX['ADDON']['perm'][$mypage] = 'addon_framework[]';
$REX['ADDON']['author'][$mypage] = "Markus Staab";

$REX['PERM'][] = 'addon_framework[]';

$I18N_ADDON_FRAMEWORK = new i18n($REX['LANG'],$REX['INCLUDE_PATH']."/addons/$mypage/lang/");

$Basedir = dirname(__FILE__);

// Allgemeine libs includen
require_once $Basedir.'/functions/function_rex_common.inc.php';

// Im Backendlibs includen
if ($REX['REDAXO'])
{
  if(rex_get('css', 'string') == 'addons/'. $mypage)
  {
    $cssfile = $REX['INCLUDE_PATH'] .'/addons/'. $mypage .'/css/common.css';
    rex_send_file($cssfile, 'text/css');
    exit();
  }

  rex_register_extension('PAGE_HEADER',
    create_function('$params', 'return $params[\'subject\'] .\'  <link rel="stylesheet" type="text/css" href="index.php?css=addons/'. $mypage .'" />\'."\n";')
  );

  require_once $Basedir.'/functions/function_rex_folder.inc.php';
  require_once $Basedir.'/functions/function_rex_string.inc.php';
  require_once $Basedir.'/functions/function_rex_installation.inc.php';
}
?>
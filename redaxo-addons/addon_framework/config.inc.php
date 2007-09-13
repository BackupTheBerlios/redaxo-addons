<?php

/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: config.inc.php,v 1.8 2007/09/13 19:49:17 kills Exp $
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

// CSS includen
rex_register_extension('PAGE_HEADER', 'rex_a22_insertCss');
function rex_a22_insertCss($params)
{
  return $params['subject'] .'  <link rel="stylesheet" type="text/css" href="../files/tmp_/addon_framework/common.css" />'. "\n";
}

// Im Backendlibs includen
if ($REX['REDAXO'])
{
  require_once $Basedir.'/functions/function_rex_folder.inc.php';
  require_once $Basedir.'/functions/function_rex_string.inc.php';
  require_once $Basedir.'/functions/function_rex_installation.inc.php';
}
?>
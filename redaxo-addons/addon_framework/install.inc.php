<?php

/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: install.inc.php,v 1.5 2007/09/10 16:56:04 kills Exp $
 */

require_once $REX['INCLUDE_PATH'] . '/addons/addon_framework/functions/function_pclzip.inc.php';

$mediaFolder = $REX['MEDIAFOLDER'].'/addon_framework');
if(!is_dir($mediaFolder))
  mkdir($mediaFolder);

// Install wysiwyg-Calendar
rex_a22_extract_archive('include/addons/addon_framework/js/calendar.zip');


$cssFile = $REX['MEDIAFOLDER'].'/addon_framework/common.css';
if(!file_exists($cssFile))
  copy($REX['INCLUDE_PATH'] . '/addons/addon_framework/css/common.css',
       $cssFile);

$REX['ADDON']['install']['addon_framework'] = 1;

?>
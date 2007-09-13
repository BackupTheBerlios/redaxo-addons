<?php

/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: install.inc.php,v 1.7 2007/09/13 19:49:17 kills Exp $
 */

require_once $REX['INCLUDE_PATH'] . '/addons/addon_framework/functions/function_pclzip.inc.php';

$mediaFolder = $REX['MEDIAFOLDER'].'/addon_framework';
if(!is_dir($mediaFolder))
  mkdir($mediaFolder);

// Install wysiwyg-Calendar
rex_a22_extract_archive('include/addons/addon_framework/js/calendar.zip');


$cssFile = '/tmp_/addon_framework/common.css';
if(!file_exists($REX['MEDIAFOLDER'] .$cssFile))
  copy($REX['INCLUDE_PATH'] . '/addons'. $cssFile,
       $REX['MEDIAFOLDER'] .$cssFile);

$REX['ADDON']['install']['addon_framework'] = 1;

?>
<?php

/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: install.inc.php,v 1.8 2007/10/21 10:05:58 kills Exp $
 */

require_once $REX['INCLUDE_PATH'] . '/addons/addon_framework/functions/function_pclzip.inc.php';

$error = '';

$tmpFolder = $REX['MEDIAFOLDER'].'/'. $REX['TEMP_PREFIX'] .'/';
if($error == '' && !is_dir($tmpFolder) && !mkdir($tmpFolder))
  $error = 'Unable to create folder "'. $tmpFolder .'"';

$mediaFolder = $tmpFolder .'/addon_framework/';
if($error == '' && !is_dir($mediaFolder) && !mkdir($mediaFolder))
  $error = 'Unable to create folder "'. $mediaFolder .'"';

// Install wysiwyg-Calendar
if($error == '')
  rex_a22_extract_archive('include/addons/addon_framework/js/calendar.zip');


$cssPathSource = '/addons/addon_framework/css/';
$cssFile = 'common.css';

$cssSrc = $REX['INCLUDE_PATH'] . $cssPathSource . $cssFile;
$cssDst = $mediaFolder . $cssFile;

if($error == '' && !file_exists($cssDst) && !copy($cssSrc, $cssDst))
  $error = 'Unable to copy file to "'. $cssDst .'"';

if($error != '')
  $REX['ADDON']['installmsg']['addon_framework'] = $error;
else
  $REX['ADDON']['install']['addon_framework'] = true;


?>
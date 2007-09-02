<?php

/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: install.inc.php,v 1.3 2007/09/02 14:00:29 kills Exp $
 */

require_once $REX['INCLUDE_PATH'] . '/addons/addon_framework/functions/function_pclzip.inc.php';

// Install wysiwyg-Calendar
rex_a22_extract_archive('include/addons/addon_framework/js/calendar.zip');

mkdir($REX['MEDIAFOLDER'].'/addon_framework');

copy($REX['INCLUDE_PATH'] . '/addons/addon_framework/css/common.css',
     $REX['MEDIAFOLDER'].'/addon_framework/common.css');

$REX['ADDON']['install']['addon_framework'] = 1;

?>
<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: install.inc.php,v 1.2 2007/01/31 20:20:16 kills Exp $
 */
 
require_once $REX['INCLUDE_PATH'] . '/addons/addon_framework/functions/function_pclzip.inc.php';

// Install wysiwyg-Calendar
rex_a22_extract_archive('include/addons/addon_framework/js/calendar.zip');
 
$REX['ADDON']['install']['addon_framework'] = 1;

?>
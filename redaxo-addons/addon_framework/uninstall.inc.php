<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: uninstall.inc.php,v 1.2 2007/01/31 20:20:16 kills Exp $
 */

rex_deleteDir('../files/calendar', true);

$REX['ADDON']['install']['addon_framework'] = 0;
// ERRMSG IN CASE: $REX['ADDON']['installmsg']['addon_framework'] = "Deinstallation fehlgeschlagen weil...";

?>
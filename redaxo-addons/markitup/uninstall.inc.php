<?php

/**
 * markitup Addon
 *
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 *
 * @package redaxo4
 * @version $Id: uninstall.inc.php,v 1.1 2008/02/20 13:50:47 kills Exp $
 */

rex_deleteDir('../files/tmp_/markitup', true);

$REX['ADDON']['install']['markitup'] = 0;
?>
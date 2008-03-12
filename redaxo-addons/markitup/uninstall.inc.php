<?php

/**
 * markitup Addon
 *
 * @author markus[dot]staab[at]redaxo[dot]de Markus Staab

 *
 * @package redaxo4
 * @version $Id: uninstall.inc.php,v 1.2 2008/03/12 14:54:12 kills Exp $
 */

rex_deleteDir('../files/tmp_/markitup', true);

$REX['ADDON']['install']['markitup'] = 0;
?>
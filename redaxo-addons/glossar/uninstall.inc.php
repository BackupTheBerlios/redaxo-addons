<?php

/**
 * Glossar Addon
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: uninstall.inc.php,v 1.1 2006/08/04 17:46:48 kills Exp $
 */

$error = '';
if ($error == '')
{
  $error .= rex_uninstallAddon(dirname(__FILE__).'/uninstall.sql');
}

if ($error != '')
{
  $REX['ADDON']['installmsg']['glossar'] = $error;
}
else
{
  $REX['ADDON']['install']['glossar'] = 0;
}

?>
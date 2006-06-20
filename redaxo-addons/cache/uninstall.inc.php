<?php

/**
 * Article Cache Addon
 *  
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * 
 * @author info[at]thomas-peterson[dot]de Thomas Peterson
 * @author <a href="http://www.thomas-peterson.de/">http://www.thomas-peterson.de/</a>
 * 
 * @package redaxo3
 * @version $Id: uninstall.inc.php,v 1.1 2006/06/20 09:24:20 kills Exp $
 */

$error = '';
if ($error == '')
{
  $error .= rex_uninstallAddon(dirname(__FILE__).'/uninstall.sql');
}

if ($error != '')
{
  $REX['ADDON']['installmsg']['cache'] = $error;
}
else
{
  $REX['ADDON']['install']['cache'] = 0;
}

?>
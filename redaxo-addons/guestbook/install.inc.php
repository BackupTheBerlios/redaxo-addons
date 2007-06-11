<?php

/**
 * Guestbook Addon 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: install.inc.php,v 1.1 2007/06/11 11:54:26 kills Exp $
 */
 
$error = '';
if (!OOAddon :: isAvailable('addon_framework'))
{
  $error .= 'Required addon "addon_framework" is either not installed or not activated!';
}

if ($error == '')
{
  $error .= rex_installAddon(dirname(__FILE__).'/install.sql');
}

if ($error != '')
{
  $REX['ADDON']['installmsg']['guestbook'] = $error;
}
else
{
  $REX['ADDON']['install']['guestbook'] = 1;
}
?>
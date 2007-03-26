<?php

/**
 * MetaForm Addon
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: config.inc.php,v 1.1 2007/03/26 14:02:22 kills Exp $
 */

$mypage = 'history';

if ($REX['REDAXO']) $I18N_HISTORY = new i18n($REX['LANG'],$REX['INCLUDE_PATH'].'/addons/'. $mypage .'/lang');

$REX['ADDON']['rxid'][$mypage] = '128';
$REX['ADDON']['page'][$mypage] = $mypage;

$REX['EXTPERM'][] = 'history[]';

if($REX['REDAXO'])
{
  require $REX['INCLUDE_PATH'] . '/addons/'. $mypage .'/extensions/extension_historyHandler.inc.php';
	rex_register_extension('OUTPUT_FILTER', 'rex_a128_historyHandler', array('mypage' => $mypage));
	
	if($page != 'medienpool')
	{
	  require $REX['INCLUDE_PATH'] . '/addons/'. $mypage .'/extensions/extension_historyPanel.inc.php';
		rex_register_extension('OUTPUT_FILTER', 'rex_a128_historyPanel', array('mypage' => $mypage));
	}
}

?>
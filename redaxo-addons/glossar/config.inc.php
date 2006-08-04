<?php

/**
 * Glossar Addon
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: config.inc.php,v 1.1 2006/08/04 17:46:48 kills Exp $
 */

$mypage = 'glossar'; // only for this file

$REX['ADDON']['page'][$mypage] = $mypage;
$REX['ADDON']['rxid'][$mypage] = '13';
$REX['ADDON']['name'][$mypage] = 'Glossar';
$REX['ADDON']['perm'][$mypage] = 'glossar[]';

$REX['PERM'][] = 'glossar[]';

// Create lang object for this addon
$I18N_GLOSSAR = new i18n($REX['LANG'], $REX['INCLUDE_PATH'].'/addons/'.$mypage.'/lang/');

// Nur im Frontend
if (!$REX['REDAXO'])
{
  // Glossar Ersetzungsfunktion für das Frontend
  require_once (dirname(__FILE__).'/functions/function_replace.inc.php'); 
  rex_register_extension('OUTPUT_FILTER', 'rex_glossar_replace'); 
}
?>
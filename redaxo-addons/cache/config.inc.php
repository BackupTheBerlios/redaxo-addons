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
 * @version $Id: config.inc.php,v 1.1 2006/06/20 09:24:20 kills Exp $
 */
 
$mypage = "cache";

// CREATE LANG OBJ FOR THIS ADDON
$I18N_CACHE = new i18n($REX['LANG'], $REX['INCLUDE_PATH']."/addons/$mypage/lang");

$REX['ADDON']['rxid'][$mypage] = "51"; // unique redaxo addon id
$REX['ADDON']['page'][$mypage] = $mypage;
$REX['ADDON']['name'][$mypage] = $I18N_CACHE->msg('cache_title');
$REX['ADDON']['perm'][$mypage] = "cache[]";
$REX['PERM'][] = "cache[]";

// ----------------- DONT EDIT BELOW THIS
// --- DYN

$REX['ADDON_CACHE']['DEFAULT_LIFETIME'] = '1800';

// --- /DYN
// ----------------- /DONT EDIT BELOW THIS

/*
 * Backend Funktionen um das Meta-Form zu erweitern
 */
if ($REX['REDAXO'] && $page == 'content' && $mode == 'meta')
{
  require_once $REX['INCLUDE_PATH']. '/addons/cache/functions/function_extensions.inc.php'; 
  rex_register_extension('ART_META_FORM_SECTION', 'rex_a51_cache_meta_form');
}

/*
 * Frontend Funktionen um die Caches aufzubauen, erneuern und auszugeben
 */
if (!$REX['REDAXO'])
{
  require_once $REX['INCLUDE_PATH']. '/addons/cache/functions/function_extensions.inc.php'; 
  rex_register_extension('OUTPUT_FILTER_CACHE', 'rex_a51_cache_write');
  rex_register_extension('ADDONS_INCLUDED', 'rex_a51_cache_read');
}
?>
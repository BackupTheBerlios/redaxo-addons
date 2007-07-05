<?php

$mypage = 'live_system';                                 // only for this file

$REX['ADDON']['rxid'][$mypage] = 'REX_LIVE_SYSTEM';                        // unique id /
// $REX['ADDON'][nsid][$mypage] = 'REX002,REX003';        // necessary rxid; - not yet included
$REX['ADDON']['page'][$mypage] = $mypage;                        // pagename/foldername
$REX['ADDON']['name'][$mypage] = 'Live System Sync';                // name
$REX['ADDON']['perm'][$mypage] = 'live[]';                 // permission

$REX['DB']['live_db']['HOST'] = 'localhost'; // live db host
$REX['DB']['live_db']['NAME'] = ''; // live db name
$REX['DB']['live_db']['LOGIN'] = ''; // live db user
$REX['DB']['live_db']['PSW'] = ''; // live db pass

$REX['ADDON']['live_system']['live_path'] = ''; // full live path
$REX['ADDON']['live_system']['dev_path'] = ''; // full development path
$REX['ADDON']['live_system']['live_secure'] = false; // sch�tzt das redaxo dir am server per .htaccess

$REX['PERM'][] = 'live[]';

?>
<?php

$mypage = "bug_report";        // only for this file

$I18N_BUG_REPORT = new i18n($REX['LANG'],$REX['INCLUDE_PATH']."/addons/$mypage/lang/");  // CREATE LANG OBJ FOR THIS ADDON

// $REX['ADDON']['rxid'][$mypage] = "2";     // unique id /
// $REX[ADDON][nsid][$mypage] = "REX002,REX003";  // necessary rxid; - not yet included
$REX['ADDON']['page'][$mypage] = $mypage;     // pagename/foldername
$REX['ADDON']['name'][$mypage] = $I18N_BUG_REPORT->msg("bug_report");   // name
$REX['ADDON']['perm'][$mypage] = "bug_report[]";     // permission

$REX['PERM'][] = "bug_report[]";

$TABLE['bug_report'] = "bug_report";
$REX['ADDON']['extras'][$REX['ADDON']['rxid'][$mypage]]['TABLE'] = $TABLE['bug_report'] ;

// IF NECESSARY INCLUDE FUNC/CLASSES ETC
// INCLUDE IN FRONTEND --- if ($REX[GG]) 
// INCLUDE IN BACKEND --- if (!$REX[GG]) 

?>
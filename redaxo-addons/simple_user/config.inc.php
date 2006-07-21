<?php

/** 
 * Config . Zuständig für Simple User
 * @author jan@kristinus
 * @version 0.9
 */

$mypage = "simple_user"; // only for this file

$I18N_SIMPLE_USER = new i18n($REX['LANG'],$REX['INCLUDE_PATH']."/addons/$mypage/lang");  // CREATE LANG OBJ FOR THIS ADDON

$REX['ADDON']['page'][$mypage] = $mypage;     // pagename/foldername
$REX['ADDON']['name'][$mypage] = $I18N_SIMPLE_USER->msg("simple_user");   // name
$REX['ADDON']['perm'][$mypage] = "simple_user[]";     // permission

$REX['PERM'][] = "simple_user[]";

?>
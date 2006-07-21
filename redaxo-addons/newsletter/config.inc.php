<?php

/** 
 * Config . Zuständig für den Newsletter 
 * @author jan@kristinus
 * @version 0.9
 */

$mypage = "newsletter";

$REX[ADDON][rxid][$mypage] = "8";
$REX[ADDON][page][$mypage] = "$mypage";
$REX[ADDON][name][$mypage] = "Newsletter";
$REX[ADDON][perm][$mypage] = "newsletter[]";
$REX[PERM][] = "newsletter[]";

// IF NECESSARY INCLUDE FUNC/CLASSES ETC
// INCLUDE IN FRONTEND --- if ($REX[GG]) 
// INCLUDE IN BACKEND --- if (!$REX[GG]) 

?>
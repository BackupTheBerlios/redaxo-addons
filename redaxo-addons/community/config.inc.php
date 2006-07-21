<?php

// -- board
// -- comment
// -- online users / session
// -- login process
// -- register
// ----- mit textpic
// -- password forgotten
// -- double opt in
// -- messages
// ---- private messages
// ---- gaestebuch
// ---- comments
// -- my profile

$mypage = "community";        // only for this file

$REX['ADDON']['page'][$mypage] = "$mypage";     // pagename/foldername
$REX['ADDON']['name'][$mypage] = "Community";   // name
$REX['ADDON']['perm'][$mypage] = "community[]";    // permission

$REX['PERM'][] = "community[]";

include $REX['INCLUDE_PATH']."/addons/community/classes/class.rex_com_board.inc.php";

// IF NECESSARY INCLUDE FUNC/CLASSES ETC
// INCLUDE IN FRONTEND --- if ($REX[GG]) 
// INCLUDE IN BACKEND --- if (!$REX[GG]) 

?>
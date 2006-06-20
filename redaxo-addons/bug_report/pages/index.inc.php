<?php

$mypage = "bug_report";

include_once $REX['INCLUDE_PATH']."/addons/$mypage/classes/class.rexform.inc.php";
include_once $REX['INCLUDE_PATH']."/addons/$mypage/classes/class.rexlist.inc.php";
include_once $REX['INCLUDE_PATH']."/addons/$mypage/classes/class.rexselect.inc.php";

include $REX['INCLUDE_PATH']."/layout/top.php";


$subpage = "bugs";
rex_title($I18N_BUG_REPORT->msg("bug_report"), "&nbsp;&nbsp;&nbsp;
".$I18N_BUG_REPORT->msg("bugs"));


include $REX['INCLUDE_PATH']."/addons/$mypage/pages/$subpage.inc.php";


include $REX['INCLUDE_PATH']."/layout/bottom.php";
?>


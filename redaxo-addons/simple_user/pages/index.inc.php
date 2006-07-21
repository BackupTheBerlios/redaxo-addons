<?php

$mypage = "simple_user";

// Basisklassen einbinden
include_once $REX['INCLUDE_PATH']."/addons/$mypage/classes/class.rexform.inc.php";
include_once $REX['INCLUDE_PATH']."/addons/$mypage/classes/class.rexlist.inc.php";
include_once $REX['INCLUDE_PATH']."/addons/$mypage/classes/class.rexselect.inc.php";

// Layout-Kopf
include $REX['INCLUDE_PATH']."/layout/top.php";

$pages = array(
  array('', $I18N_SIMPLE_USER->msg('simple_user')),
  array('group', $I18N_SIMPLE_USER->msg('uw_group')),
);

// Validerung von $subpage
switch($subpage)
{
  case 'group': break;
  default: $subpage = 'user';
}

rex_title($I18N_SIMPLE_USER->msg('simple_user'), $pages);

// Seite einbinden
include $REX['INCLUDE_PATH']."/addons/$mypage/pages/$subpage.inc.php";

// Layout Fuß
include $REX['INCLUDE_PATH']."/layout/bottom.php";
?>
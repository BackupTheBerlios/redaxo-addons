<?php

error_reporting(E_ALL ^ E_NOTICE);

$mypage = "community";
$table_prefix = "rex_5_user";

include $REX["INCLUDE_PATH"]."/layout/top.php";

include $REX["INCLUDE_PATH"]."/addons/community/classes/class.rexform.inc.php";
include $REX["INCLUDE_PATH"]."/addons/community/classes/class.rexlist.inc.php";
include $REX["INCLUDE_PATH"]."/addons/community/classes/class.rexselect.inc.php";

$subtitle = "&nbsp;&nbsp;<a href=index.php?page=".$mypage."&clang=".$clang."&subpage=boards><b>Board</b></a>";
$subtitle .= "&nbsp;&nbsp;<a href=index.php?page=".$mypage."&clang=".$clang."&subpage=comments><b>Comments</b></a>";

$subpages = array (
  array( '', 'Übersicht'),
  // array ('boards', 'Boards'), 
  array ('articlecomments', 'Artikelkommentare'),
  array ('usercomments', 'Userkommentare'),
  // array ('messages', 'Private Nachrichten'),
  array ('user', 'User'),
  array ('group', 'Gruppen'),
  // array ('config', 'Einstellungen'),
  // array ('folderfiles', 'Ordner und Dateien'),
  // array ('modules', 'Modules und Templates'),
  array ('newsletter', 'Newsletter'),
);

rex_title("Community", $subpages);

$subpage = "";
if (isset($_REQUEST["subpage"]) && $_REQUEST["subpage"] != "") $subpage = $_REQUEST["subpage"];

switch($subpage)
{
	case("boards"):
		include $REX["INCLUDE_PATH"]."/addons/$myoage/community/pages/boards.inc.php";
		break;
	case("comments"):
		include $REX["INCLUDE_PATH"]."/addons/$myoage/community/pages/comments.inc.php";
		break;
	case("user"):
		include $REX["INCLUDE_PATH"]."/addons/$myoage/community/pages/user.inc.php";
		break;
	case("group"):
		include $REX["INCLUDE_PATH"]."/addons/$myoage/community/pages/group.inc.php";
		break;
	case("messages"):
		// include $REX["INCLUDE_PATH"]."/addons/$myoage/community/pages/messages.inc.php";
		break;	
	case("usercomments"):
		include $REX["INCLUDE_PATH"]."/addons/$myoage/community/pages/usercomments.inc.php";
		break;
	case("articlecomments"):
		include $REX["INCLUDE_PATH"]."/addons/$myoage/community/pages/articlecomments.inc.php";
		break;
	case("newsletter"):
		include $REX["INCLUDE_PATH"]."/addons/$myoage/community/pages/newsletter.inc.php";
		break;
	default:
		echo "<table border=0 cellpadding=10 cellspacing=0 width=770 ><tr><td class=grey>";
		echo "<h2>Übersicht</h1>
		&gt; Artikelkommentare
		<br /><br />&gt; Userkommentare
		<br /><br />&gt; Private Nachrichten
		<br /><br />&gt; User
		<br /><br />&gt; Newsletter
		
		
		
		";
		echo "</td></tr></table>";
}

/*

if ("REX_VALUE[3]" == "1")
{
 $board->anonymous = true;
}else
{
 $board->setUserjoin("rex__user on rex__board.user_id=rex__user.id","rex__user.login");
 if ($FORM[USER])
 {
  $user_id = $FORM[USER]->getValue("rex__user.id");
  $user_login = $FORM[USER]->getValue("rex__user.login");
  $board->setUser($user_id,$user_login);
 }
 $board->setLinkUser("index.php?article_id=16&FORM[user_id]=","user_id");
}

*/

include $REX["INCLUDE_PATH"]."/layout/bottom.php";

?>
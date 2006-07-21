<?php

$mypage = "newsletter";

include_once $REX["INCLUDE_PATH"]."/addons/$mypage/classes/class.rexform.inc.php";
include_once $REX["INCLUDE_PATH"]."/addons/$mypage/classes/class.rexlist.inc.php";
include_once $REX["INCLUDE_PATH"]."/addons/$mypage/classes/class.rexselect.inc.php";

include $REX["INCLUDE_PATH"]."/layout/top.php";

$a = array();
$a[] = array("user","User");
$a[] = array("newsletter","Newsletter senden");
$a[] = array("import","CSV importieren");

if ($_REQUEST["subpage"] != "newsletter" && $_REQUEST["subpage"] != "import") $_REQUEST["subpage"] = "user";

rex_title("Newsletter",$a);

if ($_REQUEST["subpage"] == "newsletter")
{
	include $REX["INCLUDE_PATH"]."/addons/$mypage/pages/newsletter.inc.php";	
	
}elseif ($_REQUEST["subpage"] == "import")
{
	include $REX["INCLUDE_PATH"]."/addons/$mypage/pages/import.inc.php";	
	
}else
{
	include $REX["INCLUDE_PATH"]."/addons/$mypage/pages/user.inc.php";
}


include $REX["INCLUDE_PATH"]."/layout/bottom.php";


?>
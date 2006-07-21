<?


include $REX["INCLUDE_PATH"]."/layout/top.php";

rex_title("QuixExplorer", "");

$QUIXPATH = $REX["INCLUDE_PATH"]."/addons/quixexplorer/quix/";

echo "<table width=770 cellpadding=0 cellspacing=1 border=0><tr><td class=grey>";

include $QUIXPATH."index.php";

echo "</td></tr></table>";

include $REX["INCLUDE_PATH"]."/layout/bottom.php";

?>

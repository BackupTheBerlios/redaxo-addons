<?php

include $REX[INCLUDE_PATH]."/layout/top.php";

rex_title("Developer Update ","");

?>

<?php
if($function == "setStatus")
{
    $liveEdit = new liveEdit();
	$liveEdit->developer_setStatus('MODULES',$status_modules);
	$liveEdit->developer_setStatus('TEMPLATES',$status_templates);
}
if($function == "update" && $modules!="")
{
	$liveEdit = new liveEdit();
	$liveEdit->regenerateArticlesByModultypId($modules);
}
?>

<?php

include $REX[INCLUDE_PATH]."/layout/bottom.php";

?>
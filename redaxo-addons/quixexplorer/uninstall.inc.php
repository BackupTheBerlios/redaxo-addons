<?php

$image_dir = $REX['MEDIAFOLDER'] .'/quixexplorer';
if(rex_deleteDir($image_dir))
{
	$REX['ADDON']['install']['quixexplorer'] = 0;
}
else
{
	$REX['ADDON']['installmsg']['quixexplorer'] = 'Der Ordner "'. $image_dir .'" konnte nicht gelöscht werden';
}

?>
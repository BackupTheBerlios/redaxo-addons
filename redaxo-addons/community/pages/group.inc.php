<?php

$SF = true;

$mypage = "community";
$subpage = "group";
$table = "rex_5_group";

$bezeichner = "Gruppe";

//------------------------------> Gruppe Anlegen|Editieren
if($func == "add" || $func == "edit"){
	
	$mita = new rexform;
	$mita->setWidth(770);
	$mita->setLabelWidth(160);
	$mita->setTablename("rex_5_group");
	if($func == "add"){
		$mita->setFormtype("add");
		$mita->setFormheader("<input type=hidden name=page value=".$mypage."><input type=hidden name=subpage value=".$subpage."><input type=hidden name=func value=".$func." />");
		$mita->setShowFormAlways(false);
		$mita->setValue("subline","$bezeichner erstellen" ,"left",0);
	}else{			
		$mita->setFormtype("edit", "id='".$oid."'", "Gruppe wurde nicht gefunden");
		$mita->setFormheader("<input type=hidden name=page value=".$mypage."><input type=hidden name=subpage value=".$subpage."><input type=hidden name=func value=".$func." /><input type=hidden name=oid value=".$oid.">");
		$mita->setShowFormAlways(true);
		$mita->setValue("subline","$bezeichner bearbeiten" ,"left",0);			
	}
	$mita->setValue("text","Name","name",1);
	$mita->setValue("text","Extras","extras");

	echo $mita->showForm();

	if (!$mita->form_show)
	{
		$func = "";
		echo "<br>";
	}
	else echo "<br /><table cellpadding=5 class=rex><tr><td><a href=index.php?page=".$mypage."&subpage=".$subpage."><b>&laquo; Zurück zur Übersicht</b></a></td></tr></table>";

}

//------------------------------> User löschen
if($func == "delete"){
	$query = "delete from rex_5_group where id='".$oid."' ";
	$delsql = new sql;
	$delsql->debugsql=0;
	$delsql->setQuery($query);
	$func = "";
}

//------------------------------> Userliste
if($func == ""){

	$sql = "select * from rex_5_group order by id";

	$mit = new rexlist;
	$mit->setQuery($sql);
	$mit->setGlobalLink("index.php?page=".$mypage."&subpage=".$subpage."&next=");
	$mit->setValue("id","id");
	$mit->setValue("Name","name");
	$mit->setLink("index.php?page=".$mypage."&subpage=".$subpage."&func=edit&oid=","id");
	$mit->setValue("löschen","");
	$mit->setFormat("ifempty", "- löschen");
	$mit->setFormat("link","index.php?page=".$mypage."&subpage=".$subpage."&func=delete&oid=","id",""," onclick=\"return confirm('sicher löschen ?');\"");	
	echo $mit->showall($next);

	echo "<br /><table cellpadding=5 class=rex><tr><td><a href=index.php?page=".$mypage."&subpage=".$subpage."&func=add><b>+ $bezeichner anlegen</b></a></td></tr></table>";

}

?>
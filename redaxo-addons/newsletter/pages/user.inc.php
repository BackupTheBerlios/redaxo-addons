<?php

$mypage = "newsletter";
$sliste = true;


//------------------------------> User Anlegen|Editieren
if($func == "add" || $func == "edit"){
	
	$mita = new rexform;
	
	$mita->setWidth(770);
	$mita->setLabelWidth(160);
	$mita->setTablename("rex_8_newsletter");
	
	if($func == "add"){
		$mita->setFormtype("add");
		$mita->setFormheader("<input type=hidden name=page value=".$mypage."><input type=hidden name=func value=".$func." />");
		$mita->setShowFormAlways(false);
	}else{			
		$mita->setFormtype("edit", "id='".$oid."'", "User wurde nicht gefunden");
		$mita->setFormheader("<input type=hidden name=page value=".$mypage."><input type=hidden name=func value=".$func." /><input type=hidden name=oid value=".$oid.">");
		$mita->setShowFormAlways(false);				
	}
	
	$mita->setValue("subline","User edieren" ,"left",0);

	$mita->setValue("text","E-Mail","email",1);
	$mita->setValue("text","Vorname","firstname",0);
	$mita->setValue("text","Name","name",0);
	$mita->setValue("text","ID der letzten Aktion","last_nlid",0);
	$mita->setValue("singleselect","Status","status",0,"0|offline|1|online");	

	echo $mita->showForm();

	if (!$mita->form_show)
	{
		$func = "";
		echo "<br>";
	}
	else echo "<br><br><a href=index.php?page=".$mypage."><b>&laquo; Zurück zur Übersicht</b></a><br>";
}

//------------------------------> User löschen
if($func == "delete"){
	$query = "delete from rex_8_newsletter where id='".$oid."' ";
	$delsql = new sql;
	$delsql->debugsql=0;
	$delsql->setQuery($query);
	$func = "";
}



//------------------------------> Userliste
if($func == ""){

	echo "<table class=rex cellpadding=5 cellspacing=1>
		<form action=index.php method=get>
		<input type=hidden name=page value=newsletter>
		<input type=hidden name=subpage value=user>
		<tr>
		<td width=100><b>Suche:</b></td>
		<td><input type=text size=30 class=inp100 name=searchtxt value='".htmlspecialchars($_REQUEST["searchtxt"])."'></td>
		<td width=500><input type=submit value='Suche starten'></td>
		</tr>
		</form>
		</table><br>";

	$addsql = "";
	if ($_REQUEST["searchtxt"] != "")
	{
		$addsql = "where email LIKE '%".$_REQUEST["searchtxt"]."%' ";
		$addsql .= "or name LIKE '%".$_REQUEST["searchtxt"]."%' ";
		$addsql .= "or firstname LIKE '%".$_REQUEST["searchtxt"]."%' ";
		$addsql .= "or last_nlid LIKE '%".$_REQUEST["searchtxt"]."%' ";
		
	}

	$sql = "select * from rex_8_newsletter $addsql order by name";

	$mit = new rexlist;
	$mit->setQuery($sql);
	$mit->setGlobalLink("index.php?page=".$mypage."&subpage=user&searchtxt=".urlencode($_REQUEST["searchtxt"])."&next=");
	//$mit->setValue("id","id");
	$mit->setValue("E-Mail","email");
	$mit->setLink("index.php?page=".$mypage."&func=edit&oid=","id");
	$mit->setValue("Name","name");
	$mit->setLink("index.php?page=".$mypage."&func=edit&oid=","id");
	$mit->setValue("Vorname","firstname");
	$mit->setValue("Aktion ID","last_nlid");
	$mit->setValue("Status","status");
	$mit->setFormat("replace_value","0|<span style='color:#cc6666;'>inaktiv</span>|1|<span style='color:#66cc66;'>aktiv</span>");
	
	$mit->addColumn("löschen","index.php?page=".$mypage."&subpage=user&func=delete&oid=","id"," onclick=\"return confirm('sicher löschen ?');\"");
	
	echo $mit->showall($next);

	echo "<br><br><a href=index.php?page=".$mypage."&subpage=user&func=add><b>User anlegen</b></a><br>";


}

?>
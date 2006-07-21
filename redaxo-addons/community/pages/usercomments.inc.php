<?php

$SF = true;

$mypage = "community";
$subpage = "usercomments";
$table = " rex_5_user_comment";

$bezeichner = "User Kommentar";

$userReplaceValue = "0|Kein User|";
$fromsql = new sql();
$fromsql->setQuery("SELECT id,user_login FROM rex_5_user");

if($fromsql->getRows()>0){
		for($i=0;$i<$fromsql->getRows(); $i++){
			$userReplaceValue .= $fromsql->getValue("id")."|".$fromsql->getValue("user_login")."|";
			if(trim(strtoupper($FORM['csuchtxt'])) == trim(strtoupper($fromsql->getValue("user_login"))) ){
				$FORM['csuchid'][] = $fromsql->getValue("id");
			}
			$fromsql->next();
		}
		$userReplaceValue = substr($userReplaceValue, 0,strlen($userReplaceValue)-1);
}


//------------------------------> Poll Anlegen|Editieren
if($func == "edit"){
	
	$mita = new rexform;
	
	$mita->setWidth(770);
	$mita->setLabelWidth(160);
	$mita->setTablename($table);
	$mita->setFormtype("edit", "id='".$oid."'", "Nachricht wurde nicht gefunden");
	$mita->setFormheader("<input type=hidden name=page value=".$mypage."><input type=hidden name=subpage value=".$subpage."><input type=hidden name=func value=".$func." /><input type=hidden name=oid value=".$oid.">");
	$mita->setShowFormAlways(false);				
	$mita->setValue("subline","$bezeichner edieren" ,"left",0);
	//$mita->setValue("singleselect","Registriert","from_user_registered",0,"0|nein|1|ja");
	$mita->setValue("showtext","Registriert","from_user_registered",0, "width:100%;' disabled='disabled");
	$mita->setValue("showtext","An","user_id",0, "width:100%;' disabled='disabled");
	$mita->setValue("showtext","Von","from_user_id",0, "width:100%;' disabled='disabled");
	$mita->setValue("showtext","Email Von","from_user_email",0, "width:100%;' disabled='disabled");
	$mita->setValue("showtext","Datum","stamp",0, "width:100%;' disabled='disabled");
	$mita->setValue("textarea","Nachricht","message",1);
	$mita->setValue("singleselect","Status","status",0,"0|inaktiv|1|aktiv");
	
				
	echo $mita->showForm();

	if (!$mita->form_show)
	{
		$func = "";
		echo "<br>";
	}
	else echo "<br /><table cellpadding=5 class=rex><tr><td><a href=index.php?page=".$mypage."&subpage=".$subpage."><b>&laquo; Zurück zur Übersicht</b></a></td></tr></table>";
	
}

//------------------------------> Partner löschen
if($func == "delete"){
	$query = "delete from $table where id='".$oid."' ";
	// TODO: entries / voting / session loeschen
	$delsql = new sql;
	$delsql->debugsql=0;
	$delsql->setQuery($query);
	$func = "";
}



//------------------------------> Userliste
if($func == ""){
	
	
	/** Suche  **/
	$addsql = "";
	$link	= "";
	

	$searchfields[] = "user_id";
	$searchfields[] = "from_user_id";
	$searchfields[] = "message";
	
	
	
	$SUCHSEL = new rexselect();
	$SUCHSEL->setMultiple(1); 
	$SUCHSEL->setSize(3); 
	$SUCHSEL->setName("FORM[csuchfeld][]");
	$SUCHSEL->setStyle("width:100%;");
	foreach($searchfields as $sf){
		$SUCHSEL->addOption($sf,$sf);
		if(!is_array($FORM['csuchfeld']))
			$SUCHSEL->setSelected($sf);
		
	}
	if(is_array($FORM['csuchfeld'])){
		foreach($FORM['csuchfeld'] as $cs){
			$SUCHSEL->setSelected($cs);
			$link .= "&FORM[csuchfeld][]=".($cs);
		}	
	}
		
	$STATUSSEL = new rexselect();
	$STATUSSEL->setName("FORM[cstatus]");
	$STATUSSEL->setStyle("width:100%;");
	if($FORM['cstatus'] != ""){
		$STATUSSEL->setSelected($FORM['cstatus']);
		$link .= "&FORM[cstatus]=".urlencode($FORM['cstatus']);
	}
	$STATUSSEL->addOption("Aktiv & Inaktiv", "");
	$STATUSSEL->addOption("Aktiv", 1);
	$STATUSSEL->addOption("Inaktiv", 0);	
	
	
	$suchform = '<table width=770 cellpadding=5 cellspacing=1 border=0 bgcolor=#ffffff>';
	$suchform .= '<form action="'.$_SERVER['PHP_SELF'].'" method="poost" >';
	$suchform .= '<input type="hidden" name="page" value="'.$mypage.'" />';
	$suchform .= '<input type="hidden" name="subpage" value="'.$subpage.'" />';
	$suchform .= '<input type="hidden" name="FORM[csuche]" value="1" />';
	$suchform .= '<tr><th>Suchbegriff</th><th>Tabellenfelder über die gesucht wird</th><th>Status der gesuchten Einträge</th><th>&nbsp;</th></tr>';	
	$suchform .= '<tr><td class="grey" valign="top"><input type="text" name="FORM[csuchtxt]" value="'.htmlspecialchars(stripslashes($FORM['csuchtxt'])).'" style="width:100%;" /></td><td class="grey" valign="top">'.$SUCHSEL->out().'</td><td class="grey" valign="top">'.$STATUSSEL->out().'</td><td class="grey" valign="top"><input type="submit" name="FORM[send]" value="suchen"  class="inp100" /></td></tr>';
	$suchform .= '</form>';
	$suchform .= '</table><br />';
	
	echo $suchform;
	
	if(isset($FORM['csuche']) && $FORM['csuche'] == 1){
		
		if(is_array($FORM['csuchfeld']) && $FORM['csuchtxt'] != ""){
			$addsql .= "WHERE (";
			foreach($FORM['csuchfeld'] as $cs){
				$addsql .= " `".$cs."` LIKE  '%".$FORM['csuchtxt']."%' OR ";			
			}
			$addsql = substr($addsql, 0, strlen($addsql)-3 );
			$addsql .= ")";
		}
		if(is_array($FORM['csuchid']) && is_array($FORM['csuchfeld'])){
			$addsql .= " OR (";
			
			foreach($FORM['csuchfeld'] as $cs){				
				foreach($FORM['csuchid'] as $csid){
					$addsql .= " `".$cs."` LIKE  '%".$csid."%' OR ";			
				}
			}
			$addsql = substr($addsql, 0, strlen($addsql)-3 );
			$addsql .= ")";
		}
		$link .= "&FORM[csuche]=".$FORM['csuche'];
	}
	if($FORM['cstatus'] != ""){
		if($addsql == ""){ $addsql .= " WHERE "; } else { $addsql .= " AND "; }
		$addsql .= " `status`='".$FORM['cstatus']."' ";
	
	}
	
		
	$sql = "select * from $table ".$addsql;

	//echo $sql;
	
	$mit = new rexlist;
	$mit->setQuery($sql);
	$mit->setList(50);
	$mit->setGlobalLink("index.php?page=".$mypage."&subpage=".$subpage."".$link."&next=");
	$mit->setValue("id","id");
	$mit->setValue("Kommentar an","user_id");
	$mit->setValueOrder(1);
	$mit->setFormat("replace_value", $userReplaceValue);
	$mit->setValue("Kommentar von","from_user_id");
	$mit->setValueOrder(1);
	$mit->setFormat("replace_value", $userReplaceValue);
	$mit->setValue("Datum","stamp");
	$mit->setValueOrder(1);
	$mit->setFormat("unixToDateTime");
	$mit->setValue("Status","status");
	$mit->setValueOrder(1);
	$mit->setFormat("replace_value","0|<span style='color:#cc6666;'>inaktiv</span>|1|<span style='color:#66cc66;'>aktiv</span>");
	
	$mit->setValue("editieren","");
	$mit->setFormat("ifempty", "editieren");
	$mit->setLink("index.php?page=".$mypage."&subpage=".$subpage."&func=edit&oid=","id");
	
	$mit->setValue("löschen","");
	$mit->setFormat("ifempty", "löschen");
	$mit->setFormat("link","index.php?page=".$mypage."&subpage=".$subpage."&func=delete&oid=","id","", " onclick=\"return confirm('sicher löschen ?');\"");	
	
	$mit->setOrder($FORM[ordername],$FORM[ordertype]);
	echo $mit->showall($next);
	
	echo "<br />";

}


?>
<?php

$SF = true;

$mypage = "community";
$subpage = "articlecomments";
$table = "rex_5_article_comment";

$bezeichner = "User Kommentar";

$userReplaceValue = "0|Kein User|";
$fromsql = new sql();
$fromsql->setQuery("SELECT id,user_login FROM rex_5_user ORDER BY user_login");

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

$artsql= new sql();
$artsql->setQuery("SELECT id, name FROM rex_article ORDER BY name");
$articleReplaceValue = "0|Kein Artikel|";
if($artsql->getRows()>0){
		for($i=0;$i<$artsql->getRows(); $i++){
			$articleReplaceValue .= $artsql->getValue("id")."|".$artsql->getValue("name")."|";
			if(trim(strtoupper($FORM['csuchtxt'])) == trim(strtoupper($artsql->getValue("name"))) ){
				$FORM['csuchartid'][] = $artsql->getValue("id");
			}
			$artsql->next();
		}
		$articleReplaceValue = substr($articleReplaceValue, 0,strlen($articleReplaceValue)-1);
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
	$mita->setValue("showtext", "Von", "user_id", 0, "width:100%;'");
	$mita->setValue("showtext","Email Von","user_email",0, "width:100%;'");
	$mita->setValue("showtext","Registriert","user_registered",0, "width:100%;'");
	$mita->setValue("showtext", "Artikel", "article_id", 0, "width:100%;'");
	$mita->setValue("showtext","Datum","stamp",0, "width:100%;'");
	$mita->setValue("textarea","Kommentar","comment",1);	
	$mita->setValue("singleselect","Status","status",0,"0|inaktiv|1|aktiv");	
				
	echo $mita->showForm();

	if (!$mita->form_show)
	{
		$func = "";
		echo "<br>";
	}
	else echo "<br /><table cellpadding=5 class=rex><tr><td><a href=index.php?page=".$mypage."&subpage=".$subpage."><b>&laquo; Zur�ck zur �bersicht</b></a></td></tr></table>";
	
}

//------------------------------> Partner l�schen
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
	
	$searchfields[] = "article_id";
	$searchfields[] = "user_id";
	$searchfields[] = "stamp";
	$searchfields[] = "comment";
	
	if($FORM['csuchtxt'] != ""){
		$link .= "&FORM[csuchtxt]=".urlencode($FORM['csuchtxt']);
	}
	
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
	$suchform .= '<tr><th>Suchbegriff</th><th>Tabellenfelder �ber die gesucht wird</th><th>Status der gesuchten Eintr�ge</th><th>&nbsp;</th></tr>';	
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
		if(is_array($FORM['csuchartid']) && is_array($FORM['csuchfeld'])){
			$addsql .= " OR (";
			
			foreach($FORM['csuchfeld'] as $cs){				
				foreach($FORM['csuchartid'] as $csartid){
					$addsql .= " `".$cs."` LIKE  '%".$csartid."%' OR ";			
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
	$mit->setValue("Kommentar id","id");
	$mit->setValueOrder(1);
	$mit->setValue("Artikel Bezeichnung","article_id");
	$mit->setFormat("replace_value", $articleReplaceValue);
	$mit->setValue("Artikel ID","article_id");
	$mit->setValueOrder(1);
	$mit->setValue("Kommentar von","user_id");
	$mit->setValueOrder(1);
	$mit->setFormat("replace_value", $userReplaceValue);
	$mit->setValue("Datum","stamp");
	$mit->setValueOrder(1);
	$mit->setFormat("unixToDateTime");
	$mit->setValue("Status","status");
	$mit->setFormat("replace_value","0|<span style='color:#cc6666;'>inaktiv</span>|1|<span style='color:#66cc66;'>aktiv</span>");
	$mit->setValueOrder(1);
	
	$mit->setValue("editieren","");
	$mit->setFormat("ifempty", "editieren");
	$mit->setLink("index.php?page=".$mypage."&subpage=".$subpage."&func=edit&oid=","id");
	
	$mit->setValue("l�schen","");
	$mit->setFormat("ifempty", "l�schen");
	$mit->setFormat("link","index.php?page=".$mypage."&subpage=".$subpage."&func=delete&oid=","id",""," onclick=\"return confirm('sicher l�schen ?');\"");	
	
	
	$mit->setOrder($FORM[ordername],$FORM[ordertype]);
	echo $mit->showall($next);
	
	echo "<br />";

}


?>
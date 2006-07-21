<?php

$SF = true;

$mypage = "community";
$subpage = "user";
$table = "rex_5_user";

$bezeichner = "User";

if ($_REQUEST["func"]=="sendmail") 
{
	// dem user eine mail schicken
	include $REX["INCLUDE_PATH"]."/addons/community/pages/user_email.inc.php";
}

//------------------------------> Poll Anlegen|Editieren
if($func == "add" || $func == "edit")
{
	
	$mita = new rexform;
	
	$mita->setWidth(770);
	$mita->setLabelWidth(160);
	$mita->setTablename($table);
	
	if($func == "add"){
		$mita->setFormtype("add");
		$mita->setFormheader("<input type=hidden name=page value=".$mypage."><input type=hidden name=subpage value=".$subpage."><input type=hidden name=func value=".$func." />");
		$mita->setShowFormAlways(false);
		$mita->setValue("subline","$bezeichner erstellen" ,"left",0);
	}else{			
		$mita->setFormtype("edit", "id='".$oid."'", "$bezeichner wurde nicht gefunden");
		$mita->setFormheader("<input type=hidden name=page value=".$mypage."><input type=hidden name=subpage value=".$subpage."><input type=hidden name=func value=".$func." /><input type=hidden name=oid value=".$oid.">");
		$mita->setShowFormAlways(false);				
		$mita->setValue("subline","$bezeichner edieren" ,"left",0);
	}

	$mita->setValue("subline","Stammdaten","left",0);
	$mita->setCols(1);
	
	$mita->setValue("text","user_login","user_login",1);
	$mita->setCols(2);
	$mita->setValue("text","user_password","user_password",1);
	$mita->setValue("singleselect","user_status","user_status",0, "1|aktiv [1]|0|inaktiv [0]");
	$mita->setValue("text","user_typ [1=user,7=admin]","user_typ",0);

	$mita->setValue("text","user_name","user_name",0);
	$mita->setValue("text","user_firstname","user_firstname",0);
	$mita->setValue("text","user_email","user_email",0);
	$mita->setValue("empty","","",0);

	if($func == "edit"){
		$mita->setValue("multipleselectsql","Gruppen","",0,
				"select * from rex_5_group order by name","id","name",
				5,"rex_5_u_g","user_id='$oid'","group_id");
		$mita->setValue("empty","","",0);
	}

	$mita->setValue("subline","Persönliche Daten","left",0);
	$mita->setCols(1);		

	$mita->setValue("text","user_street","user_street",0);
	$mita->setCols(2);
	$mita->setValue("text","user_plz","user_plz",0);
	$mita->setValue("text","user_town","user_town",0);
	$mita->setValue("text","user_phone","user_phone",0);

	/*

	$mita->setValue("checkbox","info_newsletter","info_newsletter",0);
	$mita->setValue("checkbox","info_mail","info_mail",0);
	$mita->setValue("text","user_file1","user_file1",0);
	$mita->setValue("text","user_file2","user_file2",0);
	$mita->setValue("singleselect","login_activation","login_activation",0, "0|0|1|1");
	$mita->setValue("text","activation_key","activation_key",0);
	$mita->setValue("text","mail_nlid","mail_nlid",0);
	$mita->setValue("empty","","",0);

	$mita->setValue("singleselect","user_gender","user_gender",0, "w|w|m|m");
	
	$mita->setValue("text","user_birthdate","user_birthdate",0);
	$mita->setValue("text","user_eyecolor","user_eyecolor",0);
	$mita->setValue("text","user_haircolor","user_haircolor",0);
	$mita->setValue("text","user_mobile","user_mobile",0);
	$mita->setValue("text","user_icq","user_icq",0);
	$mita->setValue("text","user_aim","user_aim",0);
	$mita->setValue("text","user_msn","user_msn",0);
	$mita->setValue("text","user_skype","user_skype",0);
	$mita->setValue("singleselect","user_private_data_public","user_private_data_public",0, "1|1|0|0");
	$mita->setValue("empty","","",0);
	
	$mita->setValue("subline","Firmen Daten","left",0);	
	$mita->setCols(1);
	$mita->setValue("text","company_name","company_name",0);
	$mita->setCols(2);
	$mita->setValue("text","company_department","company_department",0);
	$mita->setValue("text","company_operating_field","company_operating_field",0);
	$mita->setValue("text","company_street","company_street",0);
	$mita->setValue("text","company_plz","company_plz",0);
	$mita->setValue("text","company_town","company_town",0);
	$mita->setValue("text","company_phone","company_phone",0);
	$mita->setValue("text","company_mobile","company_mobile",0);
	$mita->setValue("text","company_email","company_email",0);
	$mita->setValue("singleselect","company_data_public","company_data_public",0, "1|1|0|0");	
	
	*/
	
	echo $mita->showForm();

	if ($oid > 0 & $func == "edit") echo "<br /><table cellpadding=5 class=rex><tr><td><a href=index.php?page=".$mypage."&subpage=".$subpage."&func=sendmail&oid=".$oid."><b>&raquo; Mail senden [Zusage/Absage]</b></a></td></tr></table>";


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
	$ssql 	= new sql();
	$ssql->setQuery("DESCRIBE ".$table);
	
	if($FORM['csuchtxt'] != ""){
		$link .= "&FORM[csuchtxt]=".urlencode($FORM['csuchtxt']);
	}
	
	$SUCHSEL = new rexselect();
	$SUCHSEL->setMultiple(1); 
	$SUCHSEL->setSize(5); 
	$SUCHSEL->setName("FORM[csuchfeld][]");
	$SUCHSEL->setStyle("width:100%;");
	for($i=0;$i<$ssql->getRows(); $i++){
		$SUCHSEL->addOption($ssql->getValue("field"),$ssql->getValue("field"));
		if(!is_array($FORM['csuchfeld']))
			$SUCHSEL->setSelected($ssql->getValue("field"));
		$ssql->next();
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
		$link .= "&FORM[csuche]=".$FORM['csuche'];
	}
	if($FORM['cstatus'] != ""){
		if($addsql == ""){ $addsql .= " WHERE "; } else { $addsql .= " AND "; }
		$addsql .= " `user_status`='".$FORM['cstatus']."' ";
	
	}
	
	
	$sql = "select * from $table $addsql";

	//echo $sql;
	
	
	$mit = new rexlist;
	$mit->setQuery($sql);
	$mit->setList(50);
	$mit->setGlobalLink("index.php?page=".$mypage."&subpage=".$subpage."".$link."&next=");
	$mit->setValue("id","id");
	$mit->setValue("Login","user_login");
	$mit->setValueOrder(1);
	$mit->setLink("index.php?page=".$mypage."&subpage=".$subpage."&func=edit&oid=","id");
	$mit->setValue("Vorname","user_firstname");
	$mit->setValueOrder(1);
	$mit->setValue("Name","user_name");
	$mit->setValueOrder(1);
	//$mit->setValue("Status","user_status");
	//$mit->setFormat("replace_value","0|<span style='color:#cc6666;'>inaktiv</span>|1|<span style='color:#66cc66;'>aktiv</span>");
	// $mit->setValueOrder(1);
	$mit->setValue("Status","user_status");
	$mit->setFormat("replace_value","0|<span style='color:#cc6666;'>inaktiv</span>|1|<span style='color:#66cc66;'>aktiv</span>");
	$mit->setValueOrder(1);
	$mit->setValue("löschen","");
	$mit->setFormat("ifempty", "- löschen");
	$mit->setFormat("link","index.php?page=".$mypage."&subpage=".$subpage."&func=delete&oid=","id",""," onclick=\"return confirm('sicher löschen ?');\"");	
	$mit->setOrder($FORM["ordername"],$FORM["ordertype"]);
	echo $mit->showall($next);

	echo "<br /><table cellpadding=5 class=rex><tr><td><a href=index.php?page=".$mypage."&subpage=".$subpage."&func=add><b>+ $bezeichner anlegen</b></a></td></tr></table>";
	

}


?>
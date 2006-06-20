<?php

$mypage = "bug_report";

$status0 = $I18N_BUG_REPORT->msg("status0");
$status1 = $I18N_BUG_REPORT->msg("status1");
$status2 = $I18N_BUG_REPORT->msg("status2");

//------------------------------> Projekt Anlegen|Editieren
if($func == "add" || $func == "edit"){
	
	$mita = new rexform;
	$mita->setWidth(770);
	$mita->setLabelWidth(160);
	$mita->setTablename("rex_21_bug_report");
	
		
	
	if($func == "add"){
		$mita->setFormtype("add");
		$mita->setFormheader("<input type=hidden name=page value=".$mypage."><input type=hidden name=subpage value=".$subpage."><input type=hidden name=func value=".$func." />");
		$mita->setShowFormAlways(false);
	}else{			
		$mita->setFormtype("edit", "id='".$oid."'", "Projekt wurde nicht gefunden");
		$mita->setFormheader("<input type=hidden name=page value=".$mypage."><input type=hidden name=subpage value=".$subpage."><input type=hidden name=func value=".$func." /><input type=hidden name=oid value=".$oid.">");
		$mita->setShowFormAlways(true);				
	}
	$mita->setValue("subline",$I18N_BUG_REPORT->msg("hdl_bugs") ,"left",0);
	$mita->setValue("text",$I18N_BUG_REPORT->msg("title"),"title",1);	
	$mita->setValue("textarea",$I18N_BUG_REPORT->msg("description"),"description",0);
	$mita->setValue("singleselect",$I18N_BUG_REPORT->msg("status"),"status",0, "0|".$I18N_BUG_REPORT->msg("status0")."|1|".$I18N_BUG_REPORT->msg("status1")."|2|".$I18N_BUG_REPORT->msg("status2")."");

	echo $mita->showForm();
	echo "<br /><br /><a href=index.php?page=".$mypage."&subpage=".$subpage."><b>&laquo; ".$I18N_BUG_REPORT->msg("back_to_list")."</b></a><br>";
}

//------------------------------> löschen
if($func == "delete"){
	$query = "delete from rex_21_bug_report where id='".$oid."' ";
	$delsql = new sql;
	$delsql->debugsql=0;
	$delsql->setQuery($query);
	$func = "";
}

//------------------------------> Liste
if($func == ""){
	
	
	// Bugs auslesen
	//--------------------------
	$bugsql = new sql();
	$bugsql->setQuery("select * from rex_21_bug_report order by title");
	
	
	$output .= '<table width="770" cellpadding="5" cellspacing="1" border="0" bgcolor="#ffffff" style="font-size:11px;" >';
	$output .= '<tr><th style="width:20px;">id</th><th>'.$I18N_BUG_REPORT->msg("bug_name").'</th><th>'.$I18N_BUG_REPORT->msg("description").'</th><th style="width:140px;">'.$I18N_BUG_REPORT->msg("function").'</th></tr>';

	for ($i=0;$i<$bugsql->getRows();$i++) {
		
		if ($bugsql->getValue("status") == "0") $class = 'style="background-color:#f8c2c2; vertical-align:top;"';
		if ($bugsql->getValue("status") == "1") $class = 'style="background-color:#c2e1f8; vertical-align:top;"';
		if ($bugsql->getValue("status") == "2") {
			$class = 'style="background-color:#cff8c2; vertical-align:top;"';
			$link = $bugsql->getValue("title");
		}
		else {
			$link = '<a href="index.php?page='.$mypage.'&subpage='.$subpage.'&func=edit&oid='.$bugsql->getValue("id").'">'.$bugsql->getValue("title").'</a>';
		}
	
		$output .= '<tr>
					<td '.$class.'>'.$bugsql->getValue("id").'</td>
					<td '.$class.'>'.$link.'</td>
					<td '.$class.'><pre style="font-family:verdana;">'.wordwrap($bugsql->getValue("description"),50).'</pre></td>
					<td '.$class.'><a href="index.php?page='.$mypage.'&subpage='.$subpage.'&func=delete&oid='.$bugsql->getValue("id").'" onclick="return confirm(\''.$I18N_BUG_REPORT->msg("sicherdel").'\')">'.$I18N_BUG_REPORT->msg("delete").'</a></td>
					</tr>';
		
		$bugsql->next();
	}
	
	$output .= '</table>';
	$output .= '<br /><br /><a href="index.php?page='.$mypage.'&subpage='.$subpage.'&func=add"><b>'.$I18N_BUG_REPORT->msg("create_bug").'</b></a><br>';

	print $output;
	
}

?>
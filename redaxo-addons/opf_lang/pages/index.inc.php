<?php

$mypage = "opf_lang";

include_once $REX[INCLUDE_PATH]."/addons/$mypage/classes/class.rexform.inc.php";
include_once $REX[INCLUDE_PATH]."/addons/$mypage/classes/class.rexlist.inc.php";
include_once $REX[INCLUDE_PATH]."/addons/$mypage/classes/class.rexselect.inc.php";

include $REX[INCLUDE_PATH]."/layout/top.php";

$mypage = "opf_lang";

$subtitle = "&nbsp;&nbsp;<a href=index.php?page=".$mypage."&clang=".$clang."><b>Show list</b></a> | <a href=index.php?page=".$mypage."&clang=".$clang."&func=add><b>Add 'Replace'</b></a>";
rex_title("OPF LANG", $subtitle);

$repl = "";
if (count($REX['CLANG'])>1)
{
	echo "<table width=770 cellpadding=0 cellspacing=1 border=0><tr><td width=30 class=dgrey><img src=pics/leer.gif width=16 height=16 vspace=5 hspace=12></td><td class=dgrey>&nbsp;<b>Sprachen:</b> | ";
	$stop = false;
	while( list($key,$val) = each($REX['CLANG']) )
	{
		if ($key==$clang) echo "$val | ";
		else echo "<a href=index.php?page=$page&clang=$key$sprachen_add>$val</a> | "; 
		if ($repl>"") $repl .= "|";
		$repl .= $key."|".$val; // fuer die add schnipsel funktion
	}
	echo "</b></td></tr></table><br>";
	if ($stop)
	{
		echo "<table width=770 cellpadding=0 cellspacing=1 border=0><tr><td width=30 class=warning><img src=pics/warning.gif width=16 height=16 vspace=5 hspace=12></td><td class=warning>&nbsp;&nbsp;You have no permission to this area</td></tr></table>";
		include $REX['INCLUDE_PATH']."/layout/bottom.php"; 
		exit;	
	}
}else
{
	$repl = "0|".$REX['CUR_CLANG']; // fuer die add schnipsel funktion
	$clang = 0;	
}

function rex_opf_sync()
{
	global $REX;
	
	// abgleich der replacevalue felder..
	$s = new sql;
	// $s->debugsql = 1;
	$s->setQuery("select clang, replacename, name, count(replacename) from rex_opf_lang group by replacename");
	
	for ($i=0;$i<$s->getRows();$i++)
	{
		if (count($REX['CLANG']) != $s->getValue("count(replacename)"))
		{
			reset($REX['CLANG']);
			while( list($key,$val) = each($REX['CLANG']) )
			{
				$lclang = $key;
				$replacename = $s->getValue("replacename");
				$name = $s->getValue("name");
				
				$gs = new sql;
				$gs->setQuery("select clang from rex_opf_lang where clang=$lclang and replacename='$replacename'");
				if ($gs->getRows()==0)
				{
					// erstelle	
					$us = new sql;
					$us->setTable("rex_opf_lang");
					$us->setValue("clang",$lclang);
					$us->setValue("replacename",$replacename);
					$us->setValue("name",$name);
					$us->insert();
					
				}

			}
		}
		$s->next();
	}
	


}

function rex_opf_msg($msg)
{
	$echo .= "<table width=770 cellpadding=5 cellspacing=1 border=0>";
	$echo .= "<tr><td class=warning>".$msg."</td></tr>";
	$echo .= "</table><br />";
	
	return $echo;	
}

rex_opf_sync();

//------------------------------> Anlegen|Editieren
if($func == "add")
{
	
	$SF = true;
	$msg = "";
	if ($_REQUEST["opfreplace"] != "" && $_REQUEST["opfname"] != "")
	{
		$g = new sql;
		$g->debugsql = 0;
		$g->setQuery("select * from rex_opf_lang where replacename= BINARY '".trim($_REQUEST["opfreplace"])."'");
		if ($g->getRows() > 0)
		{
			$msg = "This 'Replace value' already exists.";
		}else
		{
			$msg = "Values saved.";
			$SF = false;

			reset($REX['CLANG']);
			while( list($key,$val) = each($REX['CLANG']) )
			{
				$sv = new sql;
				// $sv->debugsql = 1;
				$sv->setTable("rex_opf_lang");
				$sv->setValue("name",trim($_REQUEST["opfname"]));
				$sv->setValue("replacename",trim($_REQUEST["opfreplace"]));
				$sv->setValue("clang",$key);
				$sv->insert();
			}
		}
		
	}elseif(isset($_REQUEST["opfreplace"]))
	{
		$msg = "Please enter a 'New value' and a 'Replace value'";
	}
	
	if ($msg != "") echo rex_opf_msg($msg);
	
	if ($SF)
	{
		echo "<table style='width: 770px' cellpadding=5 cellspacing=1 border=0 class=rextbl>";
		echo "<form action=index.php method=post><input type=hidden name=clang value=".$clang."><input type=hidden name=page value=".$mypage."><input type=hidden name=func value=".$func." />";
		// echo "<tr><th colspan=2 align=left class=dgrey>Schnipsel hinzufuegen</th></tr>";
		echo "<tr><td class=grey width=150>New value</td><td class=grey><textarea name=opfname cols=30 rows=2 class=inp100>".stripslashes(htmlspecialchars($_REQUEST["opfname"]))."</textarea></td></tr>";
		echo "<tr><td class=grey>Replace value</td><td class=grey><input type=text size=20 name=opfreplace class=inp100 value='".stripslashes(htmlspecialchars($_REQUEST["opfreplace"]))."'></td></tr>";
		echo "<tr><td class=dgrey>&nbsp;</td><td class=dgrey><input type=submit value=edit></td></tr>";
		echo "</table><br />";	
	}
	
	
	
}elseif($func == "edit")
{
	$s = new sql;
	$s->setQuery("select * from rex_opf_lang where id='".$oid."'");

	if ($s->getRows()==0) $msg = "";
	else
	{
			
		$SF = true;
		$msg = "";
		if (isset($_REQUEST["opfname"]))
		{
			$msg = "Value saved.";
			$SF = false;
	
			$sv = new sql;
			// $sv->debugsql = 1;
			$sv->setTable("rex_opf_lang");
			$sv->where("id='".$oid."'");
			$sv->setValue("name",trim($_REQUEST["opfname"]));
			$sv->update();
			
		}
		
		if ($msg != "") echo rex_opf_msg($msg);
		
		if ($SF)
		{
			echo "<table style='width: 770px' cellpadding=5 cellspacing=1 border=0 class=rextbl>";
			echo "<form action=index.php method=post><input type=hidden name=oid value=$oid><input type=hidden name=clang value=".$clang."><input type=hidden name=page value=".$mypage."><input type=hidden name=func value=".$func." />";
			// echo "<tr><th colspan=2 align=left class=dgrey>Schnipsel hinzufuegen</th></tr>";
			echo "<tr><td class=grey width=150>New value</td><td class=grey><textarea cols=30 rows=2 name=opfname class=inp100>".(htmlspecialchars($s->getValue("name")))."</textarea></td></tr>";
			echo "<tr><td class=grey>Replace value</td><td class=grey>".htmlspecialchars($s->getValue("replacename"))."</td></tr>";
			echo "<tr><td class=dgrey>&nbsp;</td><td class=dgrey><input type=submit value=add></td></tr>";
			echo "</table><br />";	
		}	
	
	}
}

//------------------------------> Löschen
if($func == "delete"){
	$query = "delete from rex_opf_lang where replacename= BINARY'".$oname."' ";
	$delsql = new sql;
	// $delsql->debugsql=0;
	$delsql->setQuery($query);
	$func = "";
	echo rex_opf_msg("Replace value deleted");
}

//------------------------------> Liste
//if($func == ""){

	$sql = "select * from rex_opf_lang where rex_opf_lang.clang=".$clang." order by replacename";

	$mit = new rexlist;
	$mit->setQuery($sql);
	$mit->setList(30);
	$mit->setGlobalLink("index.php?page=".$mypage."&clang=".$clang."&next=");
	$mit->setValue("id","rex_opf_lang.id");
	$mit->setLink("index.php?page=".$mypage."&clang=".$clang."&func=edit&oid=","id");
	$mit->setValue("Value","rex_opf_lang.name");
	$mit->setLink("index.php?page=".$mypage."&clang=".$clang."&func=edit&oid=","id");
	$mit->setValue("ReplaceValue","rex_opf_lang.replacename");
	
	$mit->addColumn("delete","index.php?page=".$mypage."&clang=".$clang."&func=delete&oname=","replacename"," onclick=\"return confirm('Delete ?');\"");
	
	echo $mit->showall($next);

	


//}


include $REX[INCLUDE_PATH]."/layout/bottom.php";
?>


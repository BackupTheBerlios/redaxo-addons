<?php

$mypage = "newsletter";
$msg = "";

if ($_REQUEST["nlaction"]== "import")
{

	if ($_FILES['userfile']['tmp_name'] != "")
	{
		$iz = new sql;
		$nlid = "import_".date("YmdHis"); // last action id
		$counter = 0;
		$handle = fopen($_FILES['userfile']['tmp_name'], "r");
		while ($data = fgetcsv($handle, 1000, ";")) {
			$counter++;
			$spalte1 = addslashes($data[0]); // email
			$spalte2 = addslashes($data[1]); // firstname
			$spalte3 = addslashes($data[2]); // name
	
			$iz->query("insert into rex_8_newsletter set last_nlid='".$nlid."', email='".$spalte1."', firstname='".$spalte2."', name='".$spalte3."', status=1 ");
			if ($counter == 3000) exit;
		}
		fclose($handle);
		
		$msg = "$counter E-Mails wurden importiert";

		if ($_REQUEST["nldeleteall"] == 1)
		{
			$iz->query("delete from rex_8_newsletter where last_nlid<>'".$nlid."'");
			$msg .= " und alte E-Mails wurden geloescht!";
		}
		

	}else
	{
		$msg = "Importdatei wurde nicht gefunden !";
	}
	
}





//------------------------------> Formular anzeigen


if ($msg != "") echo "<table class=rex cellpadding=5 cellspacing=1><tr class=warning><td class=warning colspan=2>".$msg."</td></tr></table><br>";

echo "<table class=rex cellpadding=5 cellspacing=1>
	<form action=index.php method=post enctype=multipart/form-data name=mailform>
	<input type=hidden name=page value=newsletter>
	<input type=hidden name=subpage value=import>
	<input type=hidden name=nlaction value=import>
	<tr>
	<td width=150><b>CSV (';' getrennt):</b></td>
	<td><input type=file name=userfile width=10></td>
	</tr>
	
	<tr>
	<td align=right><input type=checkbox name=nldeleteall value=1></td>
	<td>Alte E-Mails löschen</td>
	</tr>
	
	<tr>
	<td></td>
	<td><input type=submit value='abschicken'></td>
	</tr>
	
	</form>
	</table><br>";

?>
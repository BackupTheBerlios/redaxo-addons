<?php

// einem ausgewählten User eine Mail schicken.



// mail definitionen raussuchen
$mails[0]["name"] = "Account freischalten und Mail abschicken";
$mails[0]["email"] = "server@reimelt.de";
$mails[0]["subject"] = "Reimel-Henschel-Group: Vielen Dank für Ihre Anmeldung.";
$mails[0]["body"] = "Hallo ###user_id###,\nVielen Dank für das Interesse. Ihr Anfrage wurde überprüft und Sie sind nun zum Reimelt-Henschel-Extranet angemeldet.
Ihr Zugangsdaten:
Login:###user_login###
Psw:###user_psw###

mit freundlichen Grüßen

Das Reimelt-Henschel-Group Team
";
$mails[0]["action"]["user_status"] = 1;


$mails[1]["name"] = "Account nicht freischalten und Mail abschicken";
$mails[1]["email"] = "server@reimelt.de";
$mails[1]["subject"] = "Reimel-Henschel-Group: Vielen Dank für Ihre Anmeldung.";
$mails[1]["body"] = "Hallo ###EMAIL###,\nVielen Dank für das Interesse. Leider konnten wir Sie nicht zuweisen und haben daher Ihren Zugang nicht freigeschaltet. 

Bitte nehmen Sie nochmal persönlich mit uns Kontakt auf. Unsere Kontaktadresse lautet: 

email: ###EMAIL###
name: ###NAME###
firstname: ###FIRSTNAME###
login: ###LOGIN###
psw: ###PSW###
		
reimelt gmbh
max mustermann
tel: 06021.123.1231

mit freundlichen Grüßen

Das Reimelt-Henschel-Group Team
";
$mails[1]["action"]["user_status"] = 0;


// ----- USER ID ÜBERPRÜFEN

$gu = new sql;
$gu->debugsql = 0;
$gu->setQuery("select * from rex_5_user where id='".$oid."'");

if ($gu->getRows()==1)
{
	echo "<table cellpadding=5 class=rex><tr><td>";

	$mail_id = -1;
	if (isset($_REQUEST["mail_id"])) $mail_id = (int) $_REQUEST["mail_id"];

	if ($subfunc == "send" && is_array($mails) && isset($mails[$mail_id]))
	{
		// 	
		$subject = $mails[$mail_id]["subject"];
		$email = $mails[$mail_id]["email"];
		$body = $mails[$mail_id]["body"];

		// mail versenden
		$mail = new PHPMailer();
		$mail->AddAddress($gu->getValue("user_email"));
		$mail->From = $email;
		$mail->FromName = $email;

		$subject = str_replace( "###EMAIL###",$gu->getValue("user_email"),$subject);
		$subject = str_replace( "###NAME###",$gu->getValue("user_name"),$subject);
		$subject = str_replace( "###FIRSTNAME###",$gu->getValue("user_firstname"),$subject);
		$subject = str_replace( "###LOGIN###",$gu->getValue("user_login"),$subject);

		$body = str_replace( "###EMAIL###",$gu->getValue("user_email"),$body);
		$body = str_replace( "###NAME###",$gu->getValue("user_name"),$body);
		$body = str_replace( "###FIRSTNAME###",$gu->getValue("user_firstname"),$body);
		$body = str_replace( "###LOGIN###",$gu->getValue("user_login"),$body);
		$body = str_replace( "###PSW###",$gu->getValue("user_password"),$body);
		
		$mail->Subject = $subject;
		$mail->AltBody = $body;
		$mail->Send();

		echo "Mail wurde versendet<br /><br />";

		$aktionen = $mails[$mail_id]["action"];
		
		if (is_array($aktionen))
		{
			$uu = new sql;
			$uu->debugsql = 0;
			$uu->setTable("rex_5_user");
			$uu->where("id='".$oid."'");
			foreach($aktionen as $key => $value)
			{
				$uu->setValue($key,$value);
			}	
			$uu->update();
			
			echo "Aktion/en wurden ausgeführt!<br /><br />";
		}

		
	}else
	{
		foreach($mails as $key => $value)
		{
			echo "<br /><br /><a href=index.php?page=community&subpage=user&oid=$oid&func=sendmail&subfunc=send&mail_id=$key>&raquo; ".$value["name"]."</a>";
		}
		echo "<br /><br />";	
	}	
	
	echo "</td></tr></table>";
	
	echo "<br /><table cellpadding=5 class=rex><tr><td><a href=index.php?page=".$mypage."&subpage=".$subpage."&func=edit&oid=$oid><b>&laquo; Zurück zum User</b></a></td></tr></table>";
	$func = "nothing";	
}else
{
	// kein exit -> fehler -> zurueck zur übersicht
	unset($oid);	
}

?>
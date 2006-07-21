<?php

$table = "rex_5_user";

class MyMailer extends PHPMailer {
	var $Host = "localhost";
	var $Mailer = "smtp";
    // var $Host = "";
	// var $Mailer = "";
	// var $SMTPAuth = true;
	// var $Username = "";
	// var $Password = "";
}

// Newsletter senden

// 1. Artikel auswählen

// 2. Welche Listen sollen verwendet werden 
// 2.1. User die Status aktiv ?
// 2.2. Newsletter mit Status aktiv ?

// 3. Testmail empfangen

// 4. Mail abschicken
// 4.1. Abschicken und Position merken
// 4.2. Wenn Abruch dann nochmal starten

$error = "";
$mail_reply = $_REQUEST["mail_reply"];
$mail_subject = $_REQUEST["mail_subject"];
$mail_aid = $_REQUEST["mail_aid"];
$mail_nlid = $_REQUEST["mail_nlid"];

$test_email = $_REQUEST["test_email"];
$test_name = $_REQUEST["test_name"];
$test_firstname = $_REQUEST["test_firstname"];
$method = "start";

if ($mail_nlid == "") $mail_nlid = date("YmdHi");


if ($_REQUEST["method_all"] != "")
{
	if (
		$mail_reply != "" and 
		$mail_subject != "" and 
		$mail_nlid != "" and 
		$mail_aid > 0
	)
	{
		// ----- Mail an alle versenden
		// $mail_reply
		// $mail_subject
		// $mail_aid
		
		// ----- Info
		$msg = "Newsletter wurde versandt!";
		if ($msg != "")
		{
			echo "<table class=rex cellpadding=5 cellspacing=1>
			<tr class=warning>
				<td class=icon><img src=pics/warning.gif width=16 height=16></td>
				<td>Wenn diese Nachricht:\"$msg\" nicht erscheint, und diese Seite sich beendet hat, dann drücken Sie bitte den Reload/Aktualisieren 
				Button Ihres Browsers. Ihre Scriptlaufzeit ist dann zu kurz gewesen um alle Emails zu verschicken. Mit Reload werden die restlichen 
				Emails verschickt.</td>
			</tr>
			</table><br>";		
		}
		
		// ----- content auslesen
		$REX['GG'] = true;
		$REX['REDAXO'] = true;
		$REX_ARTICLE = new article;
		$REX_ARTICLE->setCLang(0);
		$REX_ARTICLE->setArticleId($mail_aid);
		// $REX_ARTICLE->setTemplateId(xx);
		$content = $REX_ARTICLE->getArticleTemplate();
		$REX['GG'] = false;
		$REX['REDAXO'] = true;
		
		
		
		// ----- eMails auslesen und versenden
		$nl = new sql;
		// $nl->debugsql = 1;
		$nl->setQuery("select * from $table where mail_nlid<>'$mail_nlid' and user_status>0 and info_newsletter=1");

		$up = new sql;
		for ($i=0;$i<$nl->getRows();$i++)
		{
			

			// ----- daten auslesen
			$to_email = $nl->getValue("user_email");
			$to_name = $nl->getValue("user_name");
			$to_firstname = $nl->getValue("user_firstname");

			// ----- email miz mail_nlid aktualisieren
			$up->query("update $table set mail_nlid='$mail_nlid' where id='".$nl->getValue("id")."'");

			// ----- email versenden
			$mail = new MyMailer();
			$mail->AddAddress($to_email);
			$mail->From = $mail_reply;
			$mail->FromName = $mail_reply;
	
			// Subject		
			$subject = $mail_subject;
			$subject = str_replace( "###EMAIL###","$to_email",$subject);
			$subject = str_replace( "###NAME###","$to_name",$subject);
			$subject = str_replace( "###FIRSTNAME###","$to_firstname",$subject);
	
			// Bodies
			// html
			$html_body = $content;
			$html_body = str_replace( "###EMAIL###","$to_email",$html_body);
			$html_body = str_replace( "###NAME###","$to_name",$html_body);
			$html_body = str_replace( "###FIRSTNAME###","$to_firstname",$html_body);
			
			// text
			$text_body = $html_body;
			$text_body = preg_replace("#(\<\!-- NL_TEXTVERSION_STRIP_START -->)(.*)(\<\!-- NL_TEXTVERSION_STRIP_END --\>)#imsU", "",  $text_body);
			$text_body = preg_replace("#(\<)(.*)(\>)#imsU", "",  $text_body);
			$text_body = html_entity_decode($text_body);
	
			// echo "<pre>$text_body</pre>";
			
			$mail->Subject = $subject;
			$mail->AltBody = $text_body;
			$mail->Body = $html_body;
			$mail->Send();


			$nl->next();	
		}

	}else
	{
		$msg = "Bitte geben Sie alle Daten ein!";
	}




}else if ($_REQUEST["method"]=="start")
{
	// ----- Testmail verschicken

	if (
		$mail_reply != "" and 
		$mail_subject != "" and 
		$mail_aid > 0 and 
		$test_email != "" and 
		$test_name != "" and 
		$test_firstname != ""
	)
	{
		
		// $mail_reply
		// $mail_subject
		// $mail_aid
		// $test_email
		// $test_name
		// $test_firstname
		// $content
		$to_email = $test_email;
		$to_name = $test_name;
		$to_firstname = $test_firstname;
		
		// Testmail verschicken..

		$REX['GG'] = true;
		$REX['REDAXO'] = true;
		$REX_ARTICLE = new article;
		$REX_ARTICLE->setCLang(0);
		$REX_ARTICLE->setArticleId($mail_aid);
		// $REX_ARTICLE->setTemplateId(xx);
		$content = $REX_ARTICLE->getArticleTemplate();
		$REX['GG'] = false;
		$REX['REDAXO'] = true;
		

		// Allgemeine Initialisierung
		$mail = new MyMailer();
		$mail->AddAddress($to_email);
		$mail->From = $mail_reply;
		$mail->FromName = $mail_reply;

		// Subject		
		$subject = $mail_subject;
		$subject = str_replace( "###EMAIL###","$to_email",$subject);
		$subject = str_replace( "###NAME###","$to_name",$subject);
		$subject = str_replace( "###FIRSTNAME###","$to_firstname",$subject);

		// Bodies
		// html
		$html_body = $content;
		$html_body = str_replace( "###EMAIL###","$to_email",$html_body);
		$html_body = str_replace( "###NAME###","$to_name",$html_body);
		$html_body = str_replace( "###FIRSTNAME###","$to_firstname",$html_body);
		
		// text
		$text_body = $html_body;
		$text_body = preg_replace("#(\<\!-- NL_TEXTVERSION_STRIP_START -->)(.*)(\<\!-- NL_TEXTVERSION_STRIP_END --\>)#imsU", "",  $text_body);
		$text_body = preg_replace("#(\<)(.*)(\>)#imsU", "",  $text_body);
		$text_body = html_entity_decode($text_body);

		// echo "<pre>$text_body</pre>";
		
		$mail->Subject = $subject;
		$mail->AltBody = $text_body;
		$mail->Body = $html_body;
		$mail->Send();
				
		$msg = "Testmail wurde versandt!";
	
	}else
	{
		$_REQUEST["method"] = "";
		$msg = "Bitte geben Sie alle Daten ein!";		
	}
}




if ($msg != "")
{
	echo "<table class=rex cellpadding=5 cellspacing=1>
	<tr class=warning>
		<td class=icon><img src=pics/warning.gif width=16 height=16></td>
		<td>$msg</td>
	</tr>
	</table><br>";		
}






?>


<table class=rex cellpadding=5 cellspacing=1>

<form ENCTYPE=multipart/form-data action=index.php#slice0 method=post name=REX_FORM>
<input type=hidden name=page value=community>
<input type=hidden name=subpage value=newsletter>
<input type=hidden name=method value=start>

<tr>
<th class=icon>&nbsp;</th>
<th colspan=2 style='font-size:12px;'>
<li>Artikel in REDAXO erstellen
<li>###EMAIL### / ###FIRSTNAME### / ###NAME### als Platzhaler erlaubt
<li>Testmail schicken
<li>Wenn Testmail ok, dann Newsletter abschicken
<li>"info_newsletter" und "user_status" muss bei den usern aktiv sein
</th>
</tr>

</table><br>

<table class=rex cellpadding=5 cellspacing=1>

<tr>
<th class=icon>&nbsp;</th>
<th colspan=2><b>Newsletterdaten:</b></th>
</tr>

<tr>
<td class=icon>&nbsp;</td>
<td width=250>Newsletterartikel:</td>
<td><table class=rexbutton><input type=hidden name='mail_aid' value="<?php echo $mail_aid; ?>" id=LINK[1]><tr><td><input type=text size=30 name='mail_name' value="<?php echo stripslashes(htmlspecialchars($mail_name)); ?>" class=inpgrey100 id=LINK_NAME[1] readonly=readonly></td><td class=inpicon><a href=javascript:openLinkMap(1,0);><img src=pics/file_open.gif width=16 height=16 title='Linkmap' border=0></a></td><td class=inpicon><a href=javascript:deleteREXLink(1,0);><img src=pics/file_del.gif width=16 height=16 title='-' border=0></a></td></tr></table></td>
</tr>

<tr>
<td class=icon>&nbsp;</td>
<td>Absendeadresse:</td>
<td><table class=rexbutton><tr><td><input type=text size=30 name='mail_reply' value="<?php echo stripslashes(htmlspecialchars($mail_reply)); ?>" class=inp100 ></td></tr></table></td>
</tr>

<tr>
<td class=icon>&nbsp;</td>
<td>Betreff/Subject:<br>(Auch Platzhalter möglich)</td>
<td><table class=rexbutton><tr><td><input type=text size=30 name='mail_subject' value="<?php echo stripslashes(htmlspecialchars($mail_subject)); ?>" class=inp100 ></td></tr></table></td>
</tr>

<tr>
<td class=icon>&nbsp;</td>
<td>NewsletterID:</td>
<td><table class=rexbutton><tr><td><input type=text size=30 name='mail_nlid' value="<?php echo stripslashes(htmlspecialchars($mail_nlid)); ?>" class=inp100 ></td></tr></table></td>
</tr>

<tr>
<th class=icon>&nbsp;</th>
<th colspan=2><b>Daten für Testmail eingeben:</b></th>
</tr>

<tr>
<td class=icon>&nbsp;</td>
<td>E-Mail:</td>
<td><table class=rexbutton><tr><td><input type=text size=30 name='test_email' value="<?php echo stripslashes(htmlspecialchars($test_email)); ?>" class=inp100 ></td></tr></table></td>
</tr>

<tr>
<td class=icon>&nbsp;</td>
<td>Name:</td>
<td><table class=rexbutton><tr><td><input type=text size=30 name='test_name' value="<?php echo stripslashes(htmlspecialchars($test_name)); ?>" class=inp100 ></td></tr></table></td>
</tr>

<tr>
<td class=icon>&nbsp;</td>
<td>Vorname:</td>
<td><table class=rexbutton><tr><td><input type=text size=30 name='test_firstname' value="<?php echo stripslashes(htmlspecialchars($test_firstname)); ?>" class=inp100 ></td></tr></table></td>
</tr>

<?php

if ($_REQUEST["method"]=="start")
{

	?>
	
	<tr>
	<td class=icon>&nbsp;</td>
	<td>Testmail ok ? Dann Häkchen setzen <br>und Newsletter wird abgeschickt.</td>
	<td><table class=rexbutton style='width:30px;'><tr><td><input type=checkbox name='method_all' value="all"></td></tr></table></td>
	</tr>
		
	<?php
	
}

?>
<tr>
<td class=icon>&nbsp;</td>
<td>&nbsp;</td>
<td><input type=submit value="Mail/s verschicken"></td>
</tr>

</table>
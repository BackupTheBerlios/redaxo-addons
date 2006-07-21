<b>Installation:</b>

- Unter "redaxo/include/addons" einen Ordner "newsletter" anlegen

- Alle Dateien des Archivs nach "redaxo/include/addons/newsletter" entpacken

- In der Datei pages/newsletter.inc.php Zeile 3 Konfiguration anpassen

- Im Header des Templates die Zeile <base href="http://www.example.org/"> hinzufügen

- fertig ;)


<b>Module:</b>

- ggf. Modul aus der Modulsammlung laden:

   - <a href="http://www.redaxo.de/165-Moduldetails.html?module_id=32">User An/Abmeldung</a>
   
   
TODOS:

MAIL_NAME: Name des Empfägers
MAIL_DATE: Datum des e-Mail Versand
MAIL_TIME: Zeit des e-Mail Versand
MAIL_NUMBER: Anzahl der Empfänger
MAIL_CHANGE: Link, um die Empfänger-E-Mail-Adresse zu ändern
MAIL_UNSUBSCRIBE: Link zum löschen des Abonnements
MAIL_STOP: Link zum Pausieren des Abonnements
MAIL_GOON: Link, um das Abonnement wieder aufzunehmen
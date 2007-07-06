/*
Addon by vscope new media
www.vscope.at
*/


<strong>Beschreibung:</strong>

Addon zum Editieren von Modulen und Templates im Filesystem mit beliebigen HTML/PHP Editor.


<strong>Installation:</strong>

- Unter "redaxo/include/addons" einen Ordner "developer" anlegen

- Alle Dateien des Archivs nach "redaxo/include/addons/developer" entpacken

- Schreibrechte auf Ordner "redaxo/include/addons/developer/live_edit" und datei "redaxo/include/addons/developer/live_edit/status.inc.php" setzen

- Im Redaxo AddOn Manager das Plugin installieren

- Im Redaxo AddOn Manager das Plugin aktivieren

- Dem Benutzer das Recht "developer[]" verleihen

- Danach erscheint im Backend unten eine neue Fusszeile

- Dort kann die Live Edit Funktion eingeschaltet werden

- Danach werden die Module / Templates in die Ordner "redaxo/include/addons/developer/live_edit/modules/"
  und "redaxo/include/addons/developer/live_edit/templates/" ausgegeben.

- Diese Files können nun mit einem beliebigen HTML/PHP Editor bearbeitet werden.

- Bei einem Reload im Back/Frontend werden die Module / Templates in der DB mit dem Inhalt der Files
  im "live_edit" Folder automatisch aktualisiert.
<strong>
- Achtung! Solange die Funktion "automatisch updaten" aktiviert ist werden Änderungen die im Backend gemacht werden
  automatisch aus der Version im Filesystem überschrieben.
</strong>

<strong>Credits:</strong>

- <a href="http://www.vscope.at">vscope new media</a>


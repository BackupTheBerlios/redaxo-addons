CREATE TABLE IF NOT EXISTS`rex_13_glossar` (
  `short_id` int(10) unsigned NOT NULL auto_increment,
  `shortcut` varchar(255) NOT NULL default '',
  `description` varchar(255) default NULL,
  `language` int(1) default '0',
  `casesense` tinyint(1) default '0',
  PRIMARY KEY  (`short_id`)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS `rex_13_glossar_lang` (
  `lang_id` int(10) unsigned NOT NULL auto_increment,
  `lang_name` varchar(255) NOT NULL default '',
  `lang_short` varchar(4) NOT NULL default '',
  PRIMARY KEY  (`lang_id`)
) TYPE=MyISAM;


INSERT INTO `rex_13_glossar` VALUES 
(0, 'z.B.', 'zum Beispiel', 1, 1),
(0, 'e.V.', 'eingetragener Verein', 1, 1),
(0, 'Email', 'elektronische Post', 2, 0),
(0, 'd.h.', 'das heisst', 1, 1),
(0, 'bzw.', 'beziehungsweise', 1, 1),
(0, 'usw.', 'und so weiter', 1, 1),
(0, 'PC', 'Personal Computer', 1, 1),
(0, 'FC', 'Fussball Club', 1, 1),
(0, 'Dr.', 'Doktor', 1, 1),
(0, 'Ca.', 'Cirka', 1, 1),
(0, 'Sa.', 'Samstag', 1, 1),
(0, 'So.', 'Sonntag', 1, 1),
(0, 'Std.', 'Stunde', 1, 1),
(0, 'St.', 'Sankt', 1, 1),
(0, 'ggf.', 'gegebenenfalls', 1, 1),
(0, 'Tel.', 'Telefon', 1, 1),
(0, 'KFZ', 'Kraftfahrzeug', 1, 1),
(0, 'CD', 'Compact Disk', 1, 1),
(0, 'VW', 'Volkswagen', 1, 1),
(0, 'u.a.', 'unter anderem', 1, 1),
(0, 'ha.', 'Hektar', 1, 1),
(0, 'km', 'Kilometer', 1, 1),
(0, 'BITV', 'Verordnung zur barrierefreien Informationstechnik', 1, 1),
(0, 'Homepage', 'Internetseite', 2, 1),
(0, 'Team', 'Gemeinschaft', 2, 1),
(0, 'Training', 'Übung', 1, 1),
(0, 'Browser', 'Medium um Internetseiten darzustellen', 2, 1),
(0, 'Hobbies', 'Freizeitgestaltung', 1, 1),
(0, 'Links', 'Adressen von Internetseiten', 2, 1),
(0, 'Outdoor', 'Draussen im Freien', 2, 1),
(0, 'Regie', 'Anleitung', 2, 1),
(0, 'Layout', 'Darstellung', 2, 1),
(0, 'Chat', 'Kommunikation via Internet', 2, 1),
(0, 'Newsletter', 'Rundbrief von Neuigkeiten', 2, 1),
(0, 'Webseite', 'Internetseite', 2, 1),
(0, 'Public', 'Öffentlichkeit', 2, 1),
(0, 'Recycling', 'Wiedereinführung in einen Kreislauf', 1, 1),
(0, 'Containern', 'Aufbewahrungsbehälter', 1, 1),
(0, 'Cowboy', 'Kuhhirte, Überwiegend zu Pferde unterwegs', 2, 1),
(0, 'Louis-seize', 'aus der Zeit von Louis, König aus Frankreich, der einen speziellen Stil darstellt', 2, 1),
(0, 'Windows', 'Fenster', 2, 1),
(0, 'MAC', 'Personal Computer der Firma Apple', 2, 1),
(0, 'Explorer', 'Auswahlfenster', 2, 1),
(0, 'Return', 'Zurück zum letzten Punkt', 2, 1),
(0, 'Screenreader', 'Bildschirmlesegerät', 2, 1),
(0, 'Web', 'Abkürzung für Internet', 2, 1),
(0, 'Accessibility', 'Bezeichnung für gute Bedienbarkeit', 2, 1),
(0, 'World', 'Welt', 2, 1),
(0, 'Wide', 'weite', 2, 1),
(0, 'Stylesheets', 'Datei um das Aussehen einer Internetseite zu beschreiben', 2, 1),
(0, 'JavaScript', 'Programmiersprache', 2, 1),
(0, 'Textbrowser', 'Medium um textbasierte Seiten im Internet darzustellen', 2, 1),
(0, 'Code', 'Quelldateien einer Programmiersprache', 2, 1);


INSERT INTO `rex_13_glossar_lang` VALUES 
(1, 'Deutsch', 'dt'),
(2, 'Englisch', 'en'),
(3, 'Französisch', 'fr');
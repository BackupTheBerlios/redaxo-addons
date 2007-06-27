CREATE TABLE `%TABLE_PREFIX%9_gbook` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `author` varchar(255) NOT NULL default '',
  `message` text NOT NULL,
  `url` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `created` int(11) default NULL,
  `reply` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

INSERT INTO `%TABLE_PREFIX%modultyp` (`id`, `label`, `name`, `category_id`, `ausgabe`, `bausgabe`, `eingabe`, `func`, `php_enable`, `html_enable`, `perm_category`, `createuser`, `updateuser`, `createdate`, `updatedate`) 
VALUES (0, '', 'Gästebuch - Eintragsliste', 0, '<?php\r\ninclude ($REX[''INCLUDE_PATH''].''/addons/guestbook/modules/module.list.inc.php'');\r\n\r\n$f1 = <<<EOD\r\nREX_VALUE[1]\r\nEOD;\r\n\r\n$f2 = <<<EOD\r\nREX_VALUE[2]\r\nEOD;\r\n\r\n$f3 = <<<EOD\r\nREX_VALUE[3]\r\nEOD;\r\n\r\n$f4 = <<<EOD\r\nREX_VALUE[4]\r\nEOD;\r\n\r\n$f5 = <<<EOD\r\nREX_VALUE[5]\r\nEOD;\r\n\r\n$f6 = $this->getValue( ''article_id'');\r\n\r\ngbook_list_output($f1, $f2, $f3, $f4, $f5, $f6);\r\n?>', '', '<?php\r\ninclude ($REX[''INCLUDE_PATH''].''/addons/guestbook/modules/module.list.inc.php'');\r\n\r\n$f1 = <<<EOD\r\nREX_VALUE[1]\r\nEOD;\r\n\r\n$f2 = <<<EOD\r\nREX_VALUE[2]\r\nEOD;\r\n\r\n$f3 = <<<EOD\r\nREX_VALUE[3]\r\nEOD;\r\n\r\n$f4 = <<<EOD\r\nREX_VALUE[4]\r\nEOD;\r\n\r\n$f5 = <<<EOD\r\nREX_VALUE[5]\r\nEOD;\r\n\r\nif ( $f1 == '''') $f1 = 5; \r\nif ( $f2 == '''') $f2 = 5; \r\nif ( $f3 == '''') $f3 = ''%d. %b. %Y - %H:%M''; \r\nif ( $f4 == '''') $f4 = ''%to%@%domain%.%tldomain%''; \r\nif ( $f5 == '''') $f5 = 1; \r\n\r\ngbook_list_input($f1, $f2, $f3, $f4, $f5);\r\n?>', '', 0, 0, '', '', '', UNIX_TIMESTAMP(), 0);
INSERT INTO `%TABLE_PREFIX%modultyp` (`id`, `label`, `name`, `category_id`, `ausgabe`, `bausgabe`, `eingabe`, `func`, `php_enable`, `html_enable`, `perm_category`, `createuser`, `updateuser`, `createdate`, `updatedate`) 
VALUES (0, '', 'Gästebuch - Formular', 0, '<?php\r\ninclude ($REX[''INCLUDE_PATH''].''/addons/guestbook/modules/module.form.inc.php'');\r\n\r\ngbook_form_output( $this->getValue(''article_id''))\r\n?>', '', '', '', 0, 0, '', '', '', UNIX_TIMESTAMP(), 0);
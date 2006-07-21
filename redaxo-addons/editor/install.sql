DROP TABLE IF EXISTS `rex_14_setting`;

CREATE TABLE `rex_14_setting` (
`id` INT NOT NULL AUTO_INCREMENT ,
`showJsElements` tinyint(1) NOT NULL,
`showProperty` tinyint(1) NOT NULL,
`showColor` tinyint(1) NOT NULL,
`showHighlight` tinyint(1) NOT NULL,
`editorWindow` varchar(5) NOT NULL DEFAULT '',
`hltSelector` varchar(6) NOT NULL DEFAULT '',
`hltProperty` varchar(6) NOT NULL DEFAULT '',
`hltValue` varchar(6) NOT NULL DEFAULT '',
`hltDeclaration` varchar(6) NOT NULL DEFAULT '',
`color1` varchar(6) NOT NULL DEFAULT '',
`color2` varchar(6) NOT NULL DEFAULT '',
`color3` varchar(6) NOT NULL DEFAULT '',
`color4` varchar(6) NOT NULL DEFAULT '',
`color5` varchar(6) NOT NULL DEFAULT '',
`color6` varchar(6) NOT NULL DEFAULT '',
`color7` varchar(6) NOT NULL DEFAULT '',
`color8` varchar(6) NOT NULL DEFAULT '',
`color9` varchar(6) NOT NULL DEFAULT '',
`color10` varchar(6) NOT NULL DEFAULT '',
`color11` varchar(6) NOT NULL DEFAULT '',
`color12` varchar(6) NOT NULL DEFAULT '',
`color13` varchar(6) NOT NULL DEFAULT '',
`color14` varchar(6) NOT NULL DEFAULT '',
`color15` varchar(6) NOT NULL DEFAULT '',
`color16` varchar(6) NOT NULL DEFAULT '',
PRIMARY KEY ( `id` )
) TYPE=MyISAM;

INSERT INTO `rex_14_setting` (`id`, `showJsElements`, `showProperty`, `showColor`, `showHighlight`, `editorWindow`, `hltSelector`, `hltProperty`, `hltValue`, `hltDeclaration`, `color1`, `color2`, `color3`, `color4`, `color5`, `color6`, `color7`, `color8`, `color9`, `color10`, `color11`, `color12`, `color13`, `color14`, `color15`, `color16`) VALUES (1, '0', '0', '0', '0', '350', 'ff00ff', 'ff0000', '008000', '000080', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

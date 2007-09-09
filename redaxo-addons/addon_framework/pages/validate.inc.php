<?php

/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: validate.inc.php,v 1.3 2007/09/09 10:55:14 kills Exp $
 */

echo '<h1>Diese Demo zeigt wie man Felder validieren kann</h1>';


// Reihenfolge muss eingehalten werden!

//------------------------------> Parameter

$oid = '72';

//------------------------------> Form

$form = & new rexForm('structure_form');
$form->setApplyUrl('index.php?page=addon_framework');
$form->setEditMode( $oid != '');
//$form->debug = true;

//------------------------------> Hidden Fields

$fieldPage = & new hiddenField('page');
$fieldPage->setValue('addon_framework');

$fieldSubPage = & new hiddenField('subpage');
$fieldSubPage->setValue('validate');

$fieldEntryId = & new hiddenField('oid');
$fieldEntryId->setValue($oid);

//------------------------------> Fields[Allgemein]

$fieldName = & new textField('name', 'Artikelname');
$fieldName->addValidator('notEmpty', 'Name darf nicht Leer sein!', false, false);

$fieldPath = & new textField('path', 'Pfad');
$fieldPath->addValidator('isLength:5', 'Pfad muss mindestens 5 Zeichen lang sein!');

$fieldLang = & new selectField('stuff', 'Sprache');
$fieldLang->addValidator('notEmpty', 'Die Sprache darf nicht Leer sein!');

// Optionen aus der Datenbank hinzufügen
$fieldLang->addSQLOptions('SELECT name,id FROM rex_clang');
// Optionen via Array hinzufügen
$fieldLang->addOptions(array (array ('OPT1', '1'), array ('OPT2', '2')));
// Einzelne Option hinzufügen
$fieldLang->addOption('Einzel', '-1');

//------------------------------> Fields[Meta]

$fieldDesc = & new textAreaField('description', 'Beschreibung');
$fieldDesc->addValidator('notEmpty', 'Die Beschreibung darf nicht Leer sein!', false, false);

$fieldKeys = & new textField('keywords', 'Suchbegriffe');
$fieldKeys->addValidator('notEmpty', 'Die Suchbegriffe dürfen nicht Leer sein!');

//------------------------------> Fields[Meta2]

$fieldDesc2 = & new textAreaField('description', 'Beschreibung');
$fieldDesc2->addValidator('notEmpty', 'Die Beschreibung darf nicht Leer sein!', false, false);

$fieldKeys2 = & new textField('keywords', 'Suchbegriffe');
$fieldKeys2->addValidator('notEmpty', 'Die Suchbegriffe dürfen nicht Leer sein!');

//------------------------------> Add Fields: Section[Allgemein]

$sectionCommon = & new rexFormSection('rex_addon_fw', 'Allgemeines', array ('id' => $oid));
$sectionCommon->addField($fieldName);
$sectionCommon->addField($fieldPath);
$sectionCommon->addField($fieldLang);

//------------------------------> Add Fields: Section[Meta]

$sectionMeta = & new rexFormSection('rex_addon_fw', 'Metadaten', array ('id' => $oid));
$sectionMeta->addField($fieldDesc);
$sectionMeta->addField($fieldKeys);

//------------------------------> Add Fields: Section[Meta2]

$sectionMeta2 = & new rexFormSection('rex_addon_fw', 'Metadaten2', array ('id' => $oid+1));
$sectionMeta2->addField($fieldDesc2);
$sectionMeta2->addField($fieldKeys2);

//------------------------------> Sections

$form->addSection($sectionCommon);
$form->addSection($sectionMeta);
$form->addSection($sectionMeta2);

//------------------------------> Add Fields: Form

$form->addField($fieldPage);
$form->addField($fieldSubPage);
$form->addField($fieldEntryId);

//------------------------------> Show Form

$form->show();
?>
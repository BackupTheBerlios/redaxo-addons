<?php

/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: form_all.inc.php,v 1.2 2007/08/24 12:19:54 kills Exp $
 */

echo '<h1>Diese Demo zeigt alle Formularfelder</h1>';


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

//------------------------------> Fields[Meta]

$fields = array();

$field = & new textField('name', 'Artikelname');
$field->setValue('test');
$fields[] = $field;

$field = & new readOnlyField('name', 'Artikelname');
$field->setValue('test');
$fields[] = $field;

$field = & new readOnlyTextField('name', 'Artikelname');
$field->setValue('test');
$fields[] = $field;

$field = & new dateField('name', 'Artikelname');
$fields[] = $field;

$field = & new wysiwygDateField('name', 'Artikelname');
$fields[] = $field;

$field = & new radioField('name', 'Artikelname');
$field->addRadio('ab', 'ab');
$field->addRadio('cd', 'cd');
$fields[] = $field;

$field = & new checkboxField('name', 'Artikelname');
$field->addBox('ab', 'ab');
$field->addBox('cd', 'cd');
$fields[] = $field;

$field = & new selectField('name', 'Artikelname');
$field->addOption('ab', 'ab');
$field->addOption('cd', 'cd');
$fields[] = $field;

$field = & new htmlField('<p>hier steht beliebiges markup!</p>');
$fields[] = $field;

$field = & new textAreaField('name', 'Artikelname');
$fields[] = $field;

$field = & new rexLinkButtonField('name', 'Artikelname');
$fields[] = $field;

$field = & new rexMediaButtonField('name', 'Artikelname');
$fields[] = $field;

//------------------------------> Add Fields: Section[Allgemein]

$sectionCommon = & new rexFormSection('rex_addon_fw', 'Allgemeines', array ('id' => $oid));
// Hier geht keine FOREACH, wg den Refernzen!
for($i = 0; $i < count($fields); $i++)
  $sectionCommon->addField($fields[$i]);

//------------------------------> Sections

$form->addSection($sectionCommon);

//------------------------------> Add Fields: Form

$form->addField($fieldPage);
$form->addField($fieldSubPage);
$form->addField($fieldEntryId);

//------------------------------> Show Form

$form->show();
?>
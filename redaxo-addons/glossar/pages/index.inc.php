<?php

/**
 * Glossar Addon
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: index.inc.php,v 1.1 2006/08/04 17:46:48 kills Exp $
 */

include $REX['INCLUDE_PATH']."/layout/top.php";

// titel ausgeben
rex_title($I18N_GLOSSAR->msg('glossar_title'), '');

//------------------------------> Parameter

$Basedir = dirname(__FILE__);
  
if (!isset ($short_id))
{
  $short_id = 0;
}
else
{
  $short_id = (int) $short_id;
}

if (!isset ($func))
{
  $func = '';
}

//------------------------------> Eintragsliste
if ($func == '')
{
  require_once $Basedir.'/../../addon_framework/classes/list/class.rex_list.inc.php';
  
  /**
   *  Liste anlegen 
   */
  $sql = 'SELECT * FROM rex_13_glossar, rex_13_glossar_lang WHERE language = lang_id';

  // Standard sortierung nach shortcut aufsteigend
  // Standard suchspalte ist shortcut
  $list = new rexlist($sql, 'shortcut', 'asc', 'shortcut');
  $list->setLabel( $I18N_GLOSSAR->msg('label_list'));
  $list->setName('glossarlist');
  $list->setColGroup(array('50px','*', '85px', '45px', '*'));
  // $list->debug = true;

  /**
   *  Spalten aus dem SQL-ResultSet anlegen 
   */
  $colId = new resultColumn('short_id', $I18N_GLOSSAR->msg('label_id'));
  $colShort = new resultColumn('shortcut', $I18N_GLOSSAR->msg('label_shortcut'));
  $colDesc = new resultColumn('description', $I18N_GLOSSAR->msg('label_description'));
  $colLang = new resultColumn('lang_name', $I18N_GLOSSAR->msg('label_language'));
  $colCase = new resultColumn('casesense', $I18N_GLOSSAR->msg('label_casesens'));

  // ID zentrieren
  $colId->setBodyAttributes('style="text-align: center;"');
  
  // colCase Werte übersetzen
  $colCase->addCondition('casesense', '0', $I18N_GLOSSAR->msg('no'));
  $colCase->addCondition('casesense', '1', $I18N_GLOSSAR->msg('yes'));
  
  /**
   *  Links auf die Spalten legen 
   */
  // Parameter "func" mit dem Wert "edit"
  // Parameter "short_id" mit dem Wert "short_id" aus dem Resultset ("%short_id%")
  $colShort->setParams(array ('func' => 'edit', 'short_id' => '%short_id%'));

  /**
   *  Optionen auf Spalten setzen
   *  Mögliche Optionen: OPT_NONE, OPT_SEARCH, OPT_SORT, OPT_FILTER, OPT_ALL
   */
  // Spalte "id" ist nicht durchsuchbar
  $colId->delOption(OPT_SEARCH);
  // Spalte "language" ist nicht sortierbar u. nicht durchsuchbar
  $colLang->delOption(OPT_SEARCH);
  // Spalte "casesense" ist nicht sortierbar u. nicht durchsuchbar
  $colCase->delOption(OPT_SORT | OPT_SEARCH);

  /**
   *  Spalten zur Anzeige hinzufügen 
   */
  $list->addColumn($colId);
  $list->addColumn($colShort);
  $list->addColumn($colLang);
  $list->addColumn($colCase);
  $list->addColumn($colDesc);

  /**
   *  Tabelle anzeigen 
   */
  $list->show();
}
//------------------------------> Formular
elseif ($func == 'edit' || $func == 'add')
{
  require_once $Basedir.'/../../addon_framework/classes/form/class.rex_form.inc.php';
  
  /** Reihenfolge muss eingehalten werden! */

  //------------------------------> Form

  $form = & new rexForm('glossar_form');
  $form->setApplyUrl('index.php?page=glossar');
  $form->setEditMode($short_id != '');
  //$form->debug = true;

  //------------------------------> Hidden Fields

  $fieldFunc = & new hiddenField('func');
  $fieldFunc->setValue('edit');
  
  $fieldEntryId = & new hiddenField('short_id');
  $fieldEntryId->setValue($short_id);

  //------------------------------> Fields[Allgemein]

  $fieldShort = & new textField('shortcut', $I18N_GLOSSAR->msg('label_shortcut'));
  $fieldShort->addValidator('notEmpty', $I18N_GLOSSAR->msg('miss_shortcut'));

  $fieldDesc = & new textAreaField('description', $I18N_GLOSSAR->msg('label_description'));
  $fieldDesc->addValidator('notEmpty', $I18N_GLOSSAR->msg('miss_description'));

  $fieldLang = & new selectField('language', $I18N_GLOSSAR->msg('label_language'));
  $fieldLang->addAttribute( 'size', '1');
  $fieldLang->addValidator('notEmpty', $I18N_GLOSSAR->msg('miss_language'));
  $fieldLang->addSQLOptions('SELECT lang_name,lang_id FROM rex_13_glossar_lang');

  $fieldCase = & new selectField('casesense', $I18N_GLOSSAR->msg('label_casesens'));
  $fieldCase->addAttribute( 'size', '1');
  $fieldCase->addValidator('notEmpty', $I18N_GLOSSAR->msg('miss_casesens'));
  $fieldCase->addOptions(array (array ($I18N_GLOSSAR->msg('yes'), '1'), array ($I18N_GLOSSAR->msg('no'), '0')));

  //------------------------------> Add Fields: Section[Allgemein]

  $sectionCommon = & new rexFormSection('rex_13_glossar', $I18N_GLOSSAR->msg('label_form'), array ('short_id' => $short_id));
  $sectionCommon->addField($fieldShort);
  $sectionCommon->addField($fieldDesc);
  $sectionCommon->addField($fieldLang);
  $sectionCommon->addField($fieldCase);

  //------------------------------> Sections

  $form->addSection($sectionCommon);

  //------------------------------> Add Fields: Form

  $form->addField($fieldFunc);
  $form->addField($fieldEntryId);

  //------------------------------> Show Form

  $form->show();
}

include $REX['INCLUDE_PATH']."/layout/bottom.php";
?>
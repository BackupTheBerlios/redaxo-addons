<?php

/**
 * Guestbook Addon 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: entries.inc.php,v 1.1 2007/06/11 11:54:26 kills Exp $
 */
 
//------------------------------> Parameter

$Basedir = dirname(__FILE__);

if (!isset ($entry_id))
{
  $entry_id = 0;
}
else
{
  $entry_id = (int) $entry_id;
}

//------------------------------> Eintragsliste
if ($func == '')
{
  require_once $Basedir.'/../../addon_framework/classes/list/class.rex_list.inc.php';

  /**
   *  Liste anlegen 
   */
  $sql = 'SELECT * FROM '.TBL_GBOOK;

  // Standard sortierung nach id absteigend
  // Standard author ist shortcut
  $list = new rexlist($sql, 'id', 'desc', 'author');
  $list->setLabel( $I18N_GBOOK->msg('label_list'));
  //$list->debug = true;

  /**
   *  Spalten aus dem SQL-ResultSet anlegen 
   */
  $colId = new resultColumn('id', $I18N_GBOOK->msg('label_id'));
  $colAuthor = new resultColumn('author', $I18N_GBOOK->msg('label_author'));
  $colMsg = new resultColumn('message', $I18N_GBOOK->msg('label_message'), 'truncate');
  $colUrl = new resultColumn('url', $I18N_GBOOK->msg('label_url'), 'url');
  $colCreated = new resultColumn('created', $I18N_GBOOK->msg('label_created'), 'strftime', 'datetime');

  // ID zentrieren
  $colId->setBodyAttributes('style="text-align: center;"');

  /**
   *  Statische Spalten anlegen 
   */
  // Antworten link
  $colAction = new staticColumn($I18N_GBOOK->msg('reply'), $I18N_GBOOK->msg('label_action'));

  /**
   *  Links auf die Spalten legen 
   */
  // Parameter "func" mit dem Wert "edit"
  // Parameter "entry_id" mit dem Wert "id" aus dem Resultset ("%id%")
  $colAuthor->setParams(array ('func' => 'edit', 'entry_id' => '%id%'));
  // Parameter "func" mit dem Wert "reply"
  // Parameter "entry_id" mit dem Wert "id" aus dem Resultset ("%id%")
  $colAction->setParams(array ('func' => 'edit', 'entry_id' => '%id%', '' => '#reply'));

  /**
   *  Optionen auf Spalten setzen
   *  Mögliche Optionen: OPT_NONE, OPT_SEARCH, OPT_SORT, OPT_FILTER, OPT_ALL
   */
  // Spalte "id" ist nicht durchsuchbar
  $colId->delOption(OPT_SEARCH | OPT_SORT);
  // Spalte "created" ist nicht durchsuchbar
  $colCreated->delOption(OPT_SEARCH);

  /**
   *  Spalten zur Anzeige hinzufügen 
   */
  $list->addColumn($colId);
  $list->addColumn($colAuthor);
  $list->addColumn($colMsg);
  $list->addColumn($colUrl);
  $list->addColumn($colCreated);
  $list->addColumn($colAction);

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

  $form = & new rexForm('structure_form');
  $form->setApplyUrl('index.php?page=guestbook');
  $form->setEditMode($entry_id != '');
//  $form->debug = true;

  //------------------------------> Hidden Fields

  $fieldFunc = & new hiddenField('func');
  $fieldFunc->setValue('edit');

  $fieldEntryId = & new hiddenField('entry_id');
  $fieldEntryId->setValue($entry_id);
  
  $fieldCreatedHidden = & new hiddenField('created');

  //------------------------------> Fields[Allgemein]

  $fieldAuthor = & new textField('author', $I18N_GBOOK->msg('label_author'));
  $fieldAuthor->addValidator('notEmpty', $I18N_GBOOK->msg('miss_author'));

  $fieldMsg = & new textAreaField('message', $I18N_GBOOK->msg('label_message'), array ('style' => 'height: 100px'));
  $fieldMsg->addValidator('notEmpty', $I18N_GBOOK->msg('miss_message'));

  $fieldUrl = & new textField('url', $I18N_GBOOK->msg('label_url'));
  $fieldUrl->addValidator('isUrl', $I18N_GBOOK->msg('incorect_url'), true);

  $fieldEmail = & new textField('email', $I18N_GBOOK->msg('label_email'));
  $fieldEmail->addValidator('isEmail', $I18N_GBOOK->msg('incorect_email'), true);

  $fieldCreated = & new readOnlyField('created', $I18N_GBOOK->msg('label_created'));
  $fieldCreated->setFormatType('strftime');
  $fieldCreated->setFormat('datetime');

  //------------------------------> Fields[Antworten]

  $fieldReply = & new textAreaField('reply', $I18N_GBOOK->msg('label_reply'), array ('style' => 'height: 100px'));

  //------------------------------> Set conditional Field Values
  
  if ( $func == 'add') 
  {
    $fieldCreated->setValue( time());
    $fieldCreatedHidden->setValue( time());
  }
  
  //------------------------------> Add Fields: Section[Allgemein]

  $sectionCommon = & new rexFormSection(TBL_GBOOK, $I18N_GBOOK->msg('label_form'), array ('id' => $entry_id));
  $sectionCommon->addField($fieldAuthor);
  $sectionCommon->addField($fieldMsg);
  $sectionCommon->addField($fieldUrl);
  $sectionCommon->addField($fieldEmail);
  $sectionCommon->addField($fieldCreated);

  //------------------------------> Add Fields: Section[Antworten]

  $sectionReply = & new rexFormSection(TBL_GBOOK, $I18N_GBOOK->msg('label_form_reply'), array ('id' => $entry_id));
  $sectionReply->setAnchor('reply');
  $sectionReply->addField($fieldReply);

  //------------------------------> Sections

  $form->addSection($sectionCommon);
  $form->addSection($sectionReply);

  //------------------------------> Add Fields: Form

  $form->addField($fieldFunc);
  $form->addField($fieldEntryId);
  $form->addField($fieldCreatedHidden);
  
  //------------------------------> Show Form

  $form->show();
}
?>
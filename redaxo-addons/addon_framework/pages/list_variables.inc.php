<?php

/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: list_variables.inc.php,v 1.1 2007/08/24 12:18:24 kills Exp $
 */

echo '<h1>Diese Demo zeigt wie man in eine rexList eigenes HTML-Markup einbinden kann</h1>';

//------------------------------> Eintragsliste

/*
Verfügbare Variablen

Platzhalter: Vor der Liste
  LIST_VAR_TOP
Platzhalter: Vor den Kopfleisten
  LIST_VAR_BEFORE_HEAD
Platzhalter: Nach den Kopfleisten
  LIST_VAR_AFTER_HEAD
Platzhalter: Vor dem Kopfbereich der Tabelle
  LIST_VAR_BEFORE_DATAHEAD
Platzhalter: Nach dem Kopfbereich der Tabelle
  LIST_VAR_AFTER_DATAHEAD
Platzhalter: Vor dem Datenbereich der Tabelle
  LIST_VAR_BEFORE_DATA
Platzhalter: Nach dem Datenbereich der Tabelle
  LIST_VAR_AFTER_DATA
Platzhalter: Vor den Fußleisten
  LIST_VAR_BEFORE_FOOT
Platzhalter: Nach den Fußleisten
  LIST_VAR_AFTER_FOOT
Platzhalter: Nach der Liste
  LIST_VAR_BOTTOM
Platzhalter: Erscheint, wenn die Liste keine Datensätze enthält
  LIST_VAR_NO_DATA
*/

if ($func == '')
{
  /*
   *  Liste anlegen
   */
  if (empty ($category_id))
  {
    $category_id = 0;
  }
  $sql = 'SELECT * FROM rex_article WHERE re_id='.$category_id.' AND startpage=1 AND clang='.$clang;
  // Standard sortierung nach CATPRIOR aufsteigend
  // Standard suchspalte ist Name
  $list = new rexlist($sql, 'catprior', 'asc', 'name');
  $list->setVar(LIST_VAR_TOP, '<p>First Header<p>');
  $list->setVar(LIST_VAR_BEFORE_HEAD, '<p>Before Head</p>');
  $list->setVar(LIST_VAR_AFTER_HEAD, '<p>After Head</p>');
  $list->setVar(LIST_VAR_BEFORE_DATAHEAD, '<p>Before DataHead</p>');
  $list->setVar(LIST_VAR_AFTER_DATAHEAD, '<tr><td colspan="3">After DataHead</td></tr>');
  $list->setVar(LIST_VAR_BEFORE_DATA, '<tr><td colspan="3">Before Data</td></tr>');
  $list->setVar(LIST_VAR_AFTER_DATA, '<tr><td colspan="3">After Data</td></tr>');
  $list->setVar(LIST_VAR_BEFORE_FOOT, '<p>Before Foot</p>');
  $list->setVar(LIST_VAR_AFTER_FOOT, '<p>After Foot</p>');
  $list->setVar(LIST_VAR_BOTTOM, '<p>Bottom</p>');
  $list->setVar(LIST_VAR_NO_DATA, '<p>No Data</p>');

  /*
   *  Spalten aus dem SQL-ResultSet anlegen
   */
  $colId = new resultColumn('id', 'ID');
  $colName = new resultColumn('name', 'Kategorie');
  $colPrio = new resultColumn('catprior', 'Prio');

  $list->addColumn($colId);
  $list->addColumn($colName);
  $list->addColumn($colPrio);

  /*
   *  Tabelle anzeigen
   */
  $list->show();
}
?>
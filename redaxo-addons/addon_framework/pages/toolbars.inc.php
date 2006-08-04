<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: toolbars.inc.php,v 1.1 2006/08/04 17:46:28 kills Exp $
 */

//------------------------------> Eintragsliste

if ($func == '')
{
  /* 
   * Liste anlegen 
   */
  
  // Standard sortierung nach Name aufsteigend
  // Standard suchspalte ist Name
  $sql = 'SELECT * FROM rex_article';
  $list = new rexlist($sql, 'Name', 'ASC', 'Name');

  /*
   * Spalten aus dem SQL-ResultSet anlegen 
   */
  $colId = new resultColumn('id', 'ID');
  $colName = new resultColumn('name', 'Name');
  $colPrio = new resultColumn('catprior', 'Prio');
  $colUpdate = new resultColumn('updateuser', 'Aktualisiert von');
  $colCreate = new resultColumn('createuser', 'Erstellt von');

  /*
   * Statische Spalten anlegen 
   */
  // L�sch link
  $colAction = new staticColumn('Aktion', 'l&ouml;schen');

  /*
   *  Sonder Spalten anlegen 
   */
  // fortlaufende Nr
  $colCount = new countColumn('#', '');

  /*
   *  Links auf die Spalten legen 
   */
  // Parameter "func" mit dem Wert "edit"
  // Parameter "id" mit dem Wert "id" aus dem Resultset ("%id%") 
  $colName->setParams(array ('func' => 'edit', 'id' => '%id%'));
  // Parameter "func" mit dem Wert "delete"
  // Parameter "id" mit dem Wert "id" aus dem Resultset ("%id%")
  $colAction->setParams(array ('func' => 'delete', 'id' => '%id%'));

  /*
   *  Spalten zur Anzeige hinzuf�gen 
   */
  $list->addColumn($colCount);
  $list->addColumn($colName);
  $list->addColumn($colId);
  $list->addColumn($colPrio);
  $list->addColumn($colUpdate);
  $list->addColumn($colCreate);
  $list->addColumn($colAction);

  /*
   *  Toolbars zur Anzeige hinzuf�gen 
   */
  // Navigationsleiste, die sich �berhalb der Liste befindet 
  // und sich �ber deren volle Breite erstreckt
  $list->addToolbar(new browseBar(), 'top', 'full');

  // Statusleiste, die sich �berhalb der Liste befindet 
  // und sich �ber die h�lfte der Breite erstreckt
  $list->addToolbar(new statusBar(), 'top', 'half');
  // Leiste zum beeinflussen der Elementanzahl pro Seite, die sich �berhalb der Liste befindet 
  // und sich �ber die h�lfte der Breite erstreckt
  $list->addToolbar(new maxElementsBar(), 'top', 'half');

  // Statusleiste, die sich unterhalb der Liste befindet 
  // und sich �ber die h�lfte der Breite erstreckt
  $list->addToolbar(new statusBar(), 'bottom', 'half');
  // Suchleiste, die sich unterhalb der Liste befindet 
  // und sich �ber die h�lfte der Breite erstreckt
  $list->addToolbar(new searchBar(), 'bottom', 'half');

  /*
   *  Tabelle anzeigen 
   */
  $list->show(false); // Default Toolbars ausblenden
}
?>
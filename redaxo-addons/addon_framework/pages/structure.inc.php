<?php

/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: structure.inc.php,v 1.4 2007/09/10 17:01:38 kills Exp $
 */

echo '<h1>Diese Demo zeigt eine Nachbildung der Strukturverwaltung</h1>';

//------------------------------> Eintragsliste

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

  /*
   *  Spalten aus dem SQL-ResultSet anlegen
   */
  $colId = new resultColumn('id', 'ID');
  // ID zentrieren
  $colId->setBodyAttributes('style="text-align: center"');
  $colName = new resultColumn('name', 'Kategorie', '', '%name% [%id%]');
  $colPrio = new resultColumn('catprior', 'Prio');

  /*
   *  Statische Spalten anlegen
   */
  // Icon Spalte
  $colIcon = new staticColumn('<img src="media/folder.gif"/>', '<img src="media/folder_plus.gif"/>');
  // Icons zentrieren
  $colIcon->setHeadAttributes('style="text-align: center"');
  $colIcon->setBodyAttributes('style="text-align: center"');
  // Bearbeiten Spalte
  $colEdit = new staticColumn('Kategorie editieren/l�schen', 'Kategorie editieren');
  // Online/Offline Spalte
  $colOnOffline = new staticColumn('', 'Status/Funktion');
  $colOnOffline->addCondition('status', '1', '<span style="color: #00aa00;">online</span>', array ('func' => 'status', 'mode' => 'offline_it', 'category_id' => '%id%'));
  $colOnOffline->addCondition('status', '0', '<span style="color: #aa0000;">offline</span>', array ('func' => 'status', 'mode' => 'online_it', 'category_id' => '%id%'));

  /*
   *  Links auf die Spalten legen
   */
  // Parameter "category_id" mit dem Wert "id" aus dem Resultset ("%id%")
  $colName->setParams(array ('category_id' => '%id%'));
  // Parameter "func" mit dem Wert "edit"
  // Parameter "id" mit dem Wert "id" aus dem Resultset ("%id%")
  $colEdit->setParams(array ('func' => 'edit', 'id' => '%id%'));

  /*
   *  Optionen auf Spalten setzen
   *  M�gliche Optionen: OPT_NONE, OPT_SEARCH, OPT_SORT, OPT_FILTER, OPT_ALL
   */
  // Spalte "ID" ist nicht sortierbar
  $colId->delOption(OPT_SORT);
  // Spalte "Prio" ist nicht durchsuchbar
  $colPrio->delOption(OPT_SEARCH);

  /*
   *  Spalten zur Anzeige hinzuf�gen
   */
  $list->addColumn($colIcon);
  $list->addColumn($colId);
  $list->addColumn($colName);
  $list->addColumn($colPrio);
  $list->addColumn($colEdit);
  $list->addColumn($colOnOffline);

  /*
   *  Tabelle anzeigen
   */
  $list->show();
}
?>
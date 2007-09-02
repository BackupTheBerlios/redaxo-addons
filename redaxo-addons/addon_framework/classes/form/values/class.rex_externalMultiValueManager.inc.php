<?php
/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rex_externalMultiValueManager.inc.php,v 1.1 2007/09/02 14:00:28 kills Exp $
 */

/*
 * externalMultiValueManager für MultiValueFormFields die Daten in einer
 * externen Tabelle speichern
 */

class rex_externalMultiValueManager extends rex_multiValueManager
{
  var $foreignTable;
  var $foreignField;

  function rex_externalMultiValueManager(&$formField, $foreignTable, $foreignField)
  {
    parent::rex_multiValueManager($formField);
    $this->foreignTable = $foreignTable;
    $this->foreignField = $foreignField;
  }

  /*
   * Prepariert den InsertValue um das Array als String in die DB zu speichern
   * @access protected
   */
  function getInsertValue($value)
  {
    $value = (array) $value;

    $section =& $this->getSection();

    // Alle vorhanden Werte löschen
    $sql = new rex_sql();
    $sql->setTable($this->foreignTable);
    $sql->setWhere($section->_getWhereString());
    $sql->delete();

    // und anschließend alle neu eintragen
    foreach($value as $val)
    {
      // Parameter aus der Ursprungstabelle mit abspeichern damit später darüber verknüpft werden kann
      foreach($section->getWhere() as $whereColName => $whereColValue)
      {
        $sql->setValue($whereColName, $whereColValue);
      }
      // Den zu speichernden Wert
      $sql->setValue($this->foreignField, $val);
      $sql->setTable($this->foreignTable);
      $sql->insert();
    }

    // null zurückgeben, damit zu diesem Feld nichts im rexForm gespeichert wird
    return null;
  }

  /*
   * Prepariert den Value um den String aus der DB als Array zurückzugeben
   * @access protected
   */
  function getValue($value)
  {
    if (!is_array($value))
    {
      $value = array();
      $section =& $this->getSection();

      // Alle vorhanden Werte löschen
      $sql = new rex_sql();
      $sql->setQuery('SELECT `'. $this->foreignField .'` FROM `'. $this->foreignTable .'` WHERE '. $section->_getWhereString());

      for($i = 0; $i < $sql->getRows(); $i++)
      {
        $value[] = $sql->getValue($this->foreignField);
        $sql->next();
      }
    }
    return $value;
  }
}
?>
<?php


/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.foreignField.inc.php,v 1.2 2007/08/24 09:06:22 kills Exp $
 */

class foreignField extends readOnlyField
{
  var $table;
  var $field;
  var $foreignField;

  function foreignField($name, $label, $attributes = array (), $id = '')
  {
    $this->rexFormMultiValueField($name, $label, $attributes, $id);
    $this->multiple = false;
  }

  /**
   * Setzt die Verkn�pfung des ForeignFields mit der FormSection
   * 
   * Beispiel:
   * <code>
   * $field->setForeignField( 'rex_article', 'name', 'id');
   * </code>
   *   
   * Der aktuelle Datensatz wird mit dem Feld "name" aus der Tabelle "rex_article" verkn�pft.
   * Die Verkn�pfung findet wird vorgenommen �ber das Feld "id".
   * 
   * @access public
   */
  function setForeignField($table, $field = '', $foreignfield = '')
  {
    $this->table = $table;
    $this->field = $field;
    $this->foreignField = $foreignfield;
  }

  function getForeignField()
  {
    $field = $this->foreignField;

    if ($field == '')
    {
      $field = $this->getName();
    }

    return $field;
  }

  function getField()
  {
    $field = $this->field;

    if ($field == '')
    {
      $field = $this->getName();
    }

    return $field;
  }

  function getTable()
  {
    return $this->table;
  }

  /**
   * Gibt den HTML Content zur�ck
   */
  function get()
  {
    $table = $this->getTable();
    $field = $this->getField();
    $foreignField = $this->getForeignField();
    $value = $this->formatValue();

    $qry = 'SELECT '.$field.' FROM '.$table.' WHERE '.$foreignField.' = "'.$value.'"';
    $sql = new rex_sql();
    // $sql->debugsql = true;
    $sql->setQuery($qry);

    if ($sql->getRows() == 1)
    {
      return $sql->getValue($field);
    }

    return '';
  }
}
?>
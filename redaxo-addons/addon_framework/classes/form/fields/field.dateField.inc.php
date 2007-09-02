<?php

/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.dateField.inc.php,v 1.3 2007/09/02 14:00:29 kills Exp $
 */

class dateField extends rexFormField
{
  var $daySelect;
  var $monthSelect;
  var $yearSelect;

  function dateField($name, $label, $attributes = array (), $id = '')
  {
    $this->rexFormField($name, $label, $attributes, $id);

    $select = new rex_select();
    $select->setName($name . '[]');
    $select->setSize(1);
    $select->setStyle('width: 24%;');

    $this->daySelect = $select;
    $this->monthSelect = $select;
    $this->yearSelect = $select;

    foreach (range(1, 31) as $day)
      $this->daySelect->addOption($day, $day);

    foreach (range(1, 12) as $month)
      $this->monthSelect->addOption($month, $month);

    foreach (range(2000, 2050) as $year)
      $this->yearSelect->addOption($year, $year);

  }

  /*
   * Prepariert den InsertValue um das Array als String in die DB zu speichern
   * @access protected
   */
  function _getInsertValue()
  {
    $value = parent :: _getInsertValue();
    if (is_array($value))
    {
      $value = mktime(0, 0, 0, $value[1], $value[0], $value[2]);
    }

    return $value;
  }

  /*
   * Prepariert den Value um den String aus der DB als Array zurückzugeben
   * @access protected
   */
  function _getValue()
  {
    $value = parent :: _getValue();

    if (!is_array($value))
    {
    	// beim add, auf aktuelle Datumswerte zurückgreifen
      if ($value == 0 || $value == '')
      {
        $value = time();
      }

			// initialwerte
      $day = date('j', $value);
      $month = date('n', $value);
      $year = date('Y', $value);
    }
    else
    {
    	// wiederanzeige nach POST
      $day = $value[0];
      $month = $value[1];
      $year = $value[2];
    }

    $this->daySelect->setSelected($day);
    $this->monthSelect->setSelected($month);
    $this->yearSelect->setSelected($year);

    // Hier immer den aktuellen Timestamp zurückliefern, da daraus der Wert für die DB entsteht
    return mktime(0, 0, 0, $month, $day, $year);
  }

  function get()
  {
    $this->getValue();
    // Id kann nur in get() gesetzt werden, da vorher die Referenzen zur Section+Form fehlen
    $this->daySelect->setId($this->getId() . '_day');
    $this->monthSelect->setId($this->getId() . '_month');
    $this->yearSelect->setId($this->getId() . '_year');

    return $this->daySelect->get() . $this->monthSelect->get() . $this->yearSelect->get();
  }
}
?>
<?php
/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rex_internalMultiValueManager.inc.php,v 1.1 2007/09/02 14:00:28 kills Exp $
 */

/*
 * internalMultiValueManager für MultiValueFormFields die Daten in der gleichen
 * Tabelle speichern
 */

class rex_internalMultiValueManager extends rex_multiValueManager
{
  var $value_separator;

  function rex_internalMultiValueManager(&$formField, $value_separator = '||')
  {
    parent::rex_multiValueManager($formField);
    $this->setValueSeparator($value_separator);
  }

  /**
   * Setzt den Trenner für die Werte
   * @access public
   */
  function setValueSeparator($value_separator)
  {
    rex_valid_type($value_separator, 'string', __FILE__, __LINE__);

    $this->value_separator = $value_separator;
  }

  /*
   * Prepariert den InsertValue um das Array als String in die DB zu speichern
   * @access protected
   */
  function getInsertValue($value)
  {
    if (is_array($value))
    {
      $value = implode($this->value_separator, $value);

      // Mit ValueSeparator davor und danach, damit man leichter mit LIKE selektieren kann
      if($value != '')
        $value = $this->value_separator . $value . $this->value_separator;
    }

    return $value;
  }

  /*
   * Prepariert den Value um den String aus der DB als Array zurückzugeben
   * @access protected
   */
  function getValue($value)
  {
    if (!is_array($value))
    {
      $value = explode($this->value_separator, $value);
      $count = count($value);
      if($count > 0)
      {
        // Da ValueSeparator davor und danach, erstes und letztes Element rauswerfen
        if($value[0] == $this->value_separator)
        {
          unset($value[0]);
          unset($value[$count-1]);
        }
      }
    }

    return $value;
  }
}
?>
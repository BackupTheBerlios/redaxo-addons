<?php
/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rex_simpleDateValueManager.inc.php,v 1.1 2008/03/11 11:21:58 kills Exp $
 */

/*
 * rex_simpleDateValueManager for managing simple DateValues
 */

class rex_simpleDateValueManager extends rex_valueManager
{
  var $date_format;

  function rex_simpleDateValueManager(&$formField)
  {
    $this->date_format = 'Y-m-d';
    parent::rex_valueManager($formField);
  }

  /*
   * Prepariert den InsertValue um das Array als String in die DB zu speichern
   * @access protected
   */
  function getInsertValue($value)
  {
    // Aktueller Timestamp als Datum speichern
    return date($this->date_format, $value);
  }

  /*
   * Prepariert den Value um den String aus der DB als Array zurückzugeben
   * @access protected
   */
  function getValue($value)
  {
    // Value ist schon Timestamp
    if(ctype_digit($value))
      return $value;

    // Umwandeln von Datumstring aus der DB in Timestamp
    return strtotime($value);
  }
}
?>
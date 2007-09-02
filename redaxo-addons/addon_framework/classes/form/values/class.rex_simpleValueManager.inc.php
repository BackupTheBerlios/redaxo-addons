<?php
/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rex_simpleValueManager.inc.php,v 1.1 2007/09/02 14:00:28 kills Exp $
 */

/*
 * simpleValueManager
 */

class rex_simpleValueManager extends rex_valueManager
{
  function rex_simpleValueManager(&$formField)
  {
    parent::rex_valueManager($formField);
  }

  /*
   * Prepariert den InsertValue um das Array als String in die DB zu speichern
   * @access protected
   */
  function getInsertValue($value)
  {
    return $value;
  }

  /*
   * Prepariert den Value um den String aus der DB als Array zurückzugeben
   * @access protected
   */
  function getValue($value)
  {
    return $value;
  }
}
?>
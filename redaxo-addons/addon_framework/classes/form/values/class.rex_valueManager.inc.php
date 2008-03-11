<?php

/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rex_valueManager.inc.php,v 1.2 2008/03/11 11:25:28 kills Exp $
 */

/*
 * Abstrakte Basisklasse für valueManager
 */
class rex_valueManager
{
  var $formField;

  function rex_valueManager(&$formField)
  {
    $this->formField =& $formField;
  }

  function &getSection()
  {
    return $this->formField->getSection();
  }

  /*
   * Prepariert den Wert $value um ihn im Formular anzuzeigen.
   * Diese Methode wird sowohl beim 1. Anzeigen, als auch beim wiederanzeigen
   * nach klick auf den Übernehmenbutton durchlaufen.
   * @access protected
   */
  function getValue($value)
  {
    trigger_error('You have to override the method rex_valueManager::getValue', E_USER_ERROR);
  }

  /*
   * Prepariert den Wert $value um ihn in die DB zu speichern
   * @access protected
   */
  function getInsertValue($value)
  {
    trigger_error('You have to override the method rex_valueManager::getInsertValue', E_USER_ERROR);
  }
}

?>
<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.passwordField.inc.php,v 1.3 2007/09/02 14:00:28 kills Exp $
 */

class passwordField extends rexFormField
{
  function passwordField($name, $label, $attributes = array (), $id = '')
  {
    $this->rexFormField($name, $label, $attributes, $id);
  }

  function getInsertValue()
  {
    // Falls der User kein Passwort eingibt, nichts speichern
    // --> altes PWD bleibt erhalten
    // --> neues wird nur gesetzt, wenn Eingabe gemacht wurde
    if($this->getValue() != '')
    {
      return $this->getValue();
    }
    return null;
  }

  function get()
  {
    return sprintf('<input type="password" name="%s" value="" id="%s" tabindex="%s"%s />', $this->getName(), $this->getId(), rex_tabindex(false), $this->getAttributes());
  }
}
?>
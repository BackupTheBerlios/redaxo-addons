<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.readOnlyTextField.inc.php,v 1.1 2006/08/04 17:46:28 kills Exp $
 */

class readOnlyTextField extends readOnlyField
{
  function readOnlyTextField($name, $label, $attributes = array (), $id = '')
  {
    $this->rexFormField($name, $label, $attributes, $id);
  }

  function get()
  {
    return sprintf('<input type="text" name="%s" value="%s" id="%s" readonly="readonly"%s />', $this->getName(), $this->getValue(), $this->getId(), $this->getAttributes());
  }
}
?>
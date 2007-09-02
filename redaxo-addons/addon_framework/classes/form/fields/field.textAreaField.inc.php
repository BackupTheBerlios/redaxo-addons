<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.textAreaField.inc.php,v 1.2 2007/09/02 14:00:29 kills Exp $
 */

class textAreaField extends textField
{
  function textField($name, $label, $attributes = array (), $id = '')
  {
    $this->rexFormField($name, $label, $attributes, $id);
  }

  function get()
  {
    return sprintf('<textarea name="%s" id="%s" tabindex="%s"%s>%s</textarea>', $this->getName(), $this->getId(), rex_tabindex(false), $this->getAttributes(), $this->getValue());
  }
}
?>
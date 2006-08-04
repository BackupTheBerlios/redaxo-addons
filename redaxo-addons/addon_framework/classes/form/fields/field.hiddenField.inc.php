<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.hiddenField.inc.php,v 1.1 2006/08/04 17:46:28 kills Exp $
 */
 
class hiddenField extends rexFormField
{
  function hiddenField($name)
  {
    $this->rexFormField($name, '');
  }

  function get()
  {
    return sprintf('<input type="hidden" name="%s" value="%s" id="%s"%s />', $this->getName(), $this->getValue(), $this->getId(), $this->getAttributes());
  }
}
?>
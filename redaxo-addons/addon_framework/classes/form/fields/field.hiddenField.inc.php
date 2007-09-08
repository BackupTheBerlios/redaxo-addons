<?php

/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.hiddenField.inc.php,v 1.3 2007/09/08 09:40:52 kills Exp $
 */

class hiddenField extends rexFormField
{
  function hiddenField($name)
  {
    $this->rexFormField($name, '');
  }

  function getId()
  {
    return 'hidden_'. parent::getId();
  }

  function get()
  {
    return sprintf('<input type="hidden" name="%s" value="%s" id="%s"%s />', $this->getName(), $this->getValue(), $this->getId(), $this->getAttributes());
  }
}
?>
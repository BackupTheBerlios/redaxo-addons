<?php

/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.htmlField.inc.php,v 1.1 2007/08/16 17:06:40 kills Exp $
 */

class htmlField extends rexFormField
{
  var $html;

  function htmlField($html)
  {
    $this->html = $html;
    $this->rexFormField('', '', array(), '');
  }

  function get()
  {
    return $this->html;
  }
}
?>
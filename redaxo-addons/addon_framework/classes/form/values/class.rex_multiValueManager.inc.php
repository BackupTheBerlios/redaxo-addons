<?php
/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rex_multiValueManager.inc.php,v 1.1 2007/09/02 14:00:28 kills Exp $
 */

/*
 * multiValueManager dient als simpleValueManager für MultiValueFormFields
 */

class rex_multiValueManager extends rex_simpleValueManager
{
  function rex_multiValueManager(&$formField)
  {
    parent::rex_simpleValueManager($formField);
  }

  function getInsertValue($value)
  {
    $value = parent::getInsertValue($value);
    return $value[0];
  }

  function getValue($value)
  {
    return array(parent::getValue($value));
  }
}
?>
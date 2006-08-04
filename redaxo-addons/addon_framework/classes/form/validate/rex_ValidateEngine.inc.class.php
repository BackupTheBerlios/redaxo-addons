<?php


/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: rex_ValidateEngine.inc.class.php,v 1.1 2006/08/04 17:46:28 kills Exp $
 */

/**
 * ValidierungsEngine der rexForm Klasse
 * @see SmartyValidate
 */
class rexValidateEngine extends SmartyValidate
{
  // Statische Call auf Member Call mappen
  function register_criteria($name, $func_name, $form = SMARTY_VALIDATE_DEFAULT_FORM)
  {
    return parent::register_criteria($name, $func_name, $form);
  }
  
  function has_registered_validators($form = SMARTY_VALIDATE_DEFAULT_FORM)
  {
    $container = @$_SESSION['SmartyValidate'][$form]['validators'];
    return isset($container) && is_array($container) && count($container) > 0;
  }
  
  // Statischer Call auf Member Call mappen
  function register_object($obj_name,&$object)
  {
    return parent::register_object($obj_name,$object);
  }
}
?>
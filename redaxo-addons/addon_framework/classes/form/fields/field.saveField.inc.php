<?php


/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.saveField.inc.php,v 1.3 2007/09/08 09:40:52 kills Exp $
 */

class saveField extends buttonField
{
  var $saveButtonName;

  function saveField($attributes = array ())
  {
    global $I18N_ADDON_FRAMEWORK;

    $this->saveButtonName = 'rexform_save_button';
    $this->addButton($this->saveButtonName, $I18N_ADDON_FRAMEWORK->msg('save'));

    $this->buttonField($attributes);
  }

  function setSaveButtonStatus($status)
  {
    $this->setButtonStatus($this->saveButtonName, $status);
  }
}
?>
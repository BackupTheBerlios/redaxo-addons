<?php


/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.rexSaveField.inc.php,v 1.2 2007/09/02 14:00:28 kills Exp $
 */

class rexSaveField extends saveField
{
  var $updateButtonName;
  var $cancelButtonName;
  var $deleteButtonName;

  function rexSaveField($attributes = array ())
  {
    $this->updateButtonName = 'rexform_update_button';
    $this->cancelButtonName = 'rexform_cancel_button';
    $this->deleteButtonName = 'rexform_delete_button';

    $this->addButton($this->updateButtonName, '�bernehmen');
    $this->addButton($this->cancelButtonName, 'Abbrechen');
    $this->addButton($this->deleteButtonName, 'L�schen');

    $this->saveField($attributes);
    $this->needFullColumn(true);
  }

  function setUpdateButtonStatus($status)
  {
    $this->setButtonStatus($this->updateButtonName, $status);
  }

  function setCancelButtonStatus($status)
  {
    $this->setButtonStatus($this->cancelButtonName, $status);
  }

  function setDeleteButtonStatus($status)
  {
    $this->setButtonStatus($this->deleteButtonName, $status);
  }

  function get()
  {
    global $I18N;

    $section = & $this->getSection();
    $form = & $section->getForm();
    $s = '';

    // linksb�ndige Buttons
    $s .= '<div class="flLeft">';
    $s .= $this->formatButton($this->saveButtonName);

    if ($form->isEditMode())
      $s .= $this->formatButton($this->updateButtonName, '', '&nbsp;');

    $s .= '</div>';

    // rechtsb�ndige Buttons
    $s .= '<div class="flRight">';

    if ($form->isEditMode())
      $s .= $this->formatButton($this->deleteButtonName, ' onclick="return confirm(\''. $I18N->msg('delete') .' ?\');"', '&nbsp;');

    $s .= $this->formatButton($this->cancelButtonName, '', '&nbsp;');
    $s .= '</div>';

    return $s;
  }
}
?>
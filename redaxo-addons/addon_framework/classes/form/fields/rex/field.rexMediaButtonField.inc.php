<?php

/**
 * Addon Framework Classes by <a href='mailto:staab@public-4u.de'>Markus Staab</a>
 * <a href='http://www.public-4u.de'>www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.rexMediaButtonField.inc.php,v 1.2 2007/08/24 11:43:45 kills Exp $
 */

class rexMediaButtonField extends popupButtonField
{
  var $enablePreview;

  function rexMediaButtonField($name, $label, $attributes = array (), $id = '')
  {
    $this->popupButtonField($name, $label, $attributes, $id);
    $this->enablePreview();
  }

  function enablePreview()
  {
    $this->enablePreview = true;
  }

  function disablePreview()
  {
    $this->enablePreview = false;
  }

  function isPreviewEnabled()
  {
    return $this->enablePreview;
  }

  function get()
  {
    global $REX;

    $section =& $this->getSection();
    $form = $section->getForm();
    // Buttons erst hier einfügen, da vorher die ID noch nicht vorhanden ist
    $this->addButton( 'Medienpool öffnen', 'javascript:openMediaPool(\'&amp;opener_form='. $form->getName() .'&amp;opener_input_field='. $this->getId() .'\');');
    $this->addButton( 'Medium entfernen', 'javascript:setValue(\''. $this->getId() .'\',\'\');', 'file_del.gif');
    $this->addButton( 'Medium hinzufügen', 'javascript:openMediaPool(\'&amp;action=media_upload&amp;subpage=add_file&amp;opener_form='. $form->getName() .'&amp;opener_input_field='. $this->getId() .'\');', 'file_add.gif');

    $preview = '';
    if($this->isPreviewEnabled() && $this->getValue() != '')
      $preview = '<img src="'. $REX['MEDIAFOLDER'] .'/'. $this->getValue() .'" />';

    return $preview . parent::get();
  }
}
?>
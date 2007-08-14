<?php

/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.rexWYSIWYGField.inc.php,v 1.2 2007/08/14 16:39:26 kills Exp $
 */

class WYSIWYGField extends textAreaField
{
  var $editor;

  function WYSIWYGField($name, $label, $attributes = array (), $id = '')
  {
    $this->rexFormField($name, $label, $attributes, $id);
  }

  function setEditor($editor)
  {
    $this->editor = $editor;
  }

  function get()
  {
    if (empty ($this->editor))
    {
      $this->editor = new rexEditor();
      $this->editor->content = $this->getValue();
      $this->editor->width = '';
      $this->editor->height = '';
      $this->editor->stylesheet = '';
      $this->editor->styles = '';
      $this->editor->lang = '';
      $this->editor->buttonrow1 = 'bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,bullist,numlist,link,linkHack,unlink,insertEmail,separator,removeformat,pasteRichtext,code'; // ,outdent,indentstyleselect,separator,
      $this->editor->buttonrow2 = ' '; // ,separator,image
      $this->editor->buttonrow3 = ' '; // tablecontrols, separator, visualaid
      $this->editor->buttonrow4 = ' '; // rowseparator,formatselect,fontselect,fontsizeselect,forecolor,charmap
    }

    $this->editor->id = $this->getId();
    $this->editor->name = $this->getName();

    //    return sprintf('<textarea name="%s" id="%s"%s>%s</textarea>', $this->getName(), $this->getId(), $this->getAttributes(), $this->getValue());
    return $this->editor->get();
  }
}

class rexEditor extends tiny2editor
{
}
?>
<?php


/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.popupButtonField.inc.php,v 1.2 2007/09/02 14:00:28 kills Exp $
 */

class popupButtonField extends readOnlyTextField
{
  var $buttons;

  function popupButtonField($name, $label, $attributes = array (), $id = '')
  {
    $this->readOnlyTextField($name, $label, $attributes, $id);
    $this->buttons = array ();
  }

  /**
   * F�gt dem ButtonField einen Button hinzu
   * @param $title Titel des Buttons 
   * @param $href Link des Buttons 
   * @param $title Dateiname des Bilder f�r den Button 
   */
  function addButton($title, $href, $image = 'file_open.gif')
  {
    $this->buttons[] = array (
      'title' => $title,
      'href' => $href,
      'image' => $image
    );
  }

  function getInputFields()
  {
    return parent :: get();
  }

  function getInsertValue()
  {
    return $this->_getInsertValue();
  }

  function getButtons()
  {
    $s = '';

    $id = $this->getId();
    foreach ($this->buttons as $button)
    {
      foreach ($button as $attr_name => $attr_value)
      {
        $button[$attr_name] = str_replace('%id%', $id, $attr_value);
      }
      $s .= sprintf('      <a href="%s" tabindex="%s"><img src="pics/%s" width="16" height="16" alt="%s" title="%s" /></a>'."\n", $button['href'], rex_tabindex(false), $button['image'], $button['title'], $button['title']);
    }

    return $s;
  }

  function get()
  {
    $s = $this->getInputFields() . $this->getButtons();

    return $s;
  }
}
?>
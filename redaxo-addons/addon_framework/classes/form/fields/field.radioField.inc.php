<?php


/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.radioField.inc.php,v 1.2 2006/08/09 12:21:15 kills Exp $
 */

class radioField extends rexFormMultiValueField
{
  function radioField($name, $label, $attributes = array (), $id = '')
  {
    $this->rexFormMultiValueField($name, $label, $attributes, $id);
  }

  /**
   * F�gt eine Box hinzu
   * @param $label Label der Option
   * @param $value Wert der Option
   * @access public
   */
  function addRadio($label, $value = '')
  {
    $this->addValue($label, $value);
  }

  /**
   * F�gt ein Array von Boxen hinzu
   * @param $options Array von Optionen
   * @access public
   */
  function addRadios($boxes)
  {
    $this->addValues($boxes);
  }

  /**
   * F�gt Boxen via SQL-Query hinzu
   * @param $query SQL-Query, der ein 2 spaltiges Resultset beschreibt
   * @access public
   */
  function addSqlRadios($query)
  {
    $this->addSqlValues($query);
  }

  /**
   * Gibt alle Boxen als Array zur�ck
   * @access public
   */
  function getRadios()
  {
    return $this->getValues();
  }

  /**
   * Gibt den HTML Content zur�ck
   */
  function get()
  {
    $s = '';
    $name = $this->getName();
    $id = $this->getId();
    $value = $this->getValue();
    $attributes = $this->getAttributes();

    $i = 0;
    $s = '<div id="'.$id.'">'; 
    foreach ($this->getRadios() as $box)
    {
      $boxid = $id . $i;
      $checked = '';
      if (in_array($box[1], $value))
      {
        $checked = ' checked="checked"';
      }
      $s .= sprintf('<input type="radio" name="%s" value="%s" id="%s" tabindex="%s"%s%s /><label for="%s">%s</label>', $name, $box[1], $boxid, rex_a22_nextTabindex(), $checked, $attributes, $boxid, $box[0]);
      $i++;
    }
    $s .= '</div>';

    return $s;
  }
}
?>
<?php

/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.selectField.inc.php,v 1.6 2007/10/29 16:53:03 kills Exp $
 */

class selectField extends rexFormMultiValueField
{
  var $multiple;

  function selectField($name, $label, $attributes = array (), $id = '')
  {
    parent::rexFormMultiValueField($name, $label, $attributes, $id);
    $this->setMultiple(false);
  }

  /**
   * Fügt eine Option hinzu
   * @param $label Label der Option
   * @param $value Wert der Option
   * @access public
   */
  function addOption($label, $value = '', $id = 0, $re_id = 0)
  {
    $this->addValue($label, $value, $id, $re_id);
  }

  /**
   * Fügt ein Array von Optionen hinzu
   * @param $options Array von Optionen
   * @access public
   */
  function addOptions($options)
  {
    $this->addValues($options);
  }

  /**
   * Fügt Optionen via SQL-Query hinzu
   * @param $query SQL-Query, der ein 2 spaltiges Resultset beschreibt
   * @access public
   */
  function addSqlOptions($query)
  {
    $this->addSqlValues($query);
  }

  /**
   * Gibt alle Optionen als Array zurück
   * @access public
   */
  function getOptions()
  {
    return $this->getValues();
  }

  /**
   * Aktiviert/Deaktiviert, dass mehrere Optionen zugleich gewählt werden können
   * @param $multiple true => aktivieren / false => deaktivieren
   * @param $multiValueManager MultiValueManagerObjekt, dass den Speichervorgang handelt
   */
  function setMultiple($multiple = true, $multiValueManager = null)
  {
    if($multiple)
    {
      if(!$multiValueManager)
        $multiValueManager = new rex_internalMultiValueManager($this);

      $this->setValueManager($multiValueManager);
    }
    else
    {
      $this->setValueManager(new rex_multiValueManager($this));
    }

    $this->multiple = $multiple;
  }

  function outGroup($re_id, $level = 0)
  {

    if ($level > 100)
    {
      // nur mal so zu sicherheit .. man weiss nie ;)
      echo "Addon FW selectField overflow!";
      exit;
    }

    $ausgabe = '';
    $group = $this->getValueGroup($re_id);

    if(is_array($group))
    {
      foreach ($group as $option)
      {
        $name = $option[0];
        $value = $option[1];
        $id = $option[2];
        $ausgabe .= $this->outOption($name, $value, $level);

        $subgroup = $this->getValueGroup($id, true);
        if ($subgroup !== false)
        {
          $ausgabe .= $this->outGroup($id, $level +1);
        }
      }
    }
    return $ausgabe;
  }

  function outOption($name, $value, $level = 0)
  {
    $bsps = '';
    for ($i = 0; $i < $level; $i ++)
      $bsps .= '&nbsp;&nbsp;&nbsp;';

    $selected = '';
    if (in_array($value, $this->getValue()))
    {
      $selected = ' selected="selected"';
    }

    return '    <option value="'.$value.'"'.$selected.'>'.$bsps.$name.'</option>';
  }

  /**
   * Gibt den HTML Content zurück
   */
  function get()
  {
    $options = '';
    $name = $this->getName();
    $options = $this->outGroup($this->getFirstGroupId());

    if ($this->multiple)
    {
      $name .= '[]';
      $this->addAttribute('multiple', 'multiple');
      $this->addAttribute('size', '5', false);
    }
    else
    {
      $this->addAttribute('size', '3', false);
    }

    return sprintf('<select name="%s" id="%s" tabindex="%s"%s>%s</select>', $name, $this->getId(), rex_tabindex(false), $this->getAttributes(), $options);
  }
}
?>
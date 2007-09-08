<?php

/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.readOnlyField.inc.php,v 1.3 2007/09/08 10:24:45 kills Exp $
 */

class readOnlyField extends rexFormField
{
  var $format;
  var $format_type;

  function readOnlyField($name, $label, $attributes = array (), $id = '')
  {
    $this->rexFormField($name, $label, $attributes, $id);
    $this->format = '';
    $this->format_type = '';
    // default werden Werte nicht gespeichert
    $this->activateSave(false);
  }

  function setFormat($format)
  {
    $this->format = $format;
  }

  function getFormat()
  {
    return $this->format;
  }

  function setFormatType($format_type)
  {
    $this->format_type = $format_type;
  }

  function getFormatType()
  {
    return $this->format_type;
  }

  function getValue()
  {
    // Hier werden keine POST Values wieder angezeigt!

    // Werte vom User gesetzt?
    $userValue = $this->getUserValue(null);
    if($userValue !== null)
      return $userValue;

    return $this->getDataSetValue();
  }

  function formatValue()
  {
    $value = $this->getValue();
    $format_type = $this->getFormatType();
    if ($format_type != '')
    {
      $value = rexFormatter :: format($value, $format_type, $this->getFormat());
    }
    return $value;
  }

  function getAttributes()
  {
    $attributes = parent::getAttributes();

    // strip required/invalid css-class attribute, da der user die Werte
    // eines read-only fields sowieso nicht beeinflussen kann
    $attributes = str_replace('class="required"', '', $attributes);
    $attributes = str_replace('class="invalid"', '', $attributes);

    return $attributes;
  }

  function get()
  {
    $value = $this->formatValue();
    return sprintf('<span id="%s"%s>%s</span>', $this->getId(), $this->getAttributes(), $value);
  }
}
?>
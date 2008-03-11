<?php

/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 *
 * @package redaxo3
 * @version $Id: field.wysiwygDateField.inc.php,v 1.6 2008/03/11 16:18:02 kills Exp $
 */

class wysiwygDateField extends readOnlyTextField
{
	var $config;
  var $path;

  function wysiwygDateField($name, $label, $attributes = array (), $id = '')
  {
  	static $first = true;

  	if(empty($attributes['style']))
  	  $attributes['style'] = 'width: 165px;';

    $this->path = '../files/addon_framework/calendar/';
    $this->readOnlyTextField($name, $label, $attributes, $id);

  	if($first)
  	{
  		$first = false;
      echo $this->addHeaderFiles();
  	}
  }

  function addHeaderFiles()
  {
    $path = $this->path;

    return
     '<link rel="stylesheet" type="text/css" media="all" href="'. $path .'calendar-win2k-cold-1.css"/>
      <script language="javascript" type="text/javascript" src="'. $path .'calendar_stripped.js"></script>
      <script language="javascript" type="text/javascript" src="'. $path .'lang/calendar-de.js"></script>
      <script language="javascript" type="text/javascript" src="'. $path .'calendar-setup.js"></script>

      <script language="javascript" type="text/javascript">
        function rex_a22_timestamp_from_calendar(cal) {
            var date = cal.date;
            var time = String(date.getTime());
            var needle = new getObj(cal.params.hiddenField);
            var hiddenField = needle.obj;
            hiddenField.value = time.substring(0,time.length-3);
        }
      </script>';
  }

  function addConfig($name, $value, $string = false)
  {
  	if($string)
  	{
  		$value = '"'. $value .'"';
  	}
  	$this->config[$name] = $value;
  }

  function getConfig()
  {
  	return $this->config;
  }

  function get()
  {
  	global $I18N;

  	// getId() ist erst hier verfügbar
    $this->addConfig('inputField', 'label_'. $this->getId(), true);
    $this->addConfig('ifFormat', $I18N->msg('dateformat'), true);
    $this->addConfig('hiddenField', $this->getId(), true);
    $this->addConfig('button', 'trigger_'. $this->getId(), true);
    $this->addConfig('onUpdate', 'rex_a22_timestamp_from_calendar');

    $value = $this->getValue();
    $formattedValue = $value != '' ? strftime($I18N->msg('dateformat'), $value) : '';

  	// Textfield für die formatierte Anzeige
  	$s = parent::get();
  	$s = str_replace('id="', 'id="label_', $s);
  	$s = preg_replace('/name="[^"]*"/', '', $s);
  	$s = preg_replace('/value="[^"]*"/', 'value="'. $formattedValue .'"', $s);
  	$s .= "\n";

		// hidden field für das speichern des timestamps
  	$s .= '<input type="hidden" id="'. $this->getId() .'" name="'. $this->getName() .'" value="'. $value .'" />'. "\n";
  	$s .= '<button id="trigger_'. $this->getId() .'" style="background-image:url('. $this->path .'icons/calendar_edit.png); width: 16px; height: 20px; background-position: center; background-repeat: no-repeat;" title="Datum wählen"></button>';

		// Kalender setup
  	$s .= '<script type="text/javascript">'. "\n";
  	$s .= 'Calendar.setup({'. "\n";
  	foreach($this->getConfig() as $name => $value)
  	{
	  	$s .= sprintf('  %-20s: %s,'. "\n", $name, $value);
  	}
  	$s .= '});'. "\n";
  	$s .= '</script>'. "\n";

  	return $s;
  }
}
?>
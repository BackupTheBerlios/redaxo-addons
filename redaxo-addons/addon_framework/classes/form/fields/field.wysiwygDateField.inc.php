<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.wysiwygDateField.inc.php,v 1.1 2007/01/31 20:20:16 kills Exp $
 */

class wysiwygDateField extends readOnlyTextField
{
	var $config;
	var $showtime;
	
  function wysiwygDateField($name, $label, $attributes = array (), $id = '')
  {
  	static $first = true;
  	
  	if($first)
  	{
  		$first = false;
  		echo '<link rel="stylesheet" type="text/css" media="all" href="../files/calendar/calendar-win2k-cold-1.css"/>';
  		echo '<script language="javascript" type="text/javascript" src="../files/calendar/calendar.js"></script>';
  		echo '<script language="javascript" type="text/javascript" src="../files/calendar/lang/calendar-de.js"></script>';
  		echo '<script language="javascript" type="text/javascript" src="../files/calendar/calendar-setup.js"></script>';
  	}
  	
  	if(empty($attributes['style']))
  	  $attributes['style'] = 'width: 165px;';
  	
    $this->readOnlyTextField($name, $label, $attributes, $id);
    
  	global $I18N;

    $this->addConfig('ifFormat', $I18N->msg('dateformat'), true);
//    $this->addConfig('timeFormat', '24');
//    $this->addConfig('showsTime', 'false', true);
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
  	// getId() ist erst hier verfügbar
    $this->addConfig('inputField', $this->getId(), true);
    $this->addConfig('button', 'trigger_'. $this->getId(), true);
    
  	$s = parent::get();
  	
  	$s .= "\n";
  	$s .= '<button id="trigger_'. $this->getId() .'" style="background-image:url(../files/calendar/icons/calendar_edit.png); width: 16px; height: 20px; background-position: center; background-repeat: no-repeat;" title="Datum wählen"></button>';
  	
  	$s .= '<script type="text/javascript">'. "\n";
  	$s .= 'Calendar.setup({'. "\n";
  	foreach($this->getConfig() as $name => $value)
  	{
	  	$s .= $name .':'. $value . ",\n";
  	}
  	$s .= '});'. "\n";
  	$s .= '</script>'. "\n";
    
  	return $s;
  }
}
?>
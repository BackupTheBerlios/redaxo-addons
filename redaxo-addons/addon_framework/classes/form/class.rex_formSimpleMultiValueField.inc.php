<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rex_formSimpleMultiValueField.inc.php,v 1.2 2007/02/12 16:11:33 kills Exp $
 */

/*
 * MultivalueField, dass die Values in der Spalte mit einem Trenner ablegt
 */
class rexSimpleMultiValueField extends rexFormMultiValueField
{
  var $value_separator;
	
  function rexSimpleMultiValueField($name, $label, $tags = array (), $id = '', $value_separator = '||')
  {
  	$this->rexFormMultiValueField($name, $label, $tags, $id);
    $this->setValueSeparator($value_separator);
  }
  
  /**
   * Setzt den Trenner f�r die Werte
   * @access public
   */
  function setValueSeparator($value_separator)
  {
    rex_valid_type($value_separator, 'string', __FILE__, __LINE__);
    
    $this->value_separator = $value_separator;
  }  
  
  /*
   * Prepariert den InsertValue um das Array als String in die DB zu speichern 
   * @access protected
   */
  function getInsertValue()
  {
    $value = parent :: getInsertValue();
    if (is_array($value))
    {
    	$value = implode($this->value_separator, $value);
    	
    	// Mit ValueSeparator davor und danach, damit man leichter mit LIKE selektieren kann
    	if($value != '')
      	$value = $this->value_separator . $value . $this->value_separator;
    }
    return $value;
  }
  
  /*
   * Prepariert den Value um den String aus der DB als Array zur�ckzugeben 
   * @access protected
   */
  function getValue()
  {
    $value = parent :: getValue();
    if (!is_array($value))
    {
      $value = explode($this->value_separator, $value);
      $count = count($value);
      if($count > 0)
      {
	    	// Da ValueSeparator davor und danach, erstes und letztes Element rauswerfen
	    	if($value[0] == $this->value_separator)
	    	{
		      unset($value[0]);
		      unset($value[$count-1]);
	    	}
      }
    }
    return $value;
  }
}
?>
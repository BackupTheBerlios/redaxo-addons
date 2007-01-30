<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rex_formTableMultiValueField.inc.php,v 1.1 2007/01/30 19:21:23 kills Exp $
 */

class rexTableMultiValueField extends rexFormMultiValueField
{
	var $foreignTable;
	var $foreignField;
	
  function rexTableMultiValueField($name, $label, $foreignTable, $foreignField, $tags = array (), $id = '')
  {
  	$this->rexFormMultiValueField($name, $label, $tags, $id);
  	$this->foreignTable = $foreignTable;
  	$this->foreignField = $foreignField;
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
  		$section =& $this->getSection();
  		
  		// Alle vorhanden Werte l�schen
    	$sql = new sql();
    	$sql->debugsql = true;
  		$sql->setTable($this->foreignTable);
  		$sql->where($section->_getWhereString());
  		$sql->delete();
  		
  		// und anschlie�end alle neu eintragen  		
  		foreach($value as $val)
  		{
  			// Parameter aus der Ursprungstabelle mit abspeichern damit sp�ter dar�ber verkn�pft werden kann
	  		foreach($section->getWhere() as $whereColName => $whereColValue)
	  		{
	  			$sql->setValue($whereColName, $whereColValue);
	  		}
	  		// Den zu speichernden Wert
	  		$sql->setValue($this->foreignField, $val);
	  		$sql->insert();
  		}
    }
    
    // null zur�ckgeben, damit zu diesem Feld nichts im rexForm gespeichert wird
    return null;
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
    	$value = array();
  		$section =& $this->getSection();
  		
  		// Alle vorhanden Werte l�schen
    	$sql = new sql();
    	$sql->debugsql = true;
    	$sql->setQuery('SELECT `'. $this->foreignField .'` FROM `'. $this->foreignTable .'` WHERE '. $section->_getWhereString());
    	
    	for($i = 0; $i < $sql->getRows(); $i++)
    	{
    		$value[] = $sql->getValue($this->foreignField);
    	}
    }
    return $value;
  }
}
?>
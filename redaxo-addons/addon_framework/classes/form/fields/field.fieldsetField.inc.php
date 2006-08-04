<?php


/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.fieldsetField.inc.php,v 1.1 2006/08/04 17:46:28 kills Exp $
 */

class fieldsetField extends rexFormField
{
  var $legend;
  var $container;

  function fieldsetField()
  {
    $this->rexFormField('', '', array(), '');
    $this->container = new rexFieldContainer();
  }

  function setLegend($legend)
  {
    $this->legend = $legend;
  }

  function getLegend()
  {
    return $this->legend;
  }

  /**
   * F�gt dem Container ein Feld hinzu.
   * @param object rexFormField-Objekt des hinzugef�gt werden soll
   * @param object rexFormSection-Objekt, mit dem das Feld verkn�pft werden soll
   * @access protected
   * @see rexFieldContainer::addField() 
   */
  function addField(& $field)
  {
    $this->container->addField($field, $this->getSection());
  }

  /**
   * F�gt dem Container mehrere Felder hinzu
   * @param array Array von rexFormField-Objekten
   * @access public 
   * @see rexFieldContainer::addFields() 
   */
  function addFields(& $fields)
  {
    $this->container->addFields($fields, $this->getSection());
  }

  /**
   * Gibt alle Felder des Containers zur�ck
   * @return array Array von rexFormField-Objekten
   * @access public 
   * @see rexFieldContainer::getFields() 
   */
  function & getFields()
  {
    return $this->container->getFields();
  }

  /**
   * Gibt die Werte aller Felder des Containers zur�ck
   * @return array Die Werte der Felder als Array
   * @access protected 
   * @see rexFieldContainer::getFieldValues() 
   */
  function getFieldValues()
  {
    return $this->container->getFieldValues();
  }

  /**
   * Z�hlt wieviele Felder sich im Container befinden
   * @return integer Anzahl an Felder im Container
   * @access public 
   * @see rexFieldContainer::numFields() 
   */
  function numFields()
  {
    return $this->container->numFields();
  }

  /**
   * Durchsucht den Fieldcontainer nach einem Feld
   * @param string Name des Feldes, wonach gesucht werden soll
   * @return object|null Bei erfolgreicher Suche wird ein rexFormField-Objekt zur�ckgegeben, sonst null 
   * @access public 
   * @see rexFieldContainer::searchField() 
   */
  function & searchField($name)
  {
    return $this->container->searchField($name);
  }

  function get()
  {
    $s = '';
    $s .= '<fieldset>';

    $legend = $this->getLegend();
    if ($legend != '')
    {
      $s .= '<legend>'.$legend.'</legend>';
    }

    $fields = & $this->getFields();
    for ($i = 0; $i < $this->numFields(); $i++)
    {
      $s .= $fields[$i]->get();
    }

    $s .= '</fieldset>';
    return $s;
  }
}
?>
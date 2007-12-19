<?php

/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rex_listColumn.inc.php,v 1.4 2007/12/19 12:37:31 kills Exp $
 */


define('OPT_NONE', 0); // 0
define('OPT_SORT', 1); // 2^0
define('OPT_SEARCH', 2); // 2^1
define('OPT_FILTER', 4); // 2^2 NOT IN USE!
define('OPT_ALL', 7); // 2^3 - 1

/**
 * Basisklasse f�r Spalten innerhalb der Liste.
 */
class rexListColumn extends rexListComponent
{
  // Spalten�berschrift
  var $label;

  // Optionen zur Darstellung und zum Verhalten
  var $options;

  // conditionale Texte
  var $conditions;

  // Tags
  var $head_attributes;
  var $body_attributes;

  function rexListColumn($label = '', $options = OPT_ALL)
  {
    $this->conditions = array ();
    $this->setHeadAttributes('');
    $this->setBodyAttributes('');
    $this->setOptions($options);
    $this->setLabel($label);
    parent::rexListComponent();
  }

  /**
   * �berschreibt die vorhandenen Optionen mit der/den �bergebenen Option/Optionen
   * @param $option OPT_* Konstante
   */
  function setOptions($options)
  {
    $this->options = $options;
  }

  /**
   * Gibt alle Optionen zur�ck
   */
  function getOptions()
  {
    return $this->options;
  }

  /**
   * F�gt die �bergebene Option hinzu
   * @param $option OPT_* Konstante
   */
  function addOption($option)
  {
    if ($option == OPT_NONE)
    {
      $this->setOptions(OPT_NONE);
    }
    else
    {
      $this->setOptions($this->getOptions() | $option);
    }
  }

  /**
   * Entfernt die �bergebene Option
   * @param $option OPT_* Konstante
   */
  function delOption($option)
  {
    $this->setOptions($this->getOptions() ^ $option);
  }

  /**
   * Pr�ft ob die �bergebene Option gesetzt ist
   * @param $option OPT_* Konstante
   */
  function hasOption($option)
  {
    return ($this->getOptions() & $option) == $option;
  }

  /**
   * Setzt die Spalten�berschrift
   */
  function setLabel($label)
  {
    $this->label = $label;
  }

  /**
   * Gibt die Spalten�berschrift zur�ck
   */
  function getLabel()
  {
    return $this->label;
  }

  /**
   * F�gt der Spalte einen Wert hinzu, der von einer Spalte abh�ngig ist.
   *
   * @param $cond_column Name der Spalte die gepr�ft werden soll
   * @param $cond_value Wert, auf den gepr�ft werden soll
   * @param $text Text der ausgegeben werden soll
   * @param $params Link-Parameter die auf $text als Link gesetzt werden sollen
   * @param $attr Attribute, die in den Link direkt eingef�gt werden sollen
   */
  function addCondition($cond_column, $cond_value, $text, $params = array (), $attr = array())
  {
    $this->conditions[] = array ($cond_column, $cond_value, $text, $params, $attr);
  }

  /**
   * @access public
   * @static
   */
  function isValid($column)
  {
    return is_object($column) && is_a($column, 'rexlistcolumn');
  }

  /**
   * Durchsucht die Parameter nach %VarName%
   * und ersetzt diese durch die entsprechenden Werte
   */
  function parseParams($row)
  {
    return $this->parseArray($this->params, $row);
  }

  function parseString($string, $row)
  {
    if (empty ($row) || empty ($string))
    {
      return '';
    }

    foreach ($row as $_name => $_value)
    {
      $string = str_replace('%'.$_name.'%', $_value, $string);
    }
    return $string;
  }

  function parseArray($array, $row)
  {
    $result = array ();
    if (empty ($row) || empty ($array))
    {
      return $result;
    }

    foreach ($array as $_name => $_value)
    {
      // %VAR_NAME%
      // Wert beginnt und endet mit '%'
      if (substr($_value, 0, 1) == '%' && substr($_value, -1) == '%')
      {
        // Name der Variablen herausschneiden
        $var = substr($_value, 1, strlen($_value) - 2);

        // Name in der aktuellen Zeile suchen
        if (array_key_exists($var, $row))
        {
          // Und ersetzen
          $result[$_name] = $row[$var];
          continue;
        }
      }
      $result[$_name] = $_value;
    }

    return $result;
  }

  function setHeadAttributes($attributes)
  {
    if($attributes != '' && !startsWith($attributes, ' '))
    {
      $attributes = ' '. $attributes;
    }
    $this->head_attributes = $attributes;
  }

  function getHeadAttributes()
  {
    return $this->head_attributes;
  }

  function setBodyAttributes($attributes)
  {
    if($attributes != '' && !startsWith($attributes, ' '))
    {
      $attributes = ' '. $attributes;
    }
    $this->body_attributes = $attributes;
  }

  function getBodyAttributes()
  {
    return $this->body_attributes;
  }

  /**
   * Formatiert die Werte der aktuellen Spalte.
   * Dabei kann mit $row auf alle Werte in der aktuellen Zeile zugegriffen werden.
   */
  function format($row)
  {
    for ($i = 0; $i < count($this->conditions); $i ++)
    {
      $condition = & $this->conditions[$i];
      // $condition[0] Name der Spalte die gepr�ft werden soll
      // $condition[1] Wert, auf den gepr�ft werden soll
      // $condition[2] Text der ausgegeben werden soll
      // $condition[3] Link-Parameter die auf $text als Link gesetzt werden sollen
      // $condition[4] Link-Attribute die in das a-tag gesetzt werden sollen

      // condition pr�fen
      if (array_key_exists($condition[0], $row) && $row[$condition[0]] == $condition[1])
      {
        if (is_array($condition[3]))
        {
          $attr = isset($condition[4]) ? $condition[4] : array();
          // Text mit den Parametern $condition[3] verlinken
          return $this->link($condition[2], $this->parseArray($condition[3], $row), $attr);
        }
        else
        {
          // Plain-Text
          return $condition[2];
        }
      }
    }
    return '';
  }
}

// Column Klassen einbinden
$ColumnBasedir = dirname(__FILE__);

require_once $ColumnBasedir.'/columns/column.resultColumn.inc.php';
require_once $ColumnBasedir.'/columns/column.staticColumn.inc.php';
require_once $ColumnBasedir.'/columns/column.countColumn.inc.php';
?>
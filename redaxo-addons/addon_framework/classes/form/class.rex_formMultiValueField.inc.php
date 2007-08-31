<?php

/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rex_formMultiValueField.inc.php,v 1.7 2007/08/31 13:42:59 kills Exp $
 */

class rexFormMultiValueField extends rexFormField
{
  var $values;
  // Boolean: Umschalter zwischen normalem speichern und multivalue speichern
  var $doSimpleSave;

  function rexFormMultiValueField($name, $label, $tags = array (), $id = '')
  {
    $this->rexFormField($name, $label, $tags, $id);
    $this->values = array ();
    $this->doSimpleSave(false);
  }

  /**
   * Fügt dem Feld einen neuen Wert hinzu
   * @param $label Label des Wertes
   * @param $value Wert des Wertes
   * @param $id Id des Wertes
   * @param $re_id Id des übergeordneten Wertes
   * @access protected
   */
  function addValue($label, $value, $id = 0, $re_id = 0)
  {
    rex_valid_type($label, array('string', 'scalar'), __FILE__, __LINE__);
    rex_valid_type($value, array('string', 'scalar'), __FILE__, __LINE__);

    $this->values[$re_id][] = array (
      $label,
      $value,
      $id
    );
  }

  /**
   * doSimpleSave() getter/setter
   *
   * Simple save bedeutet, dass nur ein Wert gespeichert werden darf
   * (wie in einem normalen rexFormField)
   */
  function doSimpleSave($doSimpleSave = null)
  {
    if($doSimpleSave === true || $doSimpleSave === false)
      $this->doSimpleSave = $doSimpleSave;

    return $this->doSimpleSave;
  }

  /**
   * Fügt dem Feld eine Array von Werten hinzu
   * @param $values Array von Werten
   * @access protected
   */
  function addValues($values)
  {
    rex_valid_type($values, 'array', __FILE__, __LINE__);

    $value = array_shift($values);
    $mode = '';
    $group = false;
    if (isset ($value[0]) && isset ($value[1]))
    {
      $mode = 'Numeric';
      if(isset ($value[2]) && isset ($value[3]))
      {
        $group = true;
      }
    }
    elseif (isset ($value['label']) && isset ($value['value']))
    {
      $mode = 'Assoc';
      if(isset ($value['id']) && isset ($value['re_id']))
      {
        $group = true;
      }
    }
    elseif (is_scalar($value))
    {
      $mode = 'Scalar';
    }
    else
    {
      rexForm :: triggerError('Unexpected Array-Structure for Array $values. Expected Keys are "0" and "1" or "label" and "value"!');
    }

    if ($mode == 'Numeric')
    {
      // Add first Option
      if($group)
      {
        $this->addValue($value[0], $value[1], $value[2], $value[3]);
      }
      else
      {
        $this->addValue($value[0], $value[1]);
      }

      // Add remaing Options
      foreach ($values as $value)
      {
        if($group)
        {
          $this->addValue($value[0], $value[1], $value[2], $value[3]);
        }
        else
        {
          $this->addValue($value[0], $value[1]);
        }
      }
    }
    elseif ($mode == 'Assoc')
    {
      // Add first Option
      if($group)
      {
        $this->addValue($value['label'], $value['value'], $value['id'], $value['re_id']);
      }
      else
      {
        $this->addValue($value['label'], $value['value']);
      }


      // Add remaing Options
      foreach ($values as $value)
      {
        if($group)
        {
          $this->addValue($value['label'], $value['value'], $value['id'], $value['re_id']);
        }
        else
        {
          $this->addValue($value['label'], $value['value']);
        }
      }
    }
    // Value und Label mit gleichem Wert
    elseif ($mode == 'Scalar')
    {
      // Add first Option
      $this->addValue($value, $value);

      // Add remaing Options
      foreach ($values as $value)
      {
        $this->addValue($value, $value);
      }
    }
  }

  /**
   * Fügt dem Feld neue Werte via SQL-Query hinzu.
   * Dieser Query muss ein 2 Spaltiges Resultset beschreiben.
   *
   * @param $query SQL-Query
   * @access protected
   */
  function addSqlValues($query)
  {
    $sql = new rex_sql();
    //      $sql->debugsql = true;

    $result = $sql->getArray($query, MYSQL_NUM);

    if($sql->hasError())
      echo $sql->getError();

    if (is_array($result) && count($result) >= 1)
    {
      $value = array_shift($result);
      $count = count($value);

      if ($count >= 4)
      {
        // Add first Option
        $this->addValue($value[0], $value[1], $value[2], $value[3]);
        foreach ($result as $value)
        {
          // Add remaing Options
          $this->addValue($value[0], $value[1], $value[2], $value[3]);
        }
      }
      elseif ($count == 2)
      {
        // Add first Option
        $this->addValue($value[0], $value[1]);
        foreach ($result as $value)
        {
          // Add remaing Options
          $this->addValue($value[0], $value[1]);
        }
      }
      elseif ($count == 1)
      {
        // Add first Option
        $this->addValue($value[0], $value[0]);
        foreach ($result as $value)
        {
          // Add remaing Options
          $this->addValue($value[0], $value[0]);
        }
      }
    }
  }

  /**
   * Gibt alle Werte des Feldes zurück
   * @access protected
   */
  function getValues()
  {
    return $this->values;
  }

  function getFirstGroupId()
  {
    foreach($this->getValues() as $id => $group)
    {
      return $id;
    }
  }

  /**
   * Gibt alle Werte des Feldes einer Gruppe zurück
   * @access protected
   */
  function getValueGroup($group_id, $ignore_main_group = false)
  {
    if ($ignore_main_group && $group_id == $this->getFirstGroupId())
    {
      return false;
    }

    foreach ($this->getValues() as $id => $group)
    {
      if ($id == $group_id)
      {
        return $group;
      }
    }
    return false;
  }
}
?>
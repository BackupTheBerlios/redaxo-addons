<?php

/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rex_formSection.inc.php,v 1.6 2007/09/09 10:29:42 kills Exp $
 */

class rexFormSection extends rexFieldController
{
  // Section-Label
  var $label;
  // Spaltenanzahl
  var $columns;

  // Anker
  var $anchor;

  /**
   * Klassenkonstruktor
   */
  function rexFormSection($tableName, $label, $whereParams, $columns = 1)
  {
    rex_valid_type($label, 'string', __FILE__, __LINE__);

    $this->label = $label;
    $this->columns = $columns;
    $this->anchor = '';

    // Parentkonstruktor aufrufen
    parent::rexfieldController($tableName, $whereParams);
  }

  /**
   * Setzt einen Anker am beginn des Abschnitts
   * @param string Name des Ankers
   */
  function setAnchor($anchor)
  {
    $this->anchor = $anchor;
  }

  /**
   * Gibt den Namen des Ankers zur�ck
   * @return string Name des Ankers
   */
  function getAnchor()
  {
    return $this->anchor;
  }

  /**
   * Setzt die �berschrift des Formularabschnitts
   * @param string �berschrift des Abschnitts
   */
  function setLabel( $label)
  {
    $this->label = $label;
  }

  /**
   * Gibt die �berschrift des Formularabschnitts
   * @return string �berschrift des Abschnitts
   */
  function getLabel()
  {
    return $this->label;
  }

  function isValid($section)
  {
    return is_object($section) && is_a($section, 'rexformsection');
  }

  function numColumns()
  {
    return $this->columns;
  }

  function parseLabel($label)
  {
    $label = str_replace(' - ', '-', $label);
    $label = str_replace(' ', '-', $label);
    $label = str_replace('.', '-', $label);
    $label = str_replace('�', 'Ae', $label);
    $label = str_replace('�', 'Oe', $label);
    $label = str_replace('�', 'Ue', $label);
    $label = str_replace('�', 'ae', $label);
    $label = str_replace('�', 'oe', $label);
    $label = str_replace('�', 'ue', $label);
    $label = str_replace('�', 'ss', $label);
    $label = preg_replace("/[^a-zA-Z\-0-9]/", "", $label);
    return $label;
  }

  function get()
  {
    // Abschnittsanker
    $anchor = $this->getAnchor();
    if ($anchor != '')
      $anchor = ' id="'. $anchor .'"';

    $s = '';
    $s .= '    <!-- rexSection start -->'. "\n";
    $s .= '    <div class="a22-rexform-section"'. $anchor .'>'. "\n";

    $s .= '      <fieldset>'."\n";

    // Abschnitts�berschirft
    $label = $this->getLabel();
    if ( $label != '')
    {
      $s .= '        <legend>'. $label .'</legend>'. "\n";
    }

    // Hidden fields
    $fields = & $this->getFields();
    $numFields = $this->numFields();

    for ($t = 0; $t < $numFields; $t ++)
    {
      if (is_a($fields[$t], 'hiddenfield'))
      {
        $s .= '        '.$fields[$t]->get()."\n";
        unset ($fields[$t]);
      }
    }

    // Enstandene l�cken zwischen den Indizes l�schen
    $fields = array_resort_keys($fields);
    $numFields = count($fields);

    // Validierungsfehler
    $errors = $this->getErrors();
    $numErrors = $this->numErrors();

    if ($numErrors > 0)
    {
      // Validierungsfehler anzeigen
      $s .= '        <ul class="validatormessages">'."\n";
      for ($i = 0; $i < $numErrors; $i ++)
      {
        if (!empty ($errors[$i]))
        {
          $s .= '            <li>'.$errors[$i].'</li>'."\n";
        }
      }
      $s .= '        </ul>'."\n";
    }

    // Daten aufbereiten
    $numCols = $this->numColumns();
    $colData = array();
    $fullCols = array();
    $i = 1;
    for ($t = 0; $t < $numFields; $t ++)
    {
      $field = & $fields[$t];

      $field_label = $field->getLabel();
      $field_value = $field->get();

      if ($field_label != '')
      {
        $field_label = '<label for="'. $field->getId() .'">'. $field_label .'</label>';
      }

      $fieldStr = $field_label . $field_value;

      if($field->needFullColumn())
      {
        $fullCols[] = $fieldStr. "\n";
        continue;
      }

      $colData[$i][] = '<p>'. $fieldStr .'</p>'. "\n";

      $i++;
      if($i>$numCols)
      {
        $i = 1;
      }
    }

    foreach ($colData as $colIndex => $column)
    {
      $colStr = '';
      if(count($column) > 0)
      {
        $colStr .= '        <div class="a22-col'. $colIndex .'">'. "\n";
        foreach($column as $colValue)
        {
          $colStr .= '          ' .$colValue;
        }
        $colStr .= '        </div>'. "\n";
      }
      $s .= $colStr;
    }

    $s .= '        <div class="rex-clearer"> </div>'."\n";

    foreach($fullCols as $colValue)
    {
      $s .= '        '. $colValue;
    }

    $s .= '      </fieldset>'."\n";
    $s .= '    </div>'."\n";
    $s .= '    <!-- rexSection end -->'. "\n";

    return $s;
  }

  function show()
  {
    echo $this->get();
  }

  function toString()
  {
    return 'rexFormSection: name: "'.$this->getTableName().'", label: "'.$this->getLabel().'", felder: "'.$this->numFields().'"';
  }
}
?>
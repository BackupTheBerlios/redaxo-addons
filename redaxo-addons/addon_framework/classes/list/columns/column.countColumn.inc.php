<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: column.countColumn.inc.php,v 1.1 2006/08/04 17:46:28 kills Exp $
 */

/**
 * Klasse die eine Fortlaufende Nummer repr�sentiert
 */
class countColumn extends rexListColumn
{
  var $setSteps;
  var $counter;
  var $format;

  function countColumn($label, $start = '', $resetOnEachPage = '', $format = '', $options = OPT_NONE)
  {
    $this->setSteps = $resetOnEachPage == '' ? false : $resetOnEachPage;
    $this->counter = $start == '' ? 1 : $start;
    $this->format = $format == '' ? '<b>%s</b>' : $format;
    $this->rexListColumn($label, $options);
  }

  function format($row)
  {
    $format = parent :: format($row);
    if (strlen($format) != 0)
    {
      return $format;
    }

    if (!$this->setSteps)
    {
      $steps = $this->rexlist->getSteps();
      $this->counter += $steps['curr'];
      $this->setSteps = true;
    }
    return sprintf($this->format, $this->counter++);
  }
}
?>
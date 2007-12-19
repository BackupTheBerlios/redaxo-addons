<?php

/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: column.staticColumn.inc.php,v 1.2 2007/12/19 12:37:31 kills Exp $
 */

/**
 * Klasse f�r Statische-Spalten innerhalb der Liste.
 * Eine Statische Spalte hat immer den gleichen Text.
 */
class staticColumn extends rexListColumn
{
  // statischer Text
  var $text;

  // link Attribute
  var $link_attr;

  function staticColumn($text, $label, $params = array (), $options = OPT_NONE)
  {
    $this->text = $text;
    // Statische Spalten sind nicht durchsuch- u. sortierbar
    $this->rexListColumn($label, $options);
    $this->setParams($params);
    $this->setLinkAttributes('');
  }

  /**
   * setzt Linkattribute f�r die Spalte
   * @params $attributes String von Attributen
   */
  function setLinkAttributes($attributes)
  {
    $this->link_attr = $attributes;
  }

  function format($row)
  {
    $format = parent :: format($row);
    if (strlen($format) != 0)
    {
      return $format;
    }
    // Link mit den Parametern aus der rexList
    return $this->link($this->text, $this->parseParams($row), $this->link_attr);
  }
}
?>
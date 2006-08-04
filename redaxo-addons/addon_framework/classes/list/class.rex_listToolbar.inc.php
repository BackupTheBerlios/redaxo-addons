<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rex_listToolbar.inc.php,v 1.1 2006/08/04 17:46:28 kills Exp $
 */

/**
 * Basisklasse f�r Toolbars innerhalb der Liste
 */
class rexListToolbar extends rexListComponent
{
  function rexListToolbar()
  {
    // nichts tun
  }

  /**
   * Gibt die Tags zur�ck, die im TD angedruckt werden sollen, 
   * indem sich die Toolbar befindet. (z.b. f�r Hintergrundfarbe/Ausrichtung/etc)
   */
  function tags()
  {
    return '';
  }

  /**
   * @access public
   * @static
   */
  function isValid($column)
  {
    return is_object($column) && is_a($column, 'rexlisttoolbar');
  }

  /**
   * f�hr Funktionen der Toolbars aus, vorm anzeigen der Liste
   */
  function prepare()
  {
    // nichts tun
  }
  
  /**
   * Modifiziert den Qry der Liste
   */
  function prepareQuery(& $listsql)
  {
    // nichts tun
  }

  /**
   * Gibt die Eigentliche Toolbar zur�ck
   */
  function show()
  {
    return '';
  }
}

// Toolbar Klassen einbinden
$ToolbarBasedir = dirname(__FILE__);

require_once $ToolbarBasedir.'/toolbars/toolbar.browseBar.inc.php';
require_once $ToolbarBasedir.'/toolbars/toolbar.searchBar.inc.php';
require_once $ToolbarBasedir.'/toolbars/toolbar.statusBar.inc.php';
require_once $ToolbarBasedir.'/toolbars/toolbar.maxElementsBar.inc.php';
?>
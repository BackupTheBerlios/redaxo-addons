<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: toolbar.maxElementsBar.inc.php,v 1.1 2006/08/04 17:46:28 kills Exp $
 */

/**
 * Leiste die ein Textfeld zum �ndern der Datens�tze pro Seite anzeigt
 */
class maxElementsBar extends rexListToolbar
{
  var $stepping;
  
  function maxElementsBar()
  {
    $this->rexListToolbar();
    $this->stepping = empty ($_REQUEST['stepping']) ? '' : $_REQUEST['stepping'];
  }
  function prepare()
  {
    parent::prepare();
    if ( $this->stepping != '')
    {
      $this->rexlist->setStepping( $this->stepping);
    }
  }
  
  function show()
  {
    return '<label for="stepping">Anzahl:</label>
          <input type="text" id="stepping" name="stepping" value="'.$this->rexlist->getStepping().'" style="width: 22px" maxlength="2" title="Eintr�ge pro Seite"/>
          <input type="submit" value="Anzeigen" title="Einstellung �bernehmen"/>'."\n";
  }
}
?>
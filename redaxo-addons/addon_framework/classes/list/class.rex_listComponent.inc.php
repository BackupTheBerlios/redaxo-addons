<?php
/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rex_listComponent.inc.php,v 1.2 2007/09/06 17:13:11 kills Exp $
 */

/**
 * Basisklasse f�r alle rexList Komponenten
 */
class rexListComponent
{
  // Referenz zur dazugeh�rigen Liste
  var $rexlist;

  // Parameter f�r Links
  var $params;

  function rexListComponent($rexlist = null)
  {
    $this->params = array ();
    $this->rexlist = $rexlist;
  }

  function link($value, $params = array (), $tags = '')
  {
    if (count($params) == 0)
    {
      $params = $this->params;
    }
    return rex_listLink($value, $this->getGlobalParams(), $params, $tags);
  }

  function addGlobalParam($name, $value)
  {
    $this->rexlist->addGlobalParam($name, $value);
  }

  function addGlobalParams($params)
  {
    $this->rexlist->addGlobalParams($params);
  }

  function getGlobalParams()
  {
    return $this->rexlist->getGlobalParams();
  }

  function setParams($params)
  {
    if (is_array($params))
    {
      $this->params = array_merge($this->params, $params);
    }
    else
    {
      $this->params = $params;
    }
  }
}
?>
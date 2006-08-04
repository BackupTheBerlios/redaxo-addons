<?php


/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rex_list.inc.php,v 1.1 2006/08/04 17:46:28 kills Exp $
 */

// List Komponenten einbinden
$ListBasedir = dirname(__FILE__);

// Classes
require_once $ListBasedir.'/class.rex_listComponent.inc.php';
require_once $ListBasedir.'/class.rex_listColumn.inc.php';
require_once $ListBasedir.'/class.rex_listToolbar.inc.php';

require_once $ListBasedir.'/../class.rex_formatter.inc.php';
require_once $ListBasedir.'/../class.oosql.inc.php';

// Functions
require_once $ListBasedir.'/../../functions/function_rex_list.inc.php';

// CSS einbinden
rex_register_extension('OUTPUT_FILTER', 'rex_a22_insertRexlistCss');

/**
 * Platzhalter: Vor der Liste
 * @see rexList::setVar()
 */
define('LIST_VAR_TOP', 1);
/**
 * Platzhalter: Vor den Kopfleisten 
 * @see rexList::setVar()
*/
define('LIST_VAR_BEFORE_HEAD', 2);
/**
 * Platzhalter: Nach den Kopfleisten 
 * @see rexList::setVar()
 */
define('LIST_VAR_AFTER_HEAD', 3);
/**
 * Platzhalter: Vor dem Kopfbereich der Tabelle 
 * @see rexList::setVar()
 */
define('LIST_VAR_BEFORE_DATAHEAD', 4);
/**
 * Platzhalter: Nach dem Kopfbereich der Tabelle 
 * @see rexList::setVar()
 */
define('LIST_VAR_AFTER_DATAHEAD', 5);
/**
 * Platzhalter: Vor dem Datenbereich der Tabelle 
 * @see rexList::setVar()
 */
define('LIST_VAR_BEFORE_DATA', 6);
/**
 * Platzhalter: Nach dem Datenbereich der Tabelle 
 * @see rexList::setVar()
 */
define('LIST_VAR_AFTER_DATA', 7);
/**
 * Platzhalter: Vor den Fu�leisten 
 * @see rexList::setVar()
 */
define('LIST_VAR_BEFORE_FOOT', 8);
/**
 * Platzhalter: Nach den Fu�leisten 
 * @see rexList::setVar()
 */
define('LIST_VAR_AFTER_FOOT', 9);
/**
 * Platzhalter: Nach der Liste
 * @see rexList::setVar()
 */
define('LIST_VAR_BOTTOM', 10);
/**
 * Platzhalter: Erscheint, wenn die Liste keine Datens�tze enth�lt 
 * @see rexList::setVar()
 */
define('LIST_VAR_NO_DATA', 11);

/**
 * Basisklasse zur Darstellung von Datenbanktabellen (Listen).
 * 
 * Features:
 * - Spalte sortiertbar/suchbar schalten
 * - einzelne Leisten auslagern in ToolBars
 * - ein/ausblenden von ToolBars
 * - Elemente pro Seite von User einstellbar
 */
class rexList
{
  /**
   * Schritte der Aktuellen Seite
   * @var array
   * @access private
   */
  var $steps;

  /**
   * Anzahl Datens�tze pro Seite
   * @var integer
   * @access private
   */
  var $stepping;

  /**
   * Listenname
   * @var string
   * @access private
   */
  var $name;

  /**
   * Listen�berschrift
   * @var string
   * @access private
   */
  var $label;

  /**
   * Spaltenbreiten
   * @var array
   * @access private
   */
  var $colgroup;

  /**
   * Globale Parameter f�r Links
   * @var array
   * @access private
   */
  var $params;
  
  /**
   * Bezeichnung der Liste
   * @var string
   */
  var $caption;
  
  /**
   * Attribute der Liste
   * @var string
   */
  var $attributes;

  /**
   * Array das alle Spaltenobjekte beinhaltet
   * @var array
   * @access private
   */
  var $columns;

  /**
   * Array der Kopf und Fu�leisten
   * @var array 
   * @access private
   */
  var $toolbars;

  /**
   * Standard-Anzahl Datens�tze pro Seite
   * @var string
   * @access private
   */
  var $def_stepping;

  /**
   * Standardsortierungs Spaltenname
   * @var string
   * @access private
   */
  var $def_order_col;

  /**
   * Standardsortierungs Type
   * @var string asc/desc
   * @access private
   */
  var $def_order_type;

  /**
   * Name der Standardsuch-Spalte
   * @var string
   * @access private
   */
  var $def_search_col;

  /**
   * Datenbankverbindung
   * @var object
   * @access private
   */
  var $sql;

  /**
   * Query Builder (OOSQL)
   * @var object
   * @access protected
   */
  var $listsql;
  /**
   * Grundlegende SQL Abfrage
   * @var string
   * @access private
   */
  var $qry;

  /**
   * Datens�tze im Resultset
   * @var array
   * @access private
   */
  var $rows;

  /**
   * Anzahl Datens�tze im Dataset
   * @var integer
   * @access private
   */
  var $num_rows;

  /**
   * Datens�tze im Resultset f�r die aktuelle Seite
   * @var array
   * @access private
   */
  var $curr_rows;

  /**
   * Anzahl Datens�tze f�r die aktuelle Seite
   * @var integer
   * @access private
   */
  var $num_current_rows;

  /**
   * statische Layouttexte
   * @var array
   * @access private
   */
  var $layoutVars;

  /**
   * Debugflag
   * @var bool
   * @access public
   */
  var $debug;

  function rexList($qry, $default_order_col = '', $default_order_type = '', $default_search_col = '', $default_stepping = '')
  {
    global $order_col, $order_type;

    $this->steps = array ();
    $this->stepping = $default_stepping;

    $this->label = '';
    $this->caption = '';
    $this->name = 'default';
    $this->colgroup = array ();
    $this->params = array ();
    $this->attributes = '';
    $this->columns = array ();
    $this->toolbars = array ();

    $this->toolbars['top']['full'] = array ();
    $this->toolbars['top']['half'] = array ();
    $this->toolbars['bottom']['full'] = array ();
    $this->toolbars['bottom']['half'] = array ();

    $this->def_stepping = $default_stepping;
    $this->def_order_col = $default_order_col;
    $this->def_order_type = $default_order_type;
    $this->def_search_col = $default_search_col;

    $this->layoutVars = array ();

    $this->sql = new sql();
    $this->listsql = new OOSql($qry);
    $this->qry = $qry;
    $this->rows = '';
    $this->num_rows = '';
    $this->curr_rows = '';
    $this->num_current_rows = '';

    $this->debug = & $this->sql->debugsql;

    $this->addGlobalParams(rex_a22_getDefaultGlobalParams());

    // Nur die Parameter anh�ngen, die vom default Wert abweichen      
    if ($order_col != $default_order_col)
    {
      $this->addGlobalParam('order_col', $order_col);
    }

    if ($order_type != $default_order_type)
    {
      $this->addGlobalParam('order_type', $order_type);
    }
  }

  /**
   * F�gt einen globalen Parameter hinzu.
   * Dieser wird bei allen Links innerhalb der Liste angef�gt.
   *
   * @param Name des Parameters
   * @param Wert des Parameters 
   * @access public
   */
  function addGlobalParam($name, $value)
  {
    $this->params[$name] = $value;
  }

  /**
   * F�gt der Liste ein Array von Parametern hinzu.
   * 
   * @param array Array von Parametern
   * @access public
   */
  function addGlobalParams($params)
  {
    foreach ($params as $_name => $_value)
    {
      $this->addGlobalParam($_name, $_value);
    }
  }

  /**
   * Gibt die Globalen Parameter zur�ck.
   * 
   * @access protected
   * @return array Die Globalen Parameter
   */
  function getGlobalParams()
  {
    return $this->params;
  }

  /**
   * Setzt die Anzahl Zeilen, die pro Seite angezeigt werden
   * @param integer Zeilenanzahl
   * @access protected
   */
  function setStepping($stepping)
  {
    if ($stepping != $this->def_stepping)
    {
      $this->stepping = $stepping;
      $this->addGlobalParam('stepping', $stepping);
    }
  }

  /**
   * Setzt die Standard-Anzahl Zeilen, die pro Seite angezeigt werden
   * 
   * @param integer Zeilenanzahl
   * @access protected
   */
  function setDefaultStepping($stepping)
  {
    $this->def_stepping = $stepping;
  }

  /**
   * Setzt den Fu� der Liste.
   * Damit kann zwischen den unteren Toolbars und der Liste eigener Inhalt eingef�gt werden
   * 
   * @access public
   * @deprecated version - 27.02.2006
   */
  function setFooter($footer)
  {
    $this->setVar(LIST_VAR_BOTTOM, $footer);
  }

  /**
   * Setzt den Kopf der Liste.
   * Damit kann zwischen den obereb Toolbars und der Liste eigener Inhalt eingef�gt werden
   * 
   * @access public
   * @deprecated version - 27.02.2006
   */
  function setHeader($header)
  {
    $this->setVar(LIST_VAR_TOP, $header);
  }
  
  function setAttributes($attributes)
  {
    if($attributes != '' && !startsWith($attributes, ' '))
    {
      $attributes = ' '. $attributes;
    }
    $this->attributes = $attributes;
  }
  
  function getAttributes()
  {
    return $this->attributes;
  }
  /**
   * Setzt die Bezeichnung der Liste
   * 
   * @param string die �berschrift
   * @access public
   */
  function setCaption($caption)
  {
    $this->caption = $caption;
  }
  
  /**
   * Gibt die Bezeichnung der Liste zur�ck
   * 
   * @return string die �berschrift
   * @access protected
   */
  function getCaption()
  {
    return $this->caption;
  }

  /**
   * Setzt die �berschrift der Liste
   * 
   * @param string die �berschrift
   * @access public
   */
  function setLabel($label)
  {
    $this->label = $label;
  }

  /**
   * Gibt die �berschrift der Liste zur�ck
   * 
   * @return string die �berschrift
   * @access protected
   */
  function getLabel()
  {
    return $this->label;
  }

  /**
   * Setzt den Namen der Liste.
   * 
   * @param string Name der Liste
   * @access public
   */
  function setName($name)
  {
    $this->name = $name;
  }

  /**
   * Gibt den Namen der Liste zur�ck.
   *  
   * @param string Name der Liste
   * @access public
   */
  function getName()
  {
    return $this->name;
  }

  /**
   * F�gt der Liste eine Spalte hinzu.
   * 
   * @param object Spaltenobjekt das hinzugef�gt werden soll
   * @access public
   */
  function addColumn(& $column)
  {
    if (!rexListColumn :: isValid($column))
    {
      trigger_error('rexList: Unexpected type "'.gettype($column).'" for $column! Expecting "rexlistcolumn"-object.', E_USER_ERROR);
    }

    $column->rexlist = & $this;
    $this->columns[] = & $column;
  }

  /**
   * F�gt der Liste ein Array von Spalten hinzu.
   * 
   * @param array Array von Spaltenobjekten die hinzugef�gt werden sollen
   * @access public
   */
  function addColumns(& $columns)
  {
    if (!is_array($columns))
    {
      trigger_error('rexList: Unexpected type "'.gettype($columns).'" for $columns! Expecting an Array!.', E_USER_ERROR);
    }
    foreach ($columns as $column)
    {
      $this->addColumn($column);
    }
  }

  /**
   * F�gt der Liste eine Toolbar hinzu.
   * Diese muss ein Objekt vom Typ "rexlisttoolbar" sein!
   * 
   * @param $direction Stelle an der die Toolbar angef�gt werden soll (top|bottom).
   * @param $mode Breite in der die Toolbar angezeigt werden soll (full|half).
   * @access public
   */
  function addToolbar(& $toolbar, $direction = 'top', $mode = 'full')
  {
    if (!in_array($direction, array (
        'top',
        'bottom'
      )))
    {
      trigger_error('rexList: Unexpected direction "'.$direction.'"!', E_USER_ERROR);
    }
    if (!in_array($mode, array (
        'full',
        'half'
      )))
    {
      trigger_error('rexList: Unexpected mode "'.$mode.'"!', E_USER_ERROR);
    }
    if (!rexListToolbar :: isValid($toolbar))
    {
      trigger_error('rexList: Unexpected type "'.gettype($toolbar).'" for $column! Expecting "rexlisttoolbar"-object.', E_USER_ERROR);
    }

    $toolbar->rexlist = & $this;
    $this->toolbars[$direction][$mode][] = & $toolbar;
  }

  /**
   * Gibt ein Array von Toolbars zur�ck, die der Liste bereits hinzugef�gt wurden.
   * 
   * @return array Bereits hinzugef�gte Toolbars
   * @access public
   */
  function getToolbars()
  {
    return array_merge($this->toolbars['top']['full'], $this->toolbars['top']['half'], $this->toolbars['bottom']['full'], $this->toolbars['bottom']['half']);
  }

  /**
   * F�gt der Liste die Standard-Toolbars hinzu
   * @access protected
   */
  function addDefaultToolbars()
  {
    $this->addToolbar(new browseBar(), 'top', 'half');
    $this->addToolbar(new searchBar(), 'top', 'half');
    $this->addToolbar(new statusBar(), 'bottom', 'half');
    $this->addToolbar(new maxElementsBar(), 'bottom', 'half');
  }

  /**
   * Gibt einen Link zur�ck mit den Parametern $params und den Attributen $tags
   * 
   * @param string Text der verlinkt werden soll
   * @param array Parameter die an die URL des Links angef�gt werden sollen
   * @param array Tags, die dem Link Element hinzugef�gt werden sollen
   * 
   * @return string Gibt einen formatierten HTML-Hyperlink zur�ck
   * @access protected
   */
  function link($value, $params = array (), $tags = '')
  {
    return rex_listLink($value, $this->params, $params, $tags);
  }

  /**
   * Gibt die aktuellen Schritte der Liste zur�ck.
   * 
   *     $this->steps['prev']; // vorheriger Schritt
   *     $this->steps['curr']; // aktueller Schritt
   *     $this->steps['next']; // n�chster Schritt
   * 
   * @return array Assoc-Array mit den Schritten
   * @access protected
   */
  function getSteps()
  {
    global $REX;

    if (empty ($this->steps))
    {

      /*
      $this->steps['prev'];
      $this->steps['curr'];
      $this->steps['next'];
      */

      /*
      // SQL_CALC_FOUND_ROWS & FOUND_ROWS() ist ab MySQL 4.0 m�glich
      if (substr($REX['MYSQL_VERSION'], 0, 1) == '4')
      {
         $qry = preg_replace('/SELECT/i', 'SELECT SQL_CALC_FOUND_ROWS', $qry);
      }
      */

      // Calc next
      $curr = @ $_REQUEST['next'];
      $found = $this->numRows();
      $stepping = $this->getStepping();
      if (empty ($curr) || $curr == 'first')
      {
        $curr = 0;
      }
      elseif ($curr == 'last')
      {
        // SQL_CACHE ist ab MySQL 4.0 m�glich
        if (substr($REX['MYSQL_VERSION'], 0, 1) == '4')
        {
          $count_qry = preg_replace('/SELECT/i', 'SELECT SQL_CACHE', $this->qry);
        }
        else
        {
          $count_qry = $this->qry;
        }

        $this->sql->setQuery($count_qry);
        $rows = $this->sql->getRows();
        if ($rows - $stepping < 0)
        {
          $curr = 0;
        }
        else
        {
          $curr = $rows - $stepping +1;
        }
      }
      else
      {
        $curr = (int) $curr;
      }

      $stepping = $stepping;

      $this->steps = array ();
      $prev = $curr - $stepping >= 0 ? $curr - $stepping : 0;

      $next = 0;
      // Ist der n�chste Wert gr��re als Datens�tze vorhanden
      if ($curr + $stepping >= $found)
      {
        // Nur wenn mehr Datens�tze vorhanden sind als f�r einen Schritt ben�tigt werden 
        if ($found - $stepping +1 > 0)
        {
          $next = $found - $stepping +1;
        }
      }
      else
      {
        $next = $curr + $stepping;
      }

      $this->steps['prev'] = $prev;
      $this->steps['curr'] = $curr;
      $this->steps['next'] = $next;

    }
    return $this->steps;
  }

  /**
   * Gibt zur�ck, wieviele Datens�tze pro Seite angezeigt werden
   * 
   * @return integer Anzahl der Datens�tze pro Seite
   * @access protected
   */
  function getStepping()
  {
    if ($this->stepping == '')
    {
      if ($this->def_stepping == '')
      {
        return 10;
      }
      return $this->def_stepping;
    }
    return $this->stepping;
  }

  /**
   * Gibt alle Datens�tze zur�ck, die vom Urspr�nglichen Query betroffen sind
   * 
   * @return array Array von Datens�tzen
   * @access protected
   */
  function getRows()
  {
    if ($this->rows == '')
    {
      $this->sql->setQuery($this->qry);
      $this->rows = $this->sql->get_array();
      //      var_dump( $this->rows);
    }
    return $this->rows;
  }

  /**
   * Gibt die Anzahl der Datens�tze zur�ck, 
   * die vom urspr�nglichen Query betroffen sind.
   * 
   * @return integer Die Anzahl der Datens�tze
   * @access protected
   */
  function numRows()
  {
    if ($this->num_rows == '')
    {
      $this->num_rows = count($this->getRows());
      /*
      // SQL_CALC_FOUND_ROWS & FOUND_ROWS() ist ab MySQL 4.0 m�glich
      if (substr($REX['MYSQL_VERSION'], 0, 1) == '4')
      {
         $this->sql->setQuery('SELECT FOUND_ROWS() AS FOUND');
         $res = $this->sql->get_array();
         $this->num_rows = $res[0]['FOUND'];
      }
      else
      {
         $this->sql->setQuery($this->qry);
         $this->num_rows = $this->getRows();
      }
      */
    }
    return $this->num_rows;
  }

  /**
   * Gibt die Datens�tze der aktuellen Seite zur�ck.
   *
   * @return array Array von Datens�tze der aktuellen Seite 
   * @access protected  
   */
  function getCurrentRows()
  {
    if ($this->curr_rows == '')
    {
      $listsql = & $this->listsql;
      $toolbars = $this->getToolbars();
      for ($i = 0; $i < count($toolbars); $i++)
      {
        $toolbar = & $toolbars[$i];
        $toolbar->prepareQuery($listsql);
      }

      $this->prepareQuery($listsql);
      //$this->sql->debugsql = true;
      $this->sql->setQuery($listsql->getQry());
      $this->curr_rows = $this->sql->get_array();
    }

    return $this->curr_rows;
  }

  /**
   * Gibt die Anzahl der Datens�tze, auf der aktuellen Seite zur�ck.
   * 
   * @return integer Anzahl der Datens�tze   
   * @access protected
   */
  function numCurrentRows()
  {
    if ($this->num_current_rows == '')
    {
      $this->num_current_rows = count($this->getCurrentRows());
    }
    return $this->num_current_rows;
  }

  /**
   * Gibt die Anzahl der Spalten zur�ck
   * 
   * @return integer Anzahl der Spalten
   * @access protected  
   */
  function numColumns()
  {
    return count($this->columns);
  }

  /**
   * Bereitet den Query f�r die Anfrage vor.
   * Hier werden letzte Modifikationen des SQLs vorgenommen.
   * 
   * @param object OOSQL Object der Liste
   * @access protected  
   */
  function prepareQuery(& $listsql)
  {
    global $REX;

    $order_col = empty ($_REQUEST['order_col']) ? $this->def_order_col : $_REQUEST['order_col'];
    $order_type = empty ($_REQUEST['order_type']) ? $this->def_order_type : $_REQUEST['order_type'];

    if ($order_col != '')
    {
      $listsql->addOrderBy($order_col, $order_type);
    }
  }

  /**
   * Dieser Methode l�st das Event f�r die Toolbars aus, 
   * letzte Modifikationen an der rexList vorzunehmen,
   * bevor die Verarbeitung der Ausgabe beginnt.
   * 
   * @access protected  
   */
  function prepareToolbars()
  {
    $toolbars = $this->getToolbars();

    for ($i = 0; $i < count($toolbars); $i++)
    {
      $toolbars[$i]->prepare();
    }
  }

  /**
   * Funktion zum setzen von statischen Texten
   * 
   * @param integer Name des Platzhalters(Konstante)
   * @param string Statisches HTML, welches an Stelle des Platzhalters erscheint
   * @access public
   */
  function setVar($name, $value)
  {
    $this->layoutVars[$name] = $value;
  }

  /**
   * Gibt den Wert eines statischen Textes zur�ck
   * 
   * @param integer Name des Platzhalters(Konstante)
   * @access protected
   */
  function getVar($name, $default = '')
  {
    if (!empty($this->layoutVars[$name]))
    {
      return $this->layoutVars[$name];
    }
    return $default;
  }

  /**
   * Gibt die gerenderte Liste zur�ck
   * 
   * @param bool [Flag, ob die Standardtoolbars hinzugef�gt werden sollen - 
   *             true => ja/ false => nein; default:true]
   * @access public
   */
  function get($addDefaultToolbars = true)
  {
    global $REX;

    $s = '';

    // Default Toolbars hinzuf�gen
    if ($addDefaultToolbars)
    {
      $this->addDefaultToolbars();
    }

    $this->prepareToolbars();

    // Ben�tigte Variablen definieren      
    $rows = $this->getCurrentRows();

    $s .= '<!-- rexList start -->'."\n";
    $s .= '<div class="a22-rexlist">'."\n";
    $s .= $this->getVar(LIST_VAR_TOP); // Platzhalter
    $s .= '  <form action="index.php" method="post">'."\n";
    $s .= '    <fieldset>'."\n";

    $label = $this->getLabel();
    if ($label != '')
    {
      $s .= '      <legend>'.$label.'</legend>'."\n";
    }

    // Alle Parameter f�r einen Post als hidden �bergeben
    foreach ($this->params as $_name => $_value)
    {
      if ($_name != '' && $_value != '')
      {
        $s .= '      <input type="hidden" name="'.$_name.'" value="'.$_value.'" />'."\n";
      }
    }

    // ------------ Kopfleisten

    $s .= $this->getVar(LIST_VAR_BEFORE_HEAD); // Platzhalter
    $s .= '      <div class="a22-rexlist-toolbars-top">'."\n";
    $s .= $this->_getToolbars($this->toolbars['top']['full']);
    $s .= $this->_getHalfToolbars($this->toolbars['top']['half']);
    $s .= '      </div>'."\n";
    $s .= $this->getVar(LIST_VAR_AFTER_HEAD); // Platzhalter

    // ------------ Datenbereich

    $s .= $this->getVar(LIST_VAR_BEFORE_DATAHEAD); // Platzhalter
    $s .= '      <div class="a22-rexlist-data">'."\n";

    if ($this->numCurrentRows() != 0)
    {
      // ------------ Tabellenkopf

      $s .= '        <table'. $this->getAttributes() .'>'."\n";
      $s .= $this->_getColGroup();

      $caption = $this->getCaption();
      if ($caption != '')
      {
        $s .= '        <caption>'.$caption.'</caption>'."\n";
      }
      
      $s .= '          <thead>'."\n";
      $s .= '            <tr>'."\n";
      for ($i = 0; $i < $this->numColumns(); $i++)
      {
        $column = & $this->columns[$i];
        $s .= sprintf('              <th%s>%s</th>'."\n", $column->getHeadAttributes(), $column->getLabel());
      }
      $s .= '            </tr>'."\n";
      $s .= '          </thead>'."\n";
      $s .= $this->getVar(LIST_VAR_AFTER_DATAHEAD); // Platzhalter

      // ------------ Tabellendaten

      $s .= $this->getVar(LIST_VAR_BEFORE_DATA); // Platzhalter

      if (is_array($rows))
      {
        $extension_point = 'REX_LIST_'.strtoupper($this->getName()).'_ROW_ATTR';
        $extension_is_registered = rex_extension_is_registered($extension_point);
        $s .= '          <tbody>'."\n";
        for ($t = 0; $t < count($rows); $t++)
        {
          $row = & $rows[$t];

          $rowAttributes = '';
          if ($extension_is_registered)
          {
            $rowAttributes = rex_register_extension_point($extension_point, '', array (
              'row' => $row,
              'line_number' => $t
            ));
          }

          $s .= '            <tr'.$rowAttributes.'>'."\n";
          for ($i = 0; $i < count($this->columns); $i++)
          {
            $column = & $this->columns[$i];
            $s .= '              <td'.$column->getBodyAttributes().'>'.$column->format($row).'</td>'."\n";
          }
          $s .= '            </tr>'."\n";
        }
      }
      $s .= '          </tbody>'."\n";
      $s .= $this->getVar(LIST_VAR_AFTER_DATA); // Platzhalter
      $s .= '        </table>'."\n";
    }
    else
    {
      // keine Daten vorhanden
      $def_message = 'Ihr Abfrage ergab keine Ergebnisse'; 
      $s .= '        <p class="a22-rexlist-norows">'. $this->getVar(LIST_VAR_NO_DATA, $def_message) .'</p>'."\n"; // Platzhalter
    }
    
    $s .= '      </div>'."\n";

    // ------------ Fu�leisten

    $s .= $this->getVar(LIST_VAR_BEFORE_FOOT); // Platzhalter
    $s .= '      <div class="a22-rexlist-toolbars-btm">'."\n";
    $s .= $this->_getHalfToolbars($this->toolbars['bottom']['half']);
    $s .= $this->_getToolbars($this->toolbars['bottom']['full']);
    $s .= '      </div>'."\n";
    $s .= $this->getVar(LIST_VAR_AFTER_FOOT); // Platzhalter

    $s .= '    </fieldset>'."\n";
    $s .= '  </form>'."\n";
    $s .= $this->getVar(LIST_VAR_BOTTOM); // Platzhalter
    $s .= '</div>'."\n";
    $s .= '<!-- rexList end -->'."\n";

    return $s;
  }

  /**
   * Gibt die gerenderte Liste aus
   * 
   * @param bool [Flag, ob die Standardtoolbars hinzugef�gt werden sollen - 
   *             true => ja/ false => nein; default:true]
   * @access public
   */
  function show($addDefaultToolbars = true)
  {
    echo $this->get($addDefaultToolbars);
  }

  /**
   * Gibt die �bergebenen Toolbars auf voller Breite aus
   * 
   * @access private
   */
  function _getToolbars($toolbars)
  {
    $s = '';

    $numToolbars = count($toolbars);
    for ($i = 0; $i < $numToolbars; $i++)
    {
      $toolbar = & $toolbars[$i];

      $s .= '      <p class="">'."\n";
      $s .= '        '.$toolbar->show();
      $s .= '      </p>'."\n";
    }

    return $s;
  }

  /**
   * Gibt die �bergebenen Toolbars auf halber Breite aus
   * @access private
   */
  function _getHalfToolbars(& $toolbars)
  {
    $s = '';

    $numToolbars = count($toolbars);
    for ($i = 0; $i < $numToolbars; $i++)
    {
      $toolbar = & $toolbars[$i];
      // Abwecheselnd rechts-/linksb�ndig ausrichten
      $class = $i % 2 == 1 ? ' class="flRight"' : ' class="flLeft"';

      $s .= '        <p'.$class.$toolbar->tags().'>'."\n";
      $s .= '          '.$toolbar->show();
      $s .= '        </p>'."\n";
    }

    return $s;
  }

  /**
   * Setzt die Spaltenbreiten der Tabelle
   *
   * @param array Spaltenbreiten, wobei jeder index des Arrays einer Spalte entspricht
   * @access public 
   */
  function setColGroup($colgroup)
  {
    rex_valid_type($colgroup, 'array', __FILE__, __LINE__);
    $this->colgroup = $colgroup;
  }

  /**
   * Gibt die Spaltenbreiten zur�ck
   * 
   * @access protected
   */
  function getColGroup()
  {
    return $this->colgroup;
  }

  /**
   * Gibt die in HTML formatierte Colgroup zur�ck
   * 
   * @access protected
   */
  function _getColGroup()
  {
    $s = '';

    $colgroup = $this->getColgroup();
    if (empty ($colgroup))
    {
      return $s;
    }

    $s .= '          <colgroup>'."\n";
    foreach ($colgroup as $column)
    {
      $s .= '            <col width="'.$column.'" />'."\n";
    }
    $s .= '          </colgroup>'."\n";

    return $s;
  }
}
?>
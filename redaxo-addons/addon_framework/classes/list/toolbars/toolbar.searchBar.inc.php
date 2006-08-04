<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: toolbar.searchBar.inc.php,v 1.1 2006/08/04 17:46:28 kills Exp $
 */

/**
 * Suchleiste
 */
class searchBar extends rexListToolbar
{
  var $column;
  var $key;
  var $mode;
  
  var $add_columns;

  function searchBar()
  {
    if (empty ($_REQUEST['rex_search_cancel']))
    {
      // POST Vars auswerten
      $this->column = empty ($_REQUEST['rex_search_column']) ? '' : $_REQUEST['rex_search_column'];
      $this->key = empty ($_REQUEST['rex_search_key']) ? '' : $_REQUEST['rex_search_key'];
      $this->mode = empty ($_REQUEST['rex_search_mode']) ? '' : 'exact';
    }
    else
    {
      // Vars resetten
      $this->column = '';
      $this->key = '';
      $this->mode = '';
    }
    // Additional columns
    $this->add_columns = array();
  }

  function show()
  {
    $search_column = $this->column;
    $search_key = $this->key;
    $search_mode_checked = $this->mode == 'exact' ? ' checked="checked"' : '';

    if (!empty ($search_key))
    {
      $this->addGlobalParams(array ('rex_search_key' => $search_key, 'rex_search_column' => $search_column));
    }

    $s = '';
    $s .= '<label for="search_key">Suche</label>'."\n";
    $s .= '          <input type="text" value="'.$search_key.'" id="search_key" title="Suchwort" name="rex_search_key" />'."\n";
    $s .= '          <label for="search_column">in</label>'."\n";
    $s .= '          <select id="search_column" name="rex_search_column" title="Suchspalte">'."\n";

    // Suchspalten anzeigen
    for ($i = 0; $i < $this->rexlist->numColumns(); $i ++)
    {
      $column = & $this->rexlist->columns[$i];

      if ($column->hasOption(OPT_SEARCH))
      {
        $selected = '';
        if ($search_column != '' && $search_column == $column->name || $search_column == '' && $this->rexlist->def_search_col == $column->name)
        {
          $selected = ' selected="selected"';
        }
        $s .= sprintf('            <option value="%s"%s>%s</option>'."\n", $column->name, $selected, $column->label);
      }
    }

    // Manuell eingefügte Suchspalten anzeigen
    $columns = $this->getColumns();
    for ($i = 0; $i < $this->numColumns(); $i ++)
    {
      $column = & $columns[$i];
      $column_name = $column[0];
      $column_label = $column[1];

      $selected = '';
      if ($search_column != '' && $search_column == $column_name || $search_column == '' && $this->rexlist->def_search_col == $column_name)
      {
        $selected = ' selected="selected"';
      }
      $s .= sprintf('            <option value="%s"%s>%s</option>'."\n", $column_name, $selected, $column_label);
    }
    
    $s .= '          </select>'."\n";
    $s .= '          <input type="checkbox" value="exact" title="Exakter Suchmodus" name="rex_search_mode"'.$search_mode_checked.' />'."\n";
    $s .= '          <input type="submit" value="Suchen" title="Suche starten" name="rex_search_button" />'."\n";

    if ($search_key != '')
    {
      $s .= '       <input type="submit" value="Suche aufheben" name="rex_search_cancel" />'."\n";
    }

    return $s;
  }
  
  function getColumns()
  {
    return $this->add_columns;
  }
  
  function numColumns()
  {
    return count($this->add_columns);
  }
  
  function addColumn($name, $label)
  {
    $this->add_columns[] = array($name, $label);
  }

  function prepareQuery(& $listsql)
  {
    $search_column = $this->column;
    $search_key = $this->key;
    $search_mode = $this->mode;

    if ($search_column != '' && $search_key != '')
    {
      if ($search_mode == 'exact')
      {
        $listsql->addWhere($search_column.' = "'.$search_key.'"');
      }
      else
      {
        $listsql->addWhere($search_column.' LIKE "%'.$search_key.'%"');
      }
    }
  }
}
?>
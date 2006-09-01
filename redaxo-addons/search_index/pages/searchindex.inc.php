<?php

/**
 * 
 * @package redaxo3
 * @version $Id: searchindex.inc.php,v 1.2 2006/09/01 08:47:28 kills Exp $
 */
 
rex_register_extension('OUTPUT_FILTER_CACHE', 'rex_search_gen_index');
function rex_search_gen_index()
{
  $search = new rex_search_index();
  $search->rex_indexSite();
}

echo '<table class=rex style=table-layout:auto; cellpadding=5 cellspacing=1>';
echo '<tr><th>&nbsp;&nbsp;Suchindex wurde erneuert!</th></tr>';
echo '</table>';
rex_register_extension_point('SEARCH_GENERATED');

?>
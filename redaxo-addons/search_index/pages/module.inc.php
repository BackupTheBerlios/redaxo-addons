<?php

/**
 *
 * @package redaxo3
 * @version $Id: module.inc.php,v 1.3 2007/08/02 10:57:13 kills Exp $
 */

print '
    <table class=rex style=table-layout:auto; cellpadding=5 cellspacing=1>
    <tr><th>Anleitung für Such<u>ausgabe</u> Modul</th></tr>
    <tr>
      <td colspan=2>
        <div style="border: solid 1px red; padding: 20px; margin: 20px; font-size:11px">';

$module = dirname(__FILE__).'/../modules/module.searchmodule.inc.php';
highlight_file($module);

print '</div>';
print '&nbsp;&nbsp; addon by <a href="http://www.vscope.at">vscope new media</a> version 0.1 beta - updated by jan@kristinus.de, markus@public4u.de';
print '</td></tr></table>';

?>
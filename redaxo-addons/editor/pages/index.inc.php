<?php
/** 
 * Addon: Editor
 * @package redaxo3 
 * @version $Id: index.inc.php,v 1.1 2006/07/21 13:53:10 kills Exp $
 */

$mypage = "editor";

if (!isset ($subpage)) $subpage = '';
switch ($subpage){
  
  case "help":
      include $REX['INCLUDE_PATH']."/layout/top.php";
    rex_title($I18N_EDITOR->msg("editor"), "&nbsp;&nbsp;&nbsp;<a href='index.php?page=$page' target='_self'>".$I18N_EDITOR->msg("css")."</a> | <a href='index.php?page=$page&subpage=setting' target='_self'>".$I18N_EDITOR->msg("setting")."</a> | ".$I18N_EDITOR->msg("help"));
    
    echo "<table class=rex style=table-layout:auto; cellpadding=5 cellspacing=1>";
    echo "<tr><th>".$I18N->msg("addon_help")." $mypage</th></tr>";
    echo "<tr><td>";
    include $REX['INCLUDE_PATH']."/addons/$page/help.inc.php";
    echo "&nbsp;</td></tr>";
    echo "</table>";
    
    include $REX['INCLUDE_PATH']."/layout/bottom.php";
  break;
  
  case "setting":
      include $REX['INCLUDE_PATH']."/layout/top.php";
    rex_title($I18N_EDITOR->msg("editor"), "&nbsp;&nbsp;&nbsp;<a href='index.php?page=$page' target='_self'>".$I18N_EDITOR->msg("css")."</a> | ".$I18N_EDITOR->msg("setting")." | <a href='index.php?page=$page&subpage=help' target='_self'>".$I18N_EDITOR->msg("help")."</a>");
    include $REX['INCLUDE_PATH']."/addons/$page/pages/setting.inc.php";
    include $REX['INCLUDE_PATH']."/layout/bottom.php";
  break;
  
  default:
      include $REX['INCLUDE_PATH']."/layout/top.php";
    rex_title($I18N_EDITOR->msg("editor"), "&nbsp;&nbsp;&nbsp;".$I18N_EDITOR->msg("css")." | <a href='index.php?page=$page&subpage=setting' target='_self'>".$I18N_EDITOR->msg("setting")."</a> | <a href='index.php?page=$page&subpage=help' target='_self'>".$I18N_EDITOR->msg("help")."</a>" );
    include $REX['INCLUDE_PATH']."/addons/$page/pages/page.inc.php";
    include $REX['INCLUDE_PATH']."/layout/bottom.php";

}
/*
<a href='index.php?page=$page&subpage=txt' target='_self'>".$I18N_EDITOR->msg("txt")."</a>
*/

?>
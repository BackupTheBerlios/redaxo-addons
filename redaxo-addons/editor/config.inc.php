<?php
/** 
 * Addon: Editor
 * @package redaxo3 
 * @version $Id: config.inc.php,v 1.1 2006/07/21 13:53:09 kills Exp $
 */

//error_reporting( E_ALL^E_NOTICE);

// only for this file
$mypage = 'editor';


// only backend
if (!$REX['GG'])
{
  $I18N_EDITOR = new i18n($REX['LANG'],$REX['INCLUDE_PATH']."/addons/$mypage/lang/");   // CREATE LANG OBJ FOR THIS ADDON
  
  $REX['ADDON']['page'][$mypage] = $mypage;
  $REX['ADDON']['name'][$mypage] = $I18N_EDITOR->msg("editor");
  $REX['ADDON']['perm'][$mypage] = 'editor[]';

  $REX['PERM'][] = 'editor[]';

}

?>
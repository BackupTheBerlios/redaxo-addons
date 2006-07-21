<?php
/** 
 * Addon: Editor
 * @package redaxo3 
 * @version $Id: config.inc.php,v 1.1 2006/07/21 13:53:09 kills Exp $
 */

// only for this file
$mypage = 'quixexplorer';

// only backend
if (!$REX['GG'])
{
  $REX['ADDON']['page'][$mypage] = $mypage;
  $REX['ADDON']['perm'][$mypage] = 'quixexplorer[]';
  $REX['ADDON']['name'][$mypage] = "QuixExplorer";
  $REX['PERM'][] = 'quixexplorer[]';
}

?>
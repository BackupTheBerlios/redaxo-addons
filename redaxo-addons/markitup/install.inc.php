<?php

/**
 * markitup Addon
 *
 * @author markus[dot]staab[at]redaxo[dot]de Markus Staab

 *
 * @package redaxo4
 * @version $Id: install.inc.php,v 1.2 2008/03/12 14:54:12 kills Exp $
 */

require_once $REX['INCLUDE_PATH'] . '/addons/markitup/functions/function_pclzip.inc.php';

$error = '';

// check folder write permissions
$tmpDir = $REX['MEDIAFOLDER'].'/'. $REX['TEMP_PREFIX'];
if(!is_dir($tmpDir) && !mkdir($tmpDir))
  $error = 'Could not create temp-dir "'. $tmpDir .'"!';

if($error == '' && !is_writable($tmpDir))
  $error = 'temp-dir "'. $tmpDir .'" not writable!';

$markitupDir = $tmpDir.'/markitup';
if($error == '' && !is_dir($markitupDir) && !mkdir($markitupDir))
  $error = 'Could not create markitup-dir "'. $markitupDir .'"!';

if($error == '' && !is_writable($markitupDir))
  $error = 'markitup-dir "'. $markitupDir .'" not writable!';

// Copy files
if($error == '')
{
  rex_a287_extract_archive('include/addons/markitup/js/markitup.zip', 'Install Markitup');
}

if($error != '')
  $REX['ADDON']['installmsg']['markitup'] = $error;
else
  $REX['ADDON']['install']['markitup'] = true;

?>
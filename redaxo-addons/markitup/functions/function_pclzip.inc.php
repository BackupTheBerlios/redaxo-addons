<?php

/**
 * markitup Addon
 *
 * @author markus[dot]staab[at]redaxo[dot]de Markus Staab

 *
 * @package redaxo4
 * @version $Id: function_pclzip.inc.php,v 1.3 2008/03/12 14:54:12 kills Exp $
 */

include_once $REX['INCLUDE_PATH'] . '/addons/markitup/classes/class.pclzip.inc.php';

function rex_a287_extract_archive($file, $msg = '', $path=null )
{
  global $REX;
  if(!$path) $path = '../files/'. $REX['TEMP_PREFIX'];

	$archive = new PclZip($file);
	if ($archive->extract(PCLZIP_OPT_PATH, $path) == 0)
	{
	  die("Error : " . $archive->errorInfo(true));
	}

	if (($list = $archive->listContent()) == 0)
	{
	  die("Error : " . $archive->errorInfo(true));
	}
}

?>
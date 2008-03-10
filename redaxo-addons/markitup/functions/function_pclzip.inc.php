<?php

/**
 * markitup Addon
 *
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 *
 * @package redaxo4
 * @version $Id: function_pclzip.inc.php,v 1.2 2008/03/10 12:11:15 kills Exp $
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
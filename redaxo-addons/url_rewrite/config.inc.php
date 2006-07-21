<?php

/**
 * URL-Rewrite Addon
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: config.inc.php,v 1.1 2006/07/21 13:53:09 kills Exp $
 */
 
if ($REX['MOD_REWRITE'] !== false)
{
  $UrlRewriteBasedir = dirname(__FILE__);
  require_once $UrlRewriteBasedir.'/classes/class.urlrewriter.inc.php';
  
    
  // --------- configuration
  
  // Modify this line to include the right rewriter
  require_once $UrlRewriteBasedir.'/classes/class.rewrite_simple.inc.php';
  
  // --------- end of configuration


  $oRewriter = new myUrlRewriter();
  $oRewriter->prepare();

  rex_register_extension('URL_REWRITE', array ($oRewriter, 'rewrite'));
}
?>
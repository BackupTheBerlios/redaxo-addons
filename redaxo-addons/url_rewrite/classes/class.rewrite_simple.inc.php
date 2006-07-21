<?php

/**
 * URL-Rewrite Addon
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rewrite_simple.inc.php,v 1.1 2006/07/21 13:53:09 kills Exp $
 */

/**
 * URL Simple Rewrite Anleitung:
 * 
 *   .htaccess file in das root verzeichnis:
 *     RewriteEngine Off
 */
class myUrlRewriter extends rexUrlRewriter
{
  // Konstruktor
  function myUrlRewriter()
  {
    // Parent Konstruktor aufrufen
    $this->rexUrlRewriter();
  }

  // Parameter aus der URL für das Script verarbeiten
  function prepare()
  {
    global $article_id, $clang, $REX;

    if (ereg('^/([0-9]*)-([0-9]*)', $_SERVER['QUERY_STRING'], $_match = array ()))
    {
      $article_id = $_match[1];
      $clang = $_match[2];
    }
    elseif ((empty( $_GET['article_id'])) && ( empty( $_POST['article_id'])))
    {
      $article_id = $REX['STARTARTIKEL_ID'];
    }
  }

  // Url neu schreiben
  function rewrite($params)
  {
    return './?/'.$params['id'].'-'.$params['clang'].'-'.$params['name'].'.htm'.$params['params'];
  }
}
?>
<?php


/**
 * URL-Rewrite Addon
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rewrite_fullnames.inc.php,v 1.2 2006/09/14 14:33:59 kills Exp $
 */

/**
 * URL Fullnames Rewrite Anleitung:
 *
 *   1) .htaccess file in das root verzeichnis:
 *     RewriteEngine On
 *     RewriteCond %{REQUEST_URI}  !redaxo.* 
 *     RewriteCond %{REQUEST_URI}  !files.* 
 *     RewriteCond %{REQUEST_URI}  !js.* 
 *     RewriteRule ^(.*)$ index.php?%{QUERY_STRING} [L]
 *
 *   2) .htaccess file in das redaxo/ verzeichnis:
 *     RewriteEngine Off
 * 
 *   3) im Template folgende Zeile AM ANFANG des <head> ergänzen:
 *   <base href="htttp://www.meine_domain.de/pfad/zum/frontend" />
 * 
 *   4) Specials->Regenerate All starten
 *
 * @author office[at]vscope[dot]at Wolfgang Huttegger
 * @author <a href="http://www.vscope.at/">vscope new media</a>
 * 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 */

if ($REX['REDAXO'])
{
  // Die Pathnames bei folgenden Extension Points aktualisieren
  rex_register_extension('CAT_ADDED', 'rex_rewriter_generate_pathnames');
  rex_register_extension('CAT_UPDATED', 'rex_rewriter_generate_pathnames');
  rex_register_extension('CAT_DELETED', 'rex_rewriter_generate_pathnames');

  rex_register_extension('ART_ADDED', 'rex_rewriter_generate_pathnames');
  rex_register_extension('ART_UPDATED', 'rex_rewriter_generate_pathnames');
  rex_register_extension('ART_DELETED', 'rex_rewriter_generate_pathnames');

  rex_register_extension('CLANG_ADDED', 'rex_rewriter_generate_pathnames');
  rex_register_extension('CLANG_UPDATED', 'rex_rewriter_generate_pathnames');
  rex_register_extension('CLANG_DELETED', 'rex_rewriter_generate_pathnames');

  rex_register_extension('ALL_GENERATED', 'rex_rewriter_generate_pathnames');
}

class myUrlRewriter extends rexUrlRewriter
{
  var $use_levenshtein;
  
  // Konstruktor
  function myUrlRewriter($use_levenshtein = true)
  {
    $this->use_levenshtein = $use_levenshtein;
    // Parent Konstruktor aufrufen
    $this->rexUrlRewriter();
  }

  // Parameter aus der URL für das Script verarbeiten
  function prepare()
  {

    global $article_id, $clang, $REX, $REXPATH;

    if (!$REX['REDAXO'])
    {

      $pathlist = $REX['INCLUDE_PATH']."/generated/files/pathlist.php";
      include_once ($pathlist);

      $script_path = dirname($_SERVER['PHP_SELF']);
      $length = strlen($script_path);
      $path = substr($_SERVER['REQUEST_URI'], $length);

      if ($path == '')
      {
        $article_id = $REX['START_ARTICLE_ID'];
        return true;
      }

      foreach ($REXPATH as $key => $var)
      {
        foreach ($var as $k => $v)
        {
          if ($path == $v)
          {
            $article_id = $key;
            $clang = $k;
          }
        }
      }

      // Check Clang StartArtikel
      if (!$article_id)
      {
        include ($REX['INCLUDE_PATH']."/clang.inc.php");
        if (is_array($REX['CLANG']))
        {
          foreach ($REX['CLANG'] as $key => $var)
          {
            if ($var."/" == $path)
            {
              $clang = $key;
            }
          }
        }
      }

      // Check levenshtein
      if ($this->use_levenshtein && !$article_id)
      {
        foreach ($REXPATH as $key => $var)
        {
          foreach ($var as $k => $v)
          {
            $levenshtein[levenshtein($path, $v)] = $key."#".$k;
          }
        }
        ksort($levenshtein);
        $best = explode("#", array_shift($levenshtein));
        $article_id = $best[0];
        $clang = $best[1];
      }

      if (!$article_id)
      {
        $article_id = $REX['NOTFOUND_ARTICLE_ID'];
        return true;
      }
    }
  }

  // Url neu schreiben
  function rewrite($params)
  {
    global $REX, $REXPATH;
    if (!$REXPATH)
    {
      include_once ($REX['INCLUDE_PATH']."/generated/files/pathlist.php");

    }
    $id = $params['id'];
    $name = $params['name'];
    $clang = $params['clang'];
    $params = $params['params'];
    $params = $params == '' ? '' : "?".$params;
    $url = $REXPATH[$id][$clang].$params;
    return $url;
  }
}

function rex_rewriter_generate_pathnames($params = array ())
{
  global $REX;

  $db = new sql();
  $result = $db->get_array("SELECT id,name,clang,path FROM rex_article");
  if (is_array($result))
  {
    foreach ($result as $var)
    {
      $article_names[$var['id']][$var['clang']]['name'] = rex_parseArticleName($var['name']);
    }
  }

  $fcontent = "<?php\n";
  if (is_array($result))
  {
    foreach ($result as $var)
    {
      $clang = $var['clang'];
      if (count($REX['CLANG']) > 1)
      {
        $pathname = $REX['CLANG'][$clang]."/";
      }
      else
      {
        $pathname = '';
      }
      $path = explode("|", $var['path']);
      $path[] = $var['id'];
      foreach ($path as $p)
      {
        if ($p != "")
        {
          $curname = $article_names[$p][$clang]['name'];
          if ($curname != '')
          {
            $pathname .= $curname."/";
          }
        }
      }
      $fcontent .= '$REXPATH[\''.$var['id'].'\'][\''.$var['clang'].'\'] = "'.mysql_escape_string($pathname).'";'."\n";
    }
  }
  $fcontent .= "?>";

  $handle = fopen($REX['INCLUDE_PATH']."/generated/files/pathlist.php", "w");
  fwrite($handle, $fcontent);
  fclose($handle);
}
?>
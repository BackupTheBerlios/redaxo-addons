<?php


/** 
 * Klasse zur Generation eines Suchindexes
 * @package redaxo3
 * @version $Id: class.search_index.inc.php,v 1.1 2006/07/21 13:53:10 kills Exp $ 
 */

class rex_search_index
{
  var $clang = -1;
  var $path = '';
  var $custom_where_conditions = '';
  var $status = '';
  var $searchIds = false;
  var $surroundchars = 20;
  var $sourround_start_tag = "<b>";
  var $sourround_end_tag = "</b>";
  var $striptags = true;
  var $debugsql = false;

  function rex_indexSite()
  {
    global $REX;

    $SQL = "SELECT id,path,clang,status,online_from,online_to,keywords,name FROM ". $REX['TABLE_PREFIX'] ."article ";

    $WHERE = "";
    
    // ----- diese artikel filtern
    /*
    $WHERE = " where status=1";
    
    $artikelidfilter = array(1,2,3,4,5,6);
    foreach($artikelidfilter as $val)
    {
    	$WHERE .= " and id<>'".$val."'";
    }
    
    $articletypefilter = array(1,2,3);
    foreach($articletypefilter as $val)
    {
      $WHERE .= " and type_id<>'".$val."'";
    }
    */
    
    if($WHERE != '')
    {
      $WHERE = 'WHERE '. $WHERE;
    }

    $LIMIT = "";
    $db2 = new sql;
    $db2->debugsql =& $this->debugsql;
    $stop = false;
    $oldstart = (int) $_REQUEST["oldstart"];
    $start = (int) $_REQUEST["start"];
    if ($oldstart == $start && $_REQUEST["start"] != "")
    {
      $stop = true;
    }

    if ($_REQUEST["start"])
    {
      $LIMIT = "LIMIT $start, 4000";
      $oldstart = $start;
    }
    else
    {
      $db2->query('TRUNCATE TABLE '. $REX['TABLE_PREFIX'].$REX['TEMP_PREFIX'].'12_search_index');
    }

    if ($stop)
    {

      return "Bei der Indexgenerieung ist ein Fehler unterlaufen. Das kann an eventuell fehlerhaften Artikeln liegen. 
      		Bei folgendem Artikel kam ein Fehler. <a href=index.php?page=content&article_id=".$_REQUEST["errorid"]."&mode=edit&clang=".$_REQUEST["errorclang"].">-> Artikel</a>";

    }
    else
    {

      $i = $start;
      $articles = $db2->get_array("$SQL $WHERE $LIMIT");
      $CONTENT = ob_get_contents();
      ob_end_clean();

      foreach ($articles as $var)
      {
        ob_end_clean();
        ob_start();
        echo "<html><head><title>REX SEARCH</title></head><body bgcolor=#fffff3>
        			Scriptlaufzeit war zu kurz, der Prozess wird sofort 
        			weitergeführt. Sollten Sie dennoch abbrechen wollen dann <a href=index.php?page=search_index>hier</a>.
        			<br><br>
        			Sollte das Script sich nicht erneut aufrufen, dann <a href=index.php?page=search_index&subpage=gen_index&start=$i&oldstart=$oldstart&errorid=".$var['id']."&errorclang=".$var['clang'].">hier</a> klicken um den Prozess weiterzuführen.
        			
        			<br><br><a href=index.php?page=content&article_id=".$var['id']."&mode=edit&clang=".$var['clang'].">Bei diesem Artikel wurde abgebrochen</a>
        			
        			<br><br><br><br>";

        $REX['GG'] = true;
        $REX_ARTICLE = new article;
        $REX_ARTICLE->setCLang($var['clang']);
        $REX_ARTICLE->setArticleId($var['id']);
        $artcache = $REX_ARTICLE->getArticle();
        $db2 = new sql; // falls im artikel eine andere datnebank aufgerufen wurde
        $db2->debugsql =& $this->debugsql;
        $artcache = rex_register_extension_point('OUTPUT_FILTER', $artcache);
        $artcache = rex_register_extension_point('SEARCH_ARTICLE_GENERATED', $artcache);

        if ($this->striptags)
          $artcache = preg_replace('@<[\/\!]*?[^<>]*?>@si', '', $artcache);

        $sql = "INSERT INTO ". $REX['TABLE_PREFIX'].$REX['TEMP_PREFIX']. '12_search_index' ." (id,path,clang,status,online_from,online_to,name,keywords,content) VALUES ('$var[id]','$var[path]','$var[clang]','$var[status]','$var[online_from]','$var[online_to]','$var[name]','$var[keywords]','".mysql_escape_string($artcache)."')";
        $db2->query($sql);
        $i++;

      }

      ob_end_clean();
      echo $CONTENT;
      $REX['GG'] = false;

      return "Suchindex wurde erneuert!";
    }
  }

  function rex_search($keywords)
  {
    global $REX;    

    if (trim($keywords) == '')
      return false;

    $keywords = mysql_escape_string((trim($keywords)));
    
    $suche = new sql;
    $suche->debugsql =& $this->debugsql;

    // ---------------------- clang check
    if ($this->clang > -1)
    {
      $clang_set = "AND clang='".$this->clang."'";
    }
    else
    {
      $clang_set = '';
    }

    // ---------------------- status check
    if ($this->status !== '')
    {
      if($this->status === true) $this->status = 1;
      if($this->status === false) $this->status = 0;

      $status_set = "AND status='".$this->status."'";
    }
    else
    {
      $status_set = '';
    }

    // ---------------------- path check
    if ($this->path)
    {
      $path_set = "AND path LIKE ('|".$this->path."|%')";
    }
    else
    {
      $path_set = '';
    }

    // ---------------------- searchIds check
    if ($this->searchIds)
    {
      $find_set = "(FIND_IN_SET('$keywords',REPLACE(id,' ',',')) * 10000000) +";
      $like_set = "id = '$keywords' OR";
    }

    $sql = "
              SELECT
        
              (FIND_IN_SET('$keywords',REPLACE(name,' ',',')) * 10000) +
              (FIND_IN_SET('$keywords',REPLACE(keywords,' ',',')) * 5) +
              $find_set
              (FIND_IN_SET('$keywords',REPLACE(content,' ',',')) * 5)
              AS COUNTWORD, id , name , content , clang
        
              FROM ". $REX['TABLE_PREFIX'].$REX['TEMP_PREFIX'].'12_search_index' ."
              WHERE
              (
              name LIKE ('%$keywords%') OR
              keywords LIKE ('%$keywords%') OR
              $like_set
              content LIKE ('%$keywords%')
              )
        
              $clang_set
        
              $path_set
        
              $status_set

              ". $this->custom_where_conditions ."
    
              ORDER BY COUNTWORD DESC
              LIMIT 0,50
              ";

    $suche->setQuery($sql);

    for ($c = 0; $c < $suche->getRows(); $c++)
    {

      $regex = "/\b.{0,".$this->surroundchars."}".$keywords.".{0,".$this->surroundchars."}\b/im";
      $regexcontent = $suche->getValue('name').$suche->getValue('keywords').$suche->getValue('content');

      preg_match_all($regex, $regexcontent, $matches = array());

      $result[$c]['id'] = $suche->getValue('id');
      $result[$c]['name'] = $suche->getValue('name');
      $result[$c]['clang'] = $suche->getValue('clang');
      if (is_array($matches[0]))
      {
        $result[$c]['highlightedtext'] = '';
        foreach ($matches[0] as $var)
        {
          $result[$c]['highlightedtext'] .= " ...".preg_replace("/(".$keywords.")/ims", $this->sourround_start_tag.'\\1'.$this->sourround_end_tag, $var)."... ";
        }
      }

      $suche->next();
    }

    return $result;
  }
}
?>
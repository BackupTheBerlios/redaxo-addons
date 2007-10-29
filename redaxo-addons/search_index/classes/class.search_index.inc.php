<?php

/**
 * Suche Addon
 *
 * @author vscope new media design
 * @author <a href="http://www.vscope.at">www.vscope.at</a>
 *
 */

class rex_search_index
{
  var $clang = -1;
  var $path = '';
  var $custom_where_conditions = '';
  var $status = '';
  var $surroundchars = 20;
  var $sourround_start_tag = '<span class="a12_highlighted">';
  var $sourround_end_tag = '</span>';
  var $striptags = true;
  var $allowable_tags = '';
  var $limit_start = 0;
  var $limit_end = 50;

  function indexTable()
  {
    global $REX;

    return $REX['TABLE_PREFIX'] . $REX['TEMP_PREFIX']. '12_search_index';
  }

  function rex_indexSite()
  {

    global $REX, $DB;

    $SQL = 'SELECT id,clang FROM rex_article ';

    $WHERE = '';

    // ----- diese artikel filtern
    /*
    $WHERE = " where status=1";

    $artikelidfilter = array(1,2,3,4,5,6);
    foreach($artikelidfilter as $key => $val)
    {
    	$WHERE .= " and id<>'".$key."'";
    }
    */

    $LIMIT = "";
    $db2 = new rex_sql;
    $stop = false;
    $oldstart = rex_request('oldstart', 'int');
    $start = rex_request('start', 'int');
    if ($oldstart == $start && $start !== 0)
    {
      $stop = true;
    }

    if ($start)
    {
      $LIMIT = "LIMIT $start, 4000";
      $oldstart = $start;
    }
    else
    {
      // Index erst nach den Daten einf�gen,
      // da in eine Tabelle mit schon bestehendem Index, der Insert viel l�nger dauert
      $db2->setQuery('ALTER TABLE `'. $this->indexTable() .'` DROP INDEX `full_content`');
      $db2->setQuery('ALTER TABLE `'. $this->indexTable() .'` DROP INDEX `full_name`');

      // Tabelle leeren
      $db2->setQuery('TRUNCATE TABLE `'. $this->indexTable() .'`');

      // kopiere alle Metadaten
      $db2->setQuery('INSERT INTO `'. $this->indexTable() .'` (`id`,`path`,`clang`,`status`,`name`,`keywords`)
                      SELECT `id`,`path`,`clang`,`status`,`name`,`art_keywords`
                      FROM `rex_article`');
    }

    if ($stop)
    {
      $error_id = rex_request('errorid');
      $error_clang = rex_request('errorclang');

      return "Bei der Indexgenerieung ist ein Fehler unterlaufen. Das kann an eventuell fehlerhaften Artikeln liegen.
      		Bei folgendem Artikel kam ein Fehler. <a href=index.php?page=content&article_id=". $error_id ."&mode=edit&clang=". $error_clang .">-> Artikel</a>";

    }
    else
    {

      $i = $start;
      $db2->setQuery($SQL. ' '. $WHERE .' '. $LIMIT);
      $CONTENT = ob_get_contents();
      ob_end_clean();

      for($t = 0; $t < $db2->getRows(); $t++)
      {
        $art_id = $db2->getValue('id');
        $art_clang = $db2->getValue('clang');

        ob_end_clean();
        ob_start();

        echo "<html><head><title>REX SEARCH</title></head><body bgcolor=#fffff3>
        			Scriptlaufzeit war zu kurz, der Prozess wird sofort
        			weitergef�hrt. Sollten Sie dennoch abbrechen wollen dann <a href=index.php?page=search_index>hier</a>.
        			<br><br>
        			Sollte das Script sich nicht erneut aufrufen, dann <a href=index.php?page=search_index&subpage=gen_index&start=". $i ."&oldstart=". $oldstart ."&errorid=".$art_id."&errorclang=".$art_clang.">hier</a> klicken um den Prozess weiterzuf�hren.

        			<br><br><a href=index.php?page=content&article_id=".$art_id."&mode=edit&clang=".$art_clang.">Bei diesem Artikel wurde abgebrochen</a>

        			<br><br><br><br>";

        $REX['GG'] = true;
        $REX['REDAXO'] = false;

        $article = new rex_article($art_id, $art_clang);
        $artcache = $article->getArticle();
        // Da dieser Prozess recht speicherintensiv ist, variable manuell l�schen
        unset($article);

        $artcache = rex_register_extension_point('OUTPUT_FILTER', $artcache);
        $artcache = rex_register_extension_point('SEARCH_ARTICLE_GENERATED', $artcache);

        $REX['REDAXO'] = true;
        $REX['GG'] = false;

        if ($this->striptags)
          $artcache = strip_tags($artcache, $this->allowable_tags);

        $sql = "UPDATE `". $this->indexTable() ."` SET content='".mysql_escape_string($artcache)."' WHERE id=". $art_id ." AND clang=". $art_clang;

        // falls im artikel eine andere datnebank aufgerufen wurde
        $db_insert = new rex_sql;
        $db_insert->setQuery($sql);

        $i++;
        $db2->next();
      }

      // Index erst nach den Daten einf�gen,
      // da in eine Tabelle mit schon bestehendem Index, der Insert viel l�nger dauert
      $db2->setQuery('ALTER TABLE `'. $this->indexTable() .'` ADD FULLTEXT `full_content` (`name` ,`keywords` ,`content`)');
      $db2->setQuery('ALTER TABLE `'. $this->indexTable() .'` ADD FULLTEXT `full_name` (`name`)');

      ob_end_clean();
      echo $CONTENT;

      return "Suchindex wurde erneuert!";
    }
  }

  function rex_search($keywords)
  {
    global $REX;

    if (trim($keywords) == '')
      return false;

		$keywords = (isset($keywords) ? htmlspecialchars(stripslashes($keywords)) : '');
    $keywords = mysql_escape_string((trim($keywords)));

    $suche = new rex_sql;
    // $suche->debugsql = true;

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

		$sql = 	"
						SELECT id, clang, name, keywords, content,
            MATCH(name) AGAINST ('$keywords') AS score_name,
            MATCH(name, keywords, content) AGAINST ('$keywords') AS score FROM ". $this->indexTable() ."
            WHERE MATCH(name, keywords, content)
            AGAINST ('$keywords')
            $clang_set
            $path_set
            $status_set
            ". $this->custom_where_conditions ."
            ORDER BY score_name DESC, score DESC
            LIMIT ".$this->limit_start.",".$this->limit_end."
            ";
    // $suche->debugsql = true;
    $suche->setQuery($sql);

    $result = array();
    for ($c = 0; $c < $suche->getRows(); $c++)
    {

      $regex = "/\b.{0,".$this->surroundchars."}".$keywords.".{0,".$this->surroundchars."}\b/im";
      $regexcontent = $suche->getValue('name').$suche->getValue('keywords').$suche->getValue('content');

      preg_match_all($regex, $regexcontent, $matches);

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
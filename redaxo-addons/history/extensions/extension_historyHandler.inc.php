<?php

function rex_a128_historyHandler($params)
{
  global $page, $subpage, $REX_USER, $REX;
  
  $mypage = $params['mypage'];

  require $REX['INCLUDE_PATH'] . '/addons/'. $mypage .'/classes/class.rex_historyManager.inc.php';
  require $REX['INCLUDE_PATH'] . '/addons/'. $mypage .'/classes/class.rex_history.inc.php';
  require $REX['INCLUDE_PATH'] . '/addons/'. $mypage .'/classes/class.rex_historyItem.inc.php';

  $function = rex_request('function', 'string');
  $mode = rex_request('mode', 'string');

  // Alle Histories registrierens
  $articleHistory = & rexHistoryManager :: getHistory('articles');
  $templateHistory = & rexHistoryManager :: getHistory('templates');
  $moduleHistory = & rexHistoryManager :: getHistory('modules');
  $actionHistory = & rexHistoryManager :: getHistory('actions');

  $sql = new rex_sql();
  $sql->debugsql = true;

  if ($page == 'module' && $function == 'edit' && ($module_id = rex_get('modul_id', 'int')) != 0)
  {
    $result = $sql->getArray('SELECT name FROM ' . $REX['TABLE_PREFIX'] . 'modultyp WHERE id=' . $module_id);
    if (isset ($result[0]))
    {
      $link = 'index.php?page=' . $page . '&function=' . $function . '&modul_id=' . $module_id;
      $title = $result[0]['name'];
      if ($REX_USER->hasPerm('advancedMode[]'))
        $title .= ' [' . $module_id . ']';

      $moduleHistory->addItem(new rexHistoryItem($title, $link));
    }
  }
  elseif ($page == 'module' && $subpage == 'actions' && $function == 'edit' && ($action_id = rex_get('action_id', 'int')) != 0)
  {
    $result = $sql->getArray('SELECT name FROM ' . $REX['TABLE_PREFIX'] . 'action WHERE id=' . $action_id);
    if (isset ($result[0]))
    {
      $link = 'index.php?page=' . $page . '&subpage=' . $subpage . '&function=' . $function . '&modul_id=' . $action_id;
      $title = $result[0]['name'];
      if ($REX_USER->hasPerm('advancedMode[]'))
        $title .= ' [' . $action_id . ']';

      $actionHistory->addItem(new rexHistoryItem($title, $link));
    }
  }
  elseif ($page == 'template' && $function == 'edit' && ($template_id = rex_get('template_id', 'int')) != 0)
  {
    $result = $sql->getArray('SELECT name FROM ' . $REX['TABLE_PREFIX'] . 'template WHERE id=' . $template_id);
    if (isset ($result[0]))
    {
      $link = 'index.php?page=' . $page . '&function=' . $function . '&template_id=' . $template_id;
      $title = $result[0]['name'];
      if ($REX_USER->hasPerm('advancedMode[]'))
        $title .= ' [' . $template_id . ']';

      $templateHistory->addItem(new rexHistoryItem($title, $link));
    }
  }
  elseif ($page == 'content' && $mode == 'edit' && ($article_id = rex_get('article_id', 'int')) != 0)
  {
    $art = OOArticle :: getArticleById($article_id);
    if (OOArticle :: isValid($art))
    {
      $link = 'index.php?page=' . $page . '&mode=' . $mode . '&article_id=' . $article_id;
      $title = $art->getName();
      if ($REX_USER->hasPerm('advancedMode[]'))
        $title .= ' [' . $article_id . ']';

      $articleHistory->addItem(new rexHistoryItem($title, $link));
    }
  }
}
?>
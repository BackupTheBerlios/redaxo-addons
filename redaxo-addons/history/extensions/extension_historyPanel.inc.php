<?php

function rex_a128_historyPanel($params)
{
  global $REX, $REX_USER;

  $content = $params['subject'];
  $mypage = $params['mypage'];

  if (isset ($REX_USER) && $REX_USER->hasPerm('history[]'))
  {
    // histories einbinden
    $content = str_replace('</body>', rexHistoryManager :: formatHistories() . "\n</body>", $content);

    // css einbinden
    $file = $REX['INCLUDE_PATH'] . '/addons/'. $mypage .'/css/history.css';
    $size = filesize($file);
    if ($size > 0 && ($hdl = fopen($file, 'r')))
    {
      $css = fread($hdl, $size);
      fclose($hdl);

      $css = '
      					<style type="text/css" media="screen, projection" >
      					<!--
      	        /* history-addon a128 */
      	        ' . $css . '
      					-->
      					</style>';
      $content = str_replace('</head>', $css . '</head>', $content);
    }
  }

  return $content;
}
?>
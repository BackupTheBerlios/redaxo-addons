<?php


################################################################################
#
# simple Benchmark Addon
# Shows Script Time in Frontend when activated
#
################################################################################

$mypage = "Benchmark";

$REX['ADDON']['rxid'][$mypage] = "REX_BENCHMARK";
$REX['ADDON']['page'][$mypage] = $mypage;
$REX['ADDON']['name'][$mypage] = "Benchmark";
$REX['ADDON']['perm'][$mypage] = "benchmark[]";

$REX['PERM'][] = "benchmark[]";

// Nur im Frontend
if (!$REX['REDAXO'])
{

  function rex_cached_benchmark($params)
  {
    return rex_benchmark($params, true);
  }

  function rex_benchmark($params, $cached = false)
  {
    $div = '<div style="position: absolute; top: 10px; left: 10px;background-color:#EFEFEF;border:2px solid;padding:4px" onClick="this.style.display=\'none\'">';
    $div .= '<b>Redaxo Benchmark Time:</b><br />'.showScripttime().' sec';

    if ($cached)
    {
      $div .= ' (cached)';
    }

    $div .= '</div>';

    $params['subject'] = str_replace('</body>', $div.'</body>', $params['subject']);
    return $params['subject'];
  }

  rex_register_extension('OUTPUT_CACHE', 'rex_cached_benchmark');
  rex_register_extension('OUTPUT_FILTER', 'rex_benchmark');
}
?>
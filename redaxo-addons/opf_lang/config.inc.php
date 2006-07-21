<?php

/** 
 * Config . Zuständig für den Outputfilter 
 * @author jan@kristinus
 * @version 0.9
 */


$mypage = "opf_lang"; // only for this file

$REX['ADDON']['rxid'][$mypage] = '';
$REX['ADDON']['page'][$mypage] = $mypage; // pagename/foldername
$REX['ADDON']['name'][$mypage] = 'Language OutputFiler'; // name
$REX['ADDON']['perm'][$mypage] = 'opf_lang[]'; // permission

$REX['PERM'][] = 'opf_lang[]';

if ($REX['GG'])
{
  rex_register_extension('OUTPUT_FILTER', 'rex_opf');

  function rex_opf($params)
  {
    global $REX;
    $content = $params['subject'];

    $gv = new sql;
    // $gv->debugsql = 1;
    $gv->setQuery("select * from rex_opf_lang where clang='".$REX['CUR_CLANG']."'");

    for ($i = 0; $i < $gv->getRows(); $i ++)
    {
      $content = str_replace($gv->getValue("replacename"), $gv->getValue("name"), $content);
      $gv->next();
    }

    return $content;

  }

}
?>
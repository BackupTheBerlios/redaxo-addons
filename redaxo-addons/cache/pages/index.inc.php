<?php

/**
 * Article Cache Addon
 *  
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * 
 * @author info[at]thomas-peterson[dot]de Thomas Peterson
 * @author <a href="http://www.thomas-peterson.de/">http://www.thomas-peterson.de/</a>
 * 
 * @package redaxo3
 * @version $Id: index.inc.php,v 1.1 2006/06/20 09:24:20 kills Exp $
 */

//------------------------------> Parameter

$Basedir = dirname(__FILE__);

if (!isset ($func))
{
  $func = '';
}

if (!isset ($subpage))
{
  $subpage = '';
}

//------------------------------> Main

require $REX['INCLUDE_PATH']."/layout/top.php";

rex_title($I18N_CACHE->msg('menu_title'), '');


switch($subpage){
    
//    case "lang":
//        require $Basedir .'/languages.inc.php';
//    break;
    default:
        require $Basedir .'/settings.inc.php';
}

require $REX['INCLUDE_PATH']."/layout/bottom.php";

?>
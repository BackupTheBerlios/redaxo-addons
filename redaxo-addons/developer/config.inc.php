<?php

/*
Addon by vscope new media
www.vscope.at
*/

session_start();

$mypage = "developer";
$I18N_ADDON = new i18n($REX[LANG],$REX[INCLUDE_PATH]."/addons/$mypage/lang/");
$REX[ADDON][rxid][$mypage] = "REX_DEVELOPER";
$REX[ADDON][page][$mypage] = "$mypage";
$REX[ADDON][perm][$mypage] = "developer[]";
$REX[PERM][] = "developer[]";
$REX[ADDON][developer][live_edit_path] = $REX[INCLUDE_PATH]."/addons/$mypage/live_edit/";

///////////////////////////////////////////////////////////////////////////////////////////////

// Include Funtions and Classes
include($REX[INCLUDE_PATH]."/addons/$mypage/classes/class.live_edit.inc.php");
include($REX[INCLUDE_PATH]."/addons/$mypage/classes/functions.inc.php");
include($REX[ADDON][developer][live_edit_path]."status.inc.php");

//print_r($GLOBALS);

//if($REX_USER->isValueOf("rights","developer[]")){
	// Backend Functions
	if($REX['REDAXO']){
	    if($page=="addon" && $addonname == "developer" && $activate == "0"){
	        $liveEdit = new liveEdit();
	        $liveEdit->developer_setStatus('MODULES',false);
	        $liveEdit->developer_setStatus('TEMPLATES',false);
	    }
	    rex_register_extension('OUTPUT_FILTER', 'backendEdit');
	}
//}

// Check for Modules Updates in Files
if($REX[ADDON][DEVELOPER][STATUS][MODULES]=="true"){

	$liveEdit = new liveEdit();
	$articles_overwrite = $page=="addon" && $addonname=="developer" && $activate=="1" ? true : false;
	$liveEdit->writeModuleFiles($articles_overwrite);

	if($page!="developer" && $action != "update"){
		$REX[ADDON][developer][regenerate] = $liveEdit->syncModules();
	    if(!$REX[REDAXO]){
	        rex_register_extension('OUTPUT_FILTER', 'regenerateArticlesByJavascript');
	    } else {
		  	$liveEdit = new liveEdit();
			$liveEdit->regenerateArticlesByModultypId($REX[ADDON][developer][regenerate]);
	    }
	}
}

// Check for Template Updates in Files
if($REX[ADDON][DEVELOPER][STATUS][TEMPLATES]=="true"){
	$liveEdit = new liveEdit();
	$templates_overwrite = $page=="addon" && $addonname=="developer" && $activate=="1" ? true : false;
	$liveEdit->writeTemplateFiles($templates_overwrite);
	$liveEdit->syncTemplates();
}
?>
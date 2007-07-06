<?php
class liveEdit {

    var $livePath;
    var $ModulePath = "modules/";
    var $ModuleInputExtension = ".input.php";
    var $ModuleOutputExtension = ".output.php";
    var $TemplatePath = "templates/";
	var $TemplateExtension = ".template.php";

    function liveEdit(){
        global $REX;
        $this->livePath = $REX[ADDON][developer][live_edit_path];
    }

    function getModuleFiles(){
	    if ($handle = opendir($this->livePath.$this->ModulePath)) {
	       while (false !== ($file = readdir($handle))) {
	           if ($file != "." && $file != "..") {
	              if(strstr($file,$this->ModuleInputExtension)){
	              	$id = str_replace($this->ModuleInputExtension,"",$file);
	              	$moduleFiles[] = $id;
	              }
	           }
	       }
	       closedir($handle);
		   return $moduleFiles;
	    }
    }
    function getTemplateFiles(){
	    if ($handle = opendir($this->livePath.$this->TemplatePath)) {
	       while (false !== ($file = readdir($handle))) {
	           if ($file != "." && $file != "..") {
	           		$id = str_replace($this->TemplateExtension,"",$file);
	              	$templateFiles[] = $id;
	           }
	       }
	       closedir($handle);
		   return $templateFiles;
	    }
    }
    function getModulesFormDB(){
        global $REX;
		$db = new sql();
		$sql = "SELECT id,ausgabe,eingabe FROM ".$REX['TABLE_PREFIX']."modultyp";
		$dbResult = $db->get_array($sql);
		if(is_array($dbResult)){
			foreach($dbResult as $var){
			    $modules[$var[id]][eingabe] = $var[eingabe];
			    $modules[$var[id]][ausgabe] = $var[ausgabe];
			}
			return $modules;
		}


    }
    function getTemplatesFormDB(){
        global $REX;
		$db = new sql();
		$sql = "SELECT id,content FROM ".$REX['TABLE_PREFIX']."template";
		$dbResult = $db->get_array($sql);
		if(is_array($dbResult)){
			foreach($dbResult as $var){
			    $templates[$var[id]] = $var[content];
			}
			return $templates;
		}


    }
    function writeModuleFiles($overwrite=false){
        $modulesFiles = $this->getModuleFiles();
        $modulesDB = $this->getModulesFormDB();
        if(is_array($modulesDB)){
            foreach($modulesDB as $key=>$var){
                if(!@in_array($key,$modulesFiles) || $overwrite==true){
                    $inputFilename = $this->livePath.$this->ModulePath.$key.$this->ModuleInputExtension;
					$outputFilename = $this->livePath.$this->ModulePath.$key.$this->ModuleOutputExtension;
					$this->writeFile($inputFilename,$var[eingabe]);
					$this->writeFile($outputFilename,$var[ausgabe]);
					//print "wrote $outputFilename<br>";
				}

            }
        }
    }
    function writeTemplateFiles($overwrite=false){
        $templateFiles = $this->getTemplateFiles();
        $templatesDB = $this->getTemplatesFormDB();
        if(is_array($templatesDB)){
            foreach($templatesDB as $key=>$var){
                if(!@in_array($key,$templateFiles) || $overwrite==true){
					$templateFilename = $this->livePath.$this->TemplatePath.$key.$this->TemplateExtension;
					$this->writeFile($templateFilename,$var);
					//print "wrote $templateFilename<br>";
				}

            }
        }
    }
    function setConfigValue($filename,$valuename,$value){
		$content = file_get_contents($filename);
		$content = preg_replace("/\[$valuename\] = .*;/imsU","[$valuename] = \"".$value."\";",$content);
		$this->writeFile($filename,$content);
    }
    function writeFile($filename,$content){
        $handle = fopen($filename, 'w');
        fwrite($handle, $content);
        fclose($handle);
        @chmod($filename,0777);
    }
    function syncModules(){
        global $REX;
	    $moduleFiles = $this->getModuleFiles();
	    if(is_array($moduleFiles)){
	        foreach($moduleFiles as $modId){
	            if(file_exists($this->livePath.$this->ModulePath.$modId.$this->ModuleInputExtension) && file_exists($this->livePath.$this->ModulePath.$modId.$this->ModuleOutputExtension)){
	                $eingabe =  file_get_contents($this->livePath.$this->ModulePath.$modId.$this->ModuleInputExtension);
	                $ausgabe =  file_get_contents($this->livePath.$this->ModulePath.$modId.$this->ModuleOutputExtension);
	                $db = new sql();
	                $sql = "UPDATE ".$REX['TABLE_PREFIX']."modultyp SET eingabe='".mysql_escape_string($eingabe)."', ausgabe='".mysql_escape_string($ausgabe)."' WHERE id='$modId'";
	                $db->setQuery($sql);
	                if(mysql_affected_rows($db->identifier)!=0){
	                    $regenerate .= $modId."__";
	                    //print "generated db".$modId."<br>";
	                }
	            }
	        }
	    }
	    if($REX['REDAXO']){
			$this->regenerateArticlesByModultypId($modules);
		}
	    return $regenerate;
    }
    function syncTemplates(){
        global $REX;
	    $templateFiles = $this->getTemplateFiles();
	    if(is_array($templateFiles)){
	        foreach($templateFiles as $templateId){
	            if(file_exists($this->livePath.$this->TemplatePath.$templateId.$this->TemplateExtension)){
	                $content =  file_get_contents($this->livePath.$this->TemplatePath.$templateId.$this->TemplateExtension);
	                $db = new sql();
	                $sql = "UPDATE ".$REX['TABLE_PREFIX']."template SET content='".mysql_escape_string($content)."' WHERE id='$templateId'";
	                $db->setQuery($sql);
	                if(mysql_affected_rows($db->identifier)>0){
	                    $this->generateTemplate($templateId,$content);
	                    //print "generated template db".$templateId."<br>";
	                }
	            }
	        }
	    }
    }
    function generateTemplate($templateId){
        global $REX;

        $templatePathDeveloper = $this->livePath.$this->TemplatePath.$templateId.$this->TemplateExtension;
        $templatePathRedaxo = $REX['INCLUDE_PATH']."/generated/templates/".$templateId.".template";
        copy($templatePathDeveloper,$templatePathRedaxo);
    }
    function regenerateArticlesByModultypId($modules){
    	global $PHP_SELF, $module_id, $FORM, $REX_USER, $REX, $I18N;
    	include_once($REX[INCLUDE_PATH]."/clang.inc.php");
	    $modules = explode("__",$modules);
	    array_pop($modules);
	    $gc = new sql;
	    foreach($modules as $modul_id){
	        $gc->setQuery("SELECT DISTINCT(".$REX['TABLE_PREFIX']."article.id) FROM ".$REX['TABLE_PREFIX']."article
	        LEFT JOIN ".$REX['TABLE_PREFIX']."article_slice ON ".$REX['TABLE_PREFIX']."article.id=".$REX['TABLE_PREFIX']."article_slice.article_id
	        WHERE ".$REX['TABLE_PREFIX']."article_slice.modultyp_id='$modul_id'");
	        for ($i=0; $i<$gc->getRows(); $i++)
	        {
	            rex_generateArticle($gc->getValue($REX['TABLE_PREFIX']."article.id"));
	            $gc->next();
	        }
	    }
    }
	function developer_setStatus($what='modules',$status=false){
	    global $REX;
	    $statusFile = $REX[ADDON][developer][live_edit_path]."status.inc.php";
	    $stat = $status == "true" ? "true" : "false";
	    $this->setConfigValue($statusFile,$what,$status);
	    if($what == "MODULES" && $stat == "true"){
	        $this->writeModuleFiles(true);
	    }
	    if($what == "TEMPLATES" && $stat == "true"){
	        $this->writeTemplateFiles(true);
	    }
	}
}
?>

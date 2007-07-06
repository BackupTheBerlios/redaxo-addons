<?php

function backendEdit($params){
    global $REX,$REX_USER;
    if($REX_USER){
	    if($REX_USER->isValueOf("rights","developer[]")){
	        $content = $params['subject'];
	        if($REX[ADDON][DEVELOPER][STATUS][MODULES]=="true") $developer_modules = "checked";
	        if($REX[ADDON][DEVELOPER][STATUS][TEMPLATES]=="true") $developer_templates = "checked";
	        $replace = '
	        <script>
	        if (document.implementation && document.implementation.createDocument)
	        {
	            xmlDoc = document.implementation.createDocument("", "", null);
	            xmlDoc.onload = function() { alert("Status gesetzt"); };
	        }
	        else if (window.ActiveXObject)
	        {
	            xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
	            xmlDoc.onreadystatechange = function () {
	                if (xmlDoc.readyState == 4) alert("Status gesetzt");
	            };
	        }
	        function saveStatus(obj){
	            status_modules = obj.developer_modules.checked;
	            status_templates = obj.developer_templates.checked;
	            var url = "./index.php?page=developer&function=setStatus&status_modules="+status_modules+"&status_templates="+status_templates;
	            xmlDoc.load(url);
	            //prompt("debug",url);
	            return false;
	        }
	        </script>
	        ';
	        $replace .= '<form name=addon_developer onSubmit="return saveStatus(this);" style=display:inline>';
	        $replace .= "<table cellpadding=0 cellspacing=0><tr><th style=color:#000000>";
	        $replace .= "Redaxo Developer Addon: ";
	        $replace .= "</th><th>";
	        $replace .= "<input type=checkbox name=developer_modules value=1 $developer_modules> ";
	        $replace .= "</th><th style=color:#000000>";
	        $replace .= "Module automatisch updaten &nbsp;";
	        $replace .= "</th><th>";
	        $replace .= "<input type=checkbox name=developer_templates value=1 $developer_templates> ";
	        $replace .= "</th><th style=color:#000000>";
	        $replace .= "Templates automatisch updaten &nbsp;";
	        $replace .= "</th><th>";
	        $replace .= "<input type=submit value=ok> ";
	        $replace .= "</th></table>";
	        $replace .= "</form>";
	        $content = str_replace('<th colspan="2">&nbsp;</th>','<th colspan="2"><b><div align=left><font color=#000>'.$replace.'</th>',$content);
	        return $content;
	    }
	}
}

function regenerateArticlesByJavascript($params){
    global $REX;
    if($REX[ADDON][developer][regenerate]){
        $regenerateString = $REX[ADDON][developer][regenerate];
        $content = $params['subject'];
        $content = '
        <div style="position: absolute; top: 10px; left: 10px;background-color:#EFEFEF;border:2px solid;padding:4px; font-family: tahoma, verdana, sans-serif; font-size: 11px; width: 200px;"><b>Redaxo Developer Addon regenerating Articles:</b>
        <div id="status"></div>
        </div>
        <script>
        function status(count)
        {
            var div = document.getElementById("status");
            if(div)
                div.innerHTML += "· ";
            count++;
            window.setTimeout("status(" + count + ");", 50);
        }
        status(0);

        function rel()
        {
            location.reload();
        }

        if (document.implementation && document.implementation.createDocument)
                {
                    xmlDoc = document.implementation.createDocument("", "", null);
                    xmlDoc.onload = function() { rel(); };
                }
                else if (window.ActiveXObject)
                {
                    xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
                    xmlDoc.onreadystatechange = function () {
                        if (xmlDoc.readyState == 4) rel()
                    };
                }
                else
                {
                    alert("Your browser cant handle this script");
                }
                var url = "./redaxo/index.php?page=developer&function=update&modules='.$regenerateString.'";
                //document.write(url);
                xmlDoc.load(url);
        </script>
        ';

        return $content;
    }
}
?>
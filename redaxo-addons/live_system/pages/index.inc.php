<?php

include $REX[INCLUDE_PATH]."/layout/top.php";

rex_title("Live System Sync ","
&nbsp;&nbsp;&nbsp;
<a href=index.php?page=live_system&function=sync onClick=\"return confirm('Live Sync wirklich durchführen?')\">Live Sync durchführen</a>");
if ($msg != "") echo "<table border=0 cellpadding=5 cellspacing=1 width=770><tr><td class=warning>$msg</td></tr></table><br>";

?>

<?php
if($function == "sync")
{

    $db = new sql();
    include($REX[INCLUDE_PATH]."/addons/live_system/classes/function.syncMySQLData.inc.php");
    include($REX[INCLUDE_PATH]."/addons/live_system/classes/class.MySQLDump.inc.php");
    $live_db = new sql("live_db");

		$res = $db->get_array("SHOW TABLES");

		print "&nbsp;&nbsp;<b>Starte Datenbanksync</b><br>";

		foreach($res as $var)
		{
					$table = $var["Tables_in_".$REX['DB']['1']['NAME']];
	        $sql = "TRUNCATE $table";
	        $live_db->query($sql);
		}

		print "&nbsp;&nbsp;<span id=dbc>0</span> Datenbankeintäge gesynct.";

		?>
		<script>
			function dbc(){
				if(document.getElementById('dbcount')){
			    	document.getElementById('dbc').innerHTML = document.getElementById('dbcount').innerHTML.length;
			    }
			    window.setTimeout('dbc();', 100);
			}
			dbc();
		</script>
		<?php

		print "<div id=dbcount style=display:none>";
		syncMySQLData($db->identifier, $REX['DB']['1']['NAME'], $live_db);
		print "</div>";

		print "<br>&nbsp;&nbsp;Datenbank gesynct.<br><br>";

		print "&nbsp;&nbsp;<b>Starte Dateisync</b><br>";
		print "&nbsp;&nbsp;<span id=fc>0</span> Dateien gesynct.";
		?>
		<script>
			function lfc(){
			    if(document.getElementById('filecount')){
			    	document.getElementById('fc').innerHTML = document.getElementById('filecount').innerHTML.length;
				}
				window.setTimeout('lfc();', 100);
			}
			lfc();
		</script>
		<?php

		print "<div id=filecount style=display:none>";
		dircpy($REX[ADDON]["live_system"][dev_path], $REX[ADDON]["live_system"][live_path], true);
		print "</div>";

		$masterfile = $REX[ADDON]["live_system"][live_path] . "/redaxo/include/master.inc.php";
		$master = file_get_contents($masterfile);
        $replace = array("NAME","HOST","LOGIN","PSW");
        foreach($replace as $rep){
        	$master = preg_replace("/\['DB'\]\['1'\]\['".$rep."'\] = \"(.*)\";/U",'[\'DB\'][\'1\'][\''.$rep.'\'] = "'.$REX['DB']['live_db'][$rep].'";',$master);
        }
		$handle = fopen($masterfile, 'w');
		fwrite($handle, $master);
		fclose($handle);

		print "<br>&nbsp;&nbsp;Alle Dateien gesynct.<br><br>";

		if($REX[ADDON]["live_system"][live_secure] == true)
		{
			$hta = fopen($REX[ADDON]["live_system"][live_path] . "/redaxo/.htaccess", "w");
			fputs($hta, "order deny,allow\ndeny from all\nrequire valid-user\n");
			fclose($hta);
			print "&nbsp;&nbsp;<b>Live System secured.</b><br><br>";
		}

    print "&nbsp;&nbsp;<b>Live Sync wurde durchgeführt!</b>";
}


function dircpy($source, $dest, $overwrite = false){
  if($handle = opendir($source)){        // if the folder exploration is sucsessful, continue
   while(false !== ($file = readdir($handle))){ // as long as storing the next file to $file is successful, continue
     if($file != '.' && $file != '..' && $file != '.htaccess'){
       $path = $source . '/' . $file;
       if(is_file($path)){
         if(!is_file($dest . '/' . $file) || $overwrite)
           if(!@copy($path, $dest . '/' . $file))
	           {
	             echo '<font color="red">File ('.$path.') could not be copied, likely a permissions problem.</font>';
	           }
	           else
	           {
	           	$fc++;
	           	print ".";
	           	ob_flush();
	           	flush();
	          }
       } elseif(is_dir($path)){
         if(!is_dir($dest . '/' . $file))
           mkdir($dest . '/' . $file); // make subdirectory before subdirectory is copied
         dircpy($path, $dest . '/' . $file, $overwrite); //recurse!
       }
     }
   }
   closedir($handle);
  }
}

?>
<table style="width: 770px" cellpadding="0" cellspacing="0"><tr><td>
</td></tr></table>
<br><br>

<?php

include $REX[INCLUDE_PATH]."/layout/bottom.php";

?>

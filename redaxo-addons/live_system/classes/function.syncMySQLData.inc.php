<?php
function syncMySQLData($link, $mdb_name, $live_db)
{
  $dump = new MySQLDump();
  $sql = $dump->dumpDatabase($mdb_name,$link);
  $sql = explode("\n",$sql);

  if(is_array($sql)){
    foreach($sql as $sqlstring){
      $live_db->query($sqlstring);
      print ".";
			ob_flush();
			flush();
    }
  }
  return true;
}
?>

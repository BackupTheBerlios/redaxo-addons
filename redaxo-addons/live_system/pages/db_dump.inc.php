<?php
function mysql_format($strTemp)
{
   $bad_chars= array("\\", "'");
   $good_chars = array("\\\\", "''");
   return str_replace($bad_chars, $good_chars, $strTemp);
}

function dumpData($link, $mdb_name, $live_link)
{
	$result = mysql_list_tables($mdb_name, $link);
	while ($row = mysql_fetch_row($result))
	{
		$getdata = mysql_query("SELECT * FROM $row[0]", $link);
		while ($row1=mysql_fetch_array($getdata))
		{
			$thesql = "INSERT INTO `$row[0]` VALUES (";
			$getcols = mysql_list_fields($mdb_name,$row[0], $link);
			for($c=0; $c<mysql_num_fields($getcols); $c++)
			{
				$thesql .= "'" . mysql_format($row1[$c]) . "'";
				if($c < mysql_num_fields($getcols) - 1)
					$thesql .= ",";
			}
			$thesql .= ")";
			mysql_query("$thesql", $live_link);
			unset($err);
			$err = mysql_error();
			if($err)
			{
				print "Query: " . $tok . "<br>";
				print "Error: " . $err . "<br>";
			}
			else
			{
				print ".";
				ob_flush();
				flush();
			}
			$thesql = "";
		}
	}
	return true;
}
?>
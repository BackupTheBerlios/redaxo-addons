<?php

/**
* Dump data from MySQL database
*
* @name    MySQLDump
* @author  Marcus Vinícius <mv@cidademais.com.br>
* @version 1.1 2005-06-01
* @example
*
* $dump = new MySQLDump();
* print $dump->dumpDatabase("mydb");
*
*/
class MySQLDump {


    /**
     * Dump data and structure from MySQL database
     *
     * @param string $database
     * @return string
     */
    function dumpDatabase($database,$link) {

        // Connect to database
        $db = mysql_select_db($database,$link);

        if (!empty($db)) {

            // Get all table names from database
            $c = 0;
            $result = mysql_list_tables($database,$link);
            for($x = 0; $x < mysql_num_rows($result); $x++) {
                $table = mysql_tablename($result, $x);
                if (!empty($table)) {
                    $arr_tables[$c] = mysql_tablename($result, $x);
                    $c++;
                }
            }

            // List tables
            $dump = '';
            for ($y = 0; $y < count($arr_tables); $y++){

                // DB Table name
                $table = $arr_tables[$y];

                // Dump data
                unset($data);
                $result     = mysql_query("SELECT * FROM `$table`",$link);
                $num_rows   = mysql_num_rows($result);
                $num_fields = mysql_num_fields($result);

                for ($i = 0; $i < $num_rows; $i++) {

                    $row = mysql_fetch_object($result);
                    $data .= "INSERT INTO `$table` (";

                    // Field names
                    for ($x = 0; $x < $num_fields; $x++) {

                        $field_name = mysql_field_name($result, $x);

                        $data .= "`{$field_name}`";
                        $data .= ($x < ($num_fields - 1)) ? ", " : false;

                    }

                    $data .= ") VALUES (";

                    // Values
                    for ($x = 0; $x < $num_fields; $x++) {
                        $field_name = mysql_field_name($result, $x);

                        $data .= "'" . str_replace('\"', '"', mysql_escape_string($row->$field_name)) . "'";
                        $data .= ($x < ($num_fields - 1)) ? ", " : false;

                    }

                    $data.= ");\n";
                }

                $data.= "\n";

                $dump .= $structure . $data;
                $dump .= "-- --------------------------------------------------------\n\n";

            }

            return $dump;

        }

    }

}

?>
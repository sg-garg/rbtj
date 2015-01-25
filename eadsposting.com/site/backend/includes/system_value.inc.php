<?php
//LDF SCRIPTS
if (!defined('version')){
exit;}
 global $mysql_hostname, 
            $mysql_database;

        if (!$value){
        list ($value)=@mysql_fetch_row(
            @mysql_query('select value from system_values where name="'.$key.'"'));
        }
       if ($key=='domain')
          $value=strtolower($value); 
       if ($key=='turinglines' && ($value<1 || $value >30))
           $value=10;
	   if ($key=='turingcolors' && ($value<1 || $value >255))
           $value=200;

        return ($value);

?>

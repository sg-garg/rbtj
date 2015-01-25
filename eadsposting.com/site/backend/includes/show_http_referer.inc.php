<?php
if (!defined('version')){exit;}
        
$getreferer=@mysql_query('select count(*),http_referer from ' . mysql_prefix . 'users where referrer="'.$_SESSION['username'].'" and http_referer!="" and http_referer not like "'.pages_url.'%" group by http_referer');

        while ($row=@mysql_fetch_row($getreferer))
        {        
            echo $f . $row[0] . $m . '<a href="'.safeentities($row[1]).'" target="_blank">'.safeentities($row[1]).'</a>' . $e;
        }
return 1;
?>

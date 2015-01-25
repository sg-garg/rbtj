<?php
if (!defined('version'))exit;

//----------------------------
// Get counters
//----------------------------
list($guests_online) = mysql_fetch_array(mysql_query("SELECT count(*) FROM `".mysql_prefix."visitors_online` WHERE username = ''"));
$members_online = mysql_num_rows(mysql_query("SELECT username FROM `".mysql_prefix."visitors_online` WHERE username != '' GROUP BY username")); 

//----------------------------
// Replace counters
//----------------------------
$args = str_replace('%MEMBERS%', $members_online, $args);
$args = str_replace('%GUESTS%', $guests_online, $args);

//----------------------------
// Show text
//----------------------------
echo $args;

?>
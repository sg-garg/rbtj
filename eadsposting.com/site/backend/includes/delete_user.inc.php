<?php
if (!defined('version'))
{
    exit;
}

mysql_query ('update ' . mysql_prefix . 'users set upline="'.$upline.'",rebuild_stats_cache=1 where upline="'.$username.'"');
mysql_query ('delete from ' . mysql_prefix . 'user_inbox where username="' . $username .'"');
mysql_query ('delete from ' . mysql_prefix . 'user_inbox where username="-' . $username . '"');
mysql_query ('delete from ' . mysql_prefix . 'notes where username="'.$username.'"');
mysql_query ('delete from ' . mysql_prefix . 'last_login where username="'.$username.'"');
mysql_query ('delete from ' . mysql_prefix . 'free_refs where username="'.$username.'"');
mysql_query ('delete from ' . mysql_prefix . 'latest_stats where username="'.$username.'"');
mysql_query ('delete from ' . mysql_prefix . 'levels where upline="$username" or username="'.$username.'"');
mysql_query ('delete from ' . mysql_prefix . 'interests where username="'.$username.'"');
mysql_query ('delete from ' . mysql_prefix . 'accounting where username="'.$username.'"');
mysql_query ('delete from ' . mysql_prefix . 'paid_clicks where username="'.$username.'"');
$result = mysql_query('select id from '.mysql_prefix.'signups_to_process where username="'.$username.'"');
while ($row = mysql_fetch_row($result))
{
    mysql_query('delete from '.mysql_prefix.'signups_to_process where username="'.$username.'" and id='.$row[0]);     
    mysql_query('update '.mysql_prefix.'pts_ads set signups=signups-1 where ptsid='.$row[0].' and signups>0');
    mysql_query('delete from '.mysql_prefix.'paid_signups_'.$row[0].' where username="'.$username.'"');                              
}
mysql_query ('delete from ' . mysql_prefix . 'users where username="'.$username.'"');
return 1;
?>

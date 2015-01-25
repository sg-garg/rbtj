<?php
if (!defined('version') || substr(md5($_GET['USER'] . $_GET['ADGROUP'].key), 0, 8)!=$_GET['HASH'])
   exit;

$room = preg_replace('([^a-zA-Z0-9])', '', $_GET['ROOM']);
$online=unixtime-20;

@mysql_query('replace into '.mysql_prefix.'chat_users set time="'.unixtime.'",user="'.$_GET['USER'].'",color="'.$_SESSION['chat_color'].'",room="'.$room.'"');
@mysql_query('delete from '.mysql_prefix.'chat_users where time<'.$online);

$chat=@mysql_query('select user,color from '.mysql_prefix.'chat_users where room="'.$room.'" order by user');

while ($row=@mysql_fetch_array($chat))
{
    echo "<font color=".$row['color']."\">&nbsp;".$row['user']."</font><br />";
}
exit;
?>
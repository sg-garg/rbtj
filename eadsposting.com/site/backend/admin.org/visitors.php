<?php
include("../conf.inc.php");
include("functions.inc.php");
$title='Visitors Online';
admin_login();

?>
To enable this feature, add <b>&lt;?php action('visitor'); ?&gt;</b> code to pages where you want to register visitors from. For example:<br>
<b>footer.php</b> to register visitors from all main pages and/or <b>account_credited.php</b> to register members who are clicking ads<br>
<hr>
<?php
//----------------------------------
// Fetch counter data
//----------------------------------
list($guests_online) = mysql_fetch_array(mysql_query("SELECT count(*) FROM `".mysql_prefix."visitors_online` WHERE username = ''"));
list($members_online) = mysql_fetch_array(mysql_query("SELECT count(*) FROM `".mysql_prefix."visitors_online` WHERE username != ''"));
?>
Members online: <?php echo $members_online;?><br>
Guests online: <?php echo $guests_online;?><br>
<hr>
Code to show above text:<br><b>&lt;?php action('visitor_count','Members online: %MEMBERS%&lt;br&gt;Guests online: %GUESTS%&lt;br&gt;');?&gt;</b><br>
<br>
<?php

//----------------------------------
// Fetch visitor data
//----------------------------------
$query = mysql_query('SELECT *,NOW() as now FROM `'.mysql_prefix.'visitors_online` WHERE 1 ORDER BY time DESC');

//----------------------------------
// Show visitor data
//----------------------------------
echo "<table class='centered' border='1' cellpadding='2' cellspacing='0'>\n";
echo "<tr><th>Ago</th><th>IP</th><th>Username</th><th>Location</th><th>HTTP Referer</th></tr>\n";

while($row = mysql_fetch_array($query))
{
    $time = strtotime($row['now']) - strtotime($row['time']);
    if($time >= 60)
    {
        $difference = (int)($time / 60) ."m";
    }else{
        $difference = $time ."s";
    }
    $row['http_referer'] = str_replace('http://', '', str_replace('http://www.', '', $row['http_referer']));
    echo "<tr><td>$difference</td><td>{$row['ip']}</td><td><a href=viewuser.php?userid={$row['username']}>{$row['username']}</a></td><td>". htmlspecialchars($row['request']) ."</td><td>". htmlspecialchars($row['http_referer']) ."</td></tr>\n";
}
echo "</table>\n";
footer();

?>

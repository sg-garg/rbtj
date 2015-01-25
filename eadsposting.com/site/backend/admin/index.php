<?php
//LDF SCRIPTS
require_once("functions.inc.php");
$mainindex=1;


if (!system_value("admin password"))
include("password.php");

$title='Site Info';
admin_login();
echo '<center>';
@mysql_query("insert into ".mysql_prefix."system_values set value='".unixtime."' name='cronjobs_ran_at'");

if (system_value('cronjobs_ran_at')<unixtime-3600)
{
    echo "<SCRIPT language='JavaScript'><!--
    cronjobs=window.open('".scripts_url."admin/cronjobs.php?runbybrowser=1','cronjobs','width=400,height=240,left=0,top=0,toolbars=0, scrollbars=0, location=0, statusbars=0, menubars=0, resizable=0');
    //-->
    </script>";
    sleep(5);
}
if (system_value('cronjobs_ran_at')<unixtime-3600)
{
    echo "Your cronjobs.php script does not seem to have been ran recently. <a href='".scripts_url."admin/cronjobs.php?runbybrowser=1' target='_cronjobs'><b>click here to start</b></a><br>";
}
if (!$_GET['continue'] && !ini_get('safe_mode') && last_backup<=unixtime-(bkwarn*3600) && rand(0,1))
{
    echo "It has been more then ".system_value('bkwarn')." hour(s) since your last SUCCESSFUL MySQL backup<br>What do you want to do?<br><a href='backup.php'>I will make my backup now</a><br><a href='index.php?continue=yes'>I wish to continue without making a backup</a>";
    footer();
}
?>
<br>
<span class='fsize4'>Last Admin Action</span> <a href='access_log.php'>View 30 day log</a>
<table border='1' width='500' cellpadding='2' cellspacing='0'><tr><Td>
<?php
$result=@mysql_query('select value from '.mysql_prefix.'access_log order by time desc limit 1');
while ($row=@mysql_fetch_row($result))
echo nl2br(trim($row[0]));
?>
</td></tr></table>

<table border='0'>
<tr>
<td align='center' valign='top'><h1>Login to 3rd Party Support Applications</h1><br />
<table border=0><tr><td align=center>
<img height=50 src=/phpMyAdmin/themes/pmahomme/img/logo_right.png>
<form method="post" action="/phpMyAdmin/index.php">
            <input type="hidden" name="pma_username"  value="ldf">
            <input type="hidden" name="pma_password"  value="tiger123">
        <input type="hidden" name="server" value="1" />    
        <input value="Manage Mysql" type="submit">
    <input type="hidden" name="target" value="url.php" />
</form>
</td><td align=center>
<img height=50 src=webmin.jpg />
<form class='ui_form' action='http://192.168.0.11:10000/session_login.cgi' method=post >
<input type=hidden name="page" value="/">
<input type='hidden' name="user" value="admin">
<input type=hidden name="pass" value="tiger123">
<input type=submit value="Manage Server">
</form>
</td></tr></table>
</td>
</tr>
</table>
</center>
<?php footer(); ?>

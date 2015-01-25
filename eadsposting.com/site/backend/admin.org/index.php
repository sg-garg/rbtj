<?php
require_once("functions.inc.php");
$mainindex=1;

if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
include("license.php");

if (!system_value("admin password"))
include("password.php");

$title='Site Info';
admin_login();
echo '<center>';
@mysql_query("insert into ".mysql_prefix."system_values set value='".unixtime."' name='cronjobs_ran_at'");
if (file_exists("../converters"))
{
    echo "<span class='warning'><h3>Security Alert</H3>";
    echo "You need to remove the ".scripts_dir."converters dir from your server<br><br>"; 
    echo "</span>";
}
if ((accounting_db!=$mysql_database || accounting_tbl!=mysql_prefix.'accounting') && defined('accounting_db'))
{
    if (!@mysql_num_rows(@mysql_query("describe ".accounting_db.".".accounting_tbl)))
    {
        echo "<font color='red'><h3>Commission settings error</H3>";
        echo "the commissions only accounting table ".accounting_db.".".accounting_tbl." does not seem to existing please verify your <a href='accounting_settings.php'>accounting settings</a><br><br>";
        echo "</font>";
    }
}
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
    echo "Make sure you have cronjobs set up, if you recently created a cronjob entry, this message will disappear after next cron run.<br>More info about cronjobs in README file.<br><br>";
}
if (!$_GET['continue'] && !ini_get('safe_mode') && last_backup<=unixtime-(bkwarn*3600) && rand(0,1))
{
    echo "<b>Reasons you should backup even if your host makes a backup: <br><br>1) Your host does not inspect every backup to make sure they are complete; However, you can.<br><br> 2) Depending on the server load at the time the host backup may fail to backup everything.<br><br> 3) Do you really know your host is making the backups they claim?<br><br> 4) If a hacker hacks the server and deletes the data, chances are he has access to the backups and can delete that as well.<br><br> 5) If your host's facilities are flooded, burnt to the ground, robbed, or your host goes out of business the backups they made will not be much good to you.<br><br></b><br> It has been more then ".system_value('bkwarn')." hour(s) since your last SUCCESSFUL MySQL backup<br>What do you want to do?<br><a href='backup.php'>I will make my backup now</a><br><a href='index.php?continue=yes'>I wish to continue without making a backup</a>";
    footer();
}
?>
<?php 
if ($keyis)
{
    @mysql_query("replace into ".mysql_prefix."system_values set name='key',value='$keyis'");
}
?>
CashCrusader Version <?php echo system_value("version")." ";

$fp = @fsockopen("cashcrusadersoftware.com", 80,$errno, $errstr, 10);
if($fp) 
{ 
    $count=0;
    if (system_value('cclist')!='no')
    {
        list($count)=@mysql_fetch_row(@mysql_query("select count(*) from ".mysql_prefix."users"));
    }
    fputs($fp,"GET /news.php?version=".system_value("version")."&domain_name=".domain." HTTP/1.0\r\nHost: cashcrusadersoftware.com\r\n\r\n");
    $start = unixtime;
    socket_set_timeout($fp, 20);
    $res = fread($fp, 10000);
    fclose($fp);
    $res=explode("<html>",$res);       
}
if (!$res[1])
{
    $res[1]='Click here to check for updates';
}
echo "<a href='http://cashcrusadersoftware.com/news.php?domain_name=".system_value('domain')."&amp;scripts=".scripts_url."&amp;key=".system_value("key")."' target='_news'>$res[1]</a>";
?>
<br>

        <form name='vote' style='margin:0px; padding:0px;' method='POST'  action='http://cashcrusadersoftware.com/vote.php'>
        <input type='hidden' name='domain' value='<?php echo domain; ?>'>
        <input type='hidden' name='license' value='<?php echo system_value("key"); ?>'>
        <a href='#' onClick="javascript:document.forms['vote'].submit();">Vote for CashCrusader enhancements</a>
        </form><br>

<a href='update.php'>Run the CashCrusader Auto Update Utility</a><br>
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
<td valign='top' align='center'>
    <span class='fsize4'>Keyword Totals</span>
    <table border='1' cellpadding='2' cellspacing='0'>
    <tr>
    <td bgcolor='#4E8CD1'>
        <span style='color:white' class='fsize2'>Keyword</span>
    </td>
    <td bgcolor='#4E8CD1'>
        <span style='color:white' class='fsize2'>Members</span>
    </td>
    </tr>
    <?php keyword_totals();?>
    </table>
</td>
<td align='center' valign='top'>
    <span class='fsize4'>Site Stats</span>
    <table  border='1' cellpadding='2' cellspacing='0' width='180'>
    <tr>
    <td bgcolor='#4E8CD1' colspan='2' align='center'>
        <span style='color:white' class='fsize2'>Members</span>
    </td>
    </tr>
    <tr>
    <td>
        Total
    </td>
    <td align='right'>
        <?php usercount(); ?>
    </td>
    </tr>
    <tr>
    <td>
        Active
    </td>
    <td align='right'>
        <?php usercount('active'); ?>
    </td>
    </tr>
    <tr>
    <td colspan='2' align='center' bgcolor='#4E8CD1'>
        <span style='color:white;' class='fsize2'>Cash Totals</span>
    </td>
    </tr>
    <tr>
    <td>
        Cash Credits
    </td>
    <td align='right'>
    <?php
        list($cash)=@mysql_fetch_row(@mysql_query("select sum(amount) from ".mysql_prefix."accounting where type='cash' and amount>0"));
        $total=$cash;
        echo "$".number_format($cash/100000/admin_cash_factor,2);
    ?>
    </td>
    </tr>
    <tr>
    <td>
        Cash Debits
    </td>
    <td align='right'>
    <?php
        list($cash)=@mysql_fetch_row(@mysql_query("select sum(amount) from ".mysql_prefix."accounting where type='cash' and amount<0"));
        $total=$total+$cash;
        echo "$".number_format($cash/100000/admin_cash_factor,2);
    ?>
    </td>
    </tr>
    <tr>
    <td>
        Grand Total
    </td>
    <td align='right'>
    <?php
        echo "$".number_format($total/100000/admin_cash_factor,2);
    ?>
    </td>
    </tr>
    <tr>
    <td colspan='2' align='center' bgcolor='#4E8CD1'>
        <span style='color:white' class='fsize2'>Point Totals</span>
    </td>
    </tr>
    <tr>
    <td>
        Point Credits
    </td>
    <td align=right>
    <?php
        list($points)=@mysql_fetch_row(@mysql_query("select sum(amount) from ".mysql_prefix."accounting where type='points' and amount>0"));
        $total=$points;
        echo number_format($points/100000,1);
    ?>
    </td>
    </tr>
    <tr>
    <td>
        Point Debits
    </td>
    <td align='right'>
    <?php
        list($points)=@mysql_fetch_row(@mysql_query("select sum(amount) from ".mysql_prefix."accounting where type='points' and amount<0"));
        $total=$total+$points;
        echo number_format($points/100000,1);
    ?>
    </td>
    </tr>
    <tr>
    <td>
        Grand Total
    </td>
    <td align='right'>
    <?php
        echo number_format($total/100000,1);
    ?>
    </td>
    </tr>
    </table>
</td>
<td align='center' valign='top'><span class='fsize4'>MySQL Stats</span>
<table border='1' cellpadding='2' cellspacing='0'>
<?php
if(phpversion() >= '4.3.0')      
{
    echo '<tr><td colspan="2" align="center" bgcolor="#4E8CD1"><span style="color:white" class="fsize2">MySQL Server Stats</span></td></tr>';
    $status = explode('  ', mysql_stat());
    foreach($status as $value)
    {
        list($key,$value) = explode(':',$value);
        echo '<tr><td>'.$key.'</td><td align="right">'.number_format($value).'</td></tr>';
    }
}
echo '<tr><td colspan="2" align="center" bgcolor="#4E8CD1"><span style="color:white" class="fsize2">Current Processes</span></td></tr>'; 
$result = @mysql_query('show processlist');
while ($row = mysql_fetch_assoc($result))
{
       echo '<tr><td>'.substr($row["Info"],0,16).'</td><td align="right">'.$row["Time"].'</td></tr>';
}
mysql_free_result ($result);
            $result      = @mysql_query('show table status');
                while ($row = @mysql_fetch_array($result)) {
                    $data += $row['Data_length'];
                    $index += $row['Index_length'];
                }
                $rows=@mysql_num_rows($result);
                $total=$data+$index; 
                mysql_free_result($result);
echo '<tr><td colspan="2" align="center" bgcolor="#4E8CD1"><span style="color:white" class="fsize2">Database Size</span></td></tr>';

echo '<tr><td>Tables</td><td align="right">'.number_format($rows).'</td></tr><tr><td>Data Bytes</td><td align="right">'.number_format($data).'</td></tr><tr><td>Index Bytes</td><td align="right">'.number_format($index).'</td></tr><tr><td>Total Bytes</td><td align="right">'.number_format($total).'</td></tr>';
?>
</table>
</td>
</tr>
</table>
</center>
<?php footer(); ?>

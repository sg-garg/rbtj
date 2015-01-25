<?
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='Delete Old Rotating Ads';
admin_login();
if (!isset($days)){$days=15;}
echo "<h3>Old Rotating Ads Report</h3><hr></center><form method=post>List ads that have that have not been shown in <input type=text name=days value=$days> days. <br><input type=submit name=report value=Report></form>";
if ($connum)
{
    echo "<br><br>";
    if ($connum!=$md5time){
    echo "Invalid confirmation ID. Nothing deleted";
    footer();
    }
    if ($uid){
    while (list($key, $value) = each($uid))
    {
        mysql_query("delete from ".mysql_prefix."rotating_ads where bannerid='$key'");
        echo "Deleted: $key<br>";
    }
    mysql_query("optimize table ".mysql_prefix."rotating_ads");
    }
}
if ($report)
{
    mysql_query("update ".mysql_prefix."rotating_ads set time=runad where runad>1");
    $report=mysql_query("select bannerid,description,time,run_type,run_quantity,views,clicks from ".mysql_prefix."rotating_ads where time < DATE_SUB(NOW(), INTERVAL $days DAY) and time != '0000-00-00 00:00:00' order by time desc");
    $md5time=substr(md5(unixtime),0,6);
    echo "<form method=post><input type=hidden name=md5time value=$md5time><br><br>To delete all checked ads below<br>enter this confirmation ID: <b>$md5time
    </b><br><br><a href=backup.php>Download a gzipped copy of your Mysql data</a><br><br><b>THIS WILL DELETE ALL CHECKED ADS BELOW. ONLY DO THIS IF YOU ARE SURE</b><br>Confirmation ID: <input type text name=connum><input type=submit value='Delete Now'><br>"; 
    echo "<table border=1 ><tr><td><b>Delete</b></td><td><b>Ad ID</b></td><td><b>Description</b></td><td><b>Type</b></td><td><b>Expires at</b></td><th>Views</th><th>Clicks</th><td><b>Last Shown</b>";
    while($row=@mysql_fetch_array($report))
    {
        if($bgcolor == ' class="row1" ')
        {
            $bgcolor=' class="row2" ';
        }
        else
        {
            $bgcolor=' class="row1" ';
        }
        if ($row[run_type]=='ongoing' or ($row[run_quantity]>$row[clicks] and $row[run_type]=='clicks') or ($row[run_quantity]>$row[views] and $row[run_type]=='views'))
        {
            $checked='';
        }  else {
            $checked='checked';
        }
        echo "</td></tr><tr $bgcolor><td><input type=checkbox class=checkbox name=\"uid[$row[bannerid]]\" value=1 $checked></td><td>$row[bannerid]</a></td><td>$row[description]</td><td>$row[run_type]</td><td>$row[run_quantity]</td><td>$row[views]</td><td>$row[clicks]</td><td>".mytimeread($row[time]);
    }
    echo "</td></tr></table></form>";
}
echo "</body>";

footer();


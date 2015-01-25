<?
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='Delete Old PTC Ads';
admin_login();

if (!isset($days)){$days=15;}
echo "<form method=post>List ads that have that have not been shown in <input type=text name=days value=$days> days. <br><input type=submit name=report value=Report></form>";
if ($connum){
echo "<br><br>";
if ($connum!=$md5time){
echo "Invalid confirmation ID. Nothing deleted";
footer();
}
if ($_POST['uid']){
foreach ($_POST['uid'] as $key => $value){
@mysql_query("delete from ".mysql_prefix."paid_clicks where id='$key'");
@mysql_query("delete from ".mysql_prefix."ptc_ads where ptcid='$key'");
echo "Deleted: $key<br>";
}
@mysql_query("optimize table ".mysql_prefix."email_ads");
}
}
if ($report){
$time=date("YmdHis",unixtime-($days*60*60*24));
$report=@mysql_query("select ptcid,description,time,run_type,run_quantity,views,clicks from ".mysql_prefix."ptc_ads where time<'$time' and description!='#PAID-START-PAGE#' order by time desc");
$md5time=substr(md5(unixtime),0,6);
echo "<form method=post><input type=hidden name=md5time value=$md5time><br><br>To delete all checked ads below<br>enter this confirmation ID: <b>$md5time
</b><br><br><a href=backup.php>Download a gzipped copy of your Mysql data</a><br><br><b>THIS WILL DELETE ALL CHECKED ADS BELOW. ONLY DO THIS IF YOU ARE SURE</b><br>Confirmation ID: <input type text name=connum><input type=submit value='Delete Now'><br>"; 
echo "<table border=1 ><tr><td><b>Delete</b></td><td><b>Ad ID</b></td><td><b>Description</b></td><td><b>Type</b></td><td><b>Expires at</b></td><th>Views</th><th>Clicks</th><td><b>Last Shown</b>";
while($row=@mysql_fetch_array($report)){
if ($row[run_type]=='ongoing' or ($row[run_quantity]>$row[clicks] and $row[run_type]=='clicks') or ($row[run_quantity]>$row[views] and $row[run_type]=='views')){
$checked='';} else {$checked='checked';}
echo "</td></tr><tr $bgcolor><td><input type=checkbox class=checkbox name=\"uid[$row[ptcid]]\" value=1 $checked></td><td>$row[ptcid]</a></td><td>$row[description]</td><td>$row[run_type]</td><td>$row[run_quantity]</td><td>$row[views]</td><td>$row[clicks]</td><td>".mytimeread($row[time]);
if($bgcolor){$bgcolor='';}else{$bgcolor='bgcolor=#DDDDDD';}
}
echo "</td></tr></table></form>";}
footer();

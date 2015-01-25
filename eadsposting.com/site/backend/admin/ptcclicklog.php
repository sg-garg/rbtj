<?
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='Click Log for PTC Ad '.$ptcid;
admin_login();

if ($connum){
$rollback=1;
if ($connum!=$md5time){
$rollback=0;
echo "Invalid confirmation ID. Nothing done";
}}
$md5time=substr(md5(unixtime),0,6);
$report=@mysql_query("select * from ".mysql_prefix."paid_clicks where id='$ptcid' order by value,time desc");
echo "<form method=post><input type=hidden name=ptcid value='$ptcid'><input type=hidden name=md5time value=$md5time><br><br>To rollback the clicks for this ad and remove all credits received from it<br>enter this confirmation ID: <b>$md5time</b><br><br><a href=backup.php>Download a gzipped copy of your Mysql data</a><br><br><b>THIS WILL CANCEL ALL CLICKS BELOW. ONLY DO THIS IF YOU ARE SURE. IF YOU ROLLBACK THIS AD BEFORE THE DAILY CLICKS ARE PROCESSED UPLINES WILL NOT RECEIVE CREDIT EITHER</b><br>Confirmation ID: <input type text name=connum><input type=submit value='Rollback Now'><br></form>";
echo "<table  border=1><tr><td><b>Username</b></td><td><b>Amount</b></td><td><b>Type</b></td><td><b>Date</b>";
while($row=@mysql_fetch_array($report)){
$cashfactor=1;
if ($row[vtype]=='cash'){
$cashfactor=admin_cash_factor;}
if ($rollback){
if ($row[value]){
@mysql_query("delete from ".mysql_prefix."clicks_to_process where username='$row[username]' and type='$row[vtype]' and amount=$row[value] limit 1");  
$row[value]=0-$row[value];
$update=@mysql_query("UPDATE ".mysql_prefix."accounting SET amount=amount+$row[value] WHERE type='$row[vtype]' and username = '$row[username]' and description='".ptcdescription."' limit 1");
@mysql_query("delete from ".mysql_prefix."paid_clicks  where id='$ptcid' and username='$row[username]'");
}
}
$row[time]=mytimeread($row[time]);

echo "</td></tr><tr $bgcolor><td><a href=viewuser.php?userid=$row[username] target=_viewuser>$row[username]</a></td><td align=right>".number_format($row[value]/100000/$cashfactor,5)."</td><td>$row[vtype]</td><td>$row[time]";
if($bgcolor){$bgcolor='';}else{$bgcolor='bgcolor=#DDDDDD';}
}
echo "</td></tr></table>";
footer();



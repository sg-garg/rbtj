<?
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='Accounts Possibly Using The Same PC';
admin_login();

if ($connum){
echo "<br><br>";
if ($connum!=$md5time){
echo "Invalid confirmation ID. Nothing canceled";
}
elseif ($uid){
while (list($key, $value) = each($uid)){
@mysql_query('update '.mysql_prefix.'users set account_type="canceled" where username="'.$key.'"');
echo "Canceled: $key<br>";
}
footer();
}
}
$report=@mysql_query("select time,ip_host,browser,count(*) as count from ".mysql_prefix."last_login,".mysql_prefix."users where ".mysql_prefix."users.username=".mysql_prefix."last_login.username and account_type!='canceled' and ip_host!='' group by ip_host,browser,time order by count desc");
$md5time=substr(md5(unixtime),0,6);
echo "<form method=post><input type=hidden name=md5time value=$md5time>To cancel all checked accounts below<br>enter this confirmation ID: <b>$md5time
</b><br><br><a href=backup.php>Download a gzipped copy of your Mysql data</a><br><br><b>THIS WILL CANCEL ALL CHECKED ACCOUNTS BELOW. ONLY DO THIS IF YOU ARE SURE</b><br>Confirmation ID: <input type text name=connum><input type=submit value='Cancel Now'><br>"; 
echo "<table border=1 ><tr><td><b>Cancel</b></td><td><b>Username, Computer ID, IP/PROXY, Browser</b>";
while($getlist=@mysql_fetch_array($report)){
if ($getlist[count]<2){
break;
}
$userreport=@mysql_query("select * from ".mysql_prefix."last_login,".mysql_prefix."users where ".mysql_prefix."users.username=".mysql_prefix."last_login.username and account_type!='canceled' and time='$getlist[time]' and ip_host='$getlist[ip_host]' and browser='".addslashes($getlist[browser])."'");
while($row=@mysql_fetch_array($userreport)){
$checked='';
if (!trim($row[computerid])){
$checked='checked';
$row[computerid]='This member blocked the cookie, Watch out for this one for sure!';}
echo "</td></tr><tr $bgcolor><td><input type=checkbox class=checkbox name=\"uid[$row[username]]\" value=1 $checked></td><td><table border=0 ><tr><th align=center>Username:</th><td><a href=viewuser.php?userid=$row[username] target=_viewuser>$row[username]</a></td></tr><tr><th align=center>Computer ID</th><td>$row[computerid]</td></tr><tr><th align=center>IP/Host</th><td>$row[ip_host]</td></tr><tr><th align=center>Browser:</th><td>$getlist[browser]</td></tr></table>";
if($bgcolor){$bgcolor='';}else{$bgcolor='bgcolor=#DDDDDD';}
}
}
echo "</td></tr></table></form>";
footer();


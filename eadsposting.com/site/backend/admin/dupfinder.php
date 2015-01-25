<?
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='Cheaters';
admin_login();

echo "The list of accounts below match so exactly they can only belong to cheaters. But of course use your own judgement<br>";
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
$report=@mysql_query("select password,pay_account,referrer,signup_ip_host,first_name,last_name,count(*) as count from ".mysql_prefix."users where account_type!='canceled' group by signup_ip_host,password,pay_account,referrer,first_name,last_name order by count desc");
$md5time=substr(md5(unixtime),0,6);
echo "<form method=post><input type=hidden name=md5time value=$md5time>To cancel all checked accounts below<br>enter this confirmation ID: <b>$md5time
</b><br><br><a href=backup.php>Download a gzipped copy of your Mysql data</a><br><br><b>THIS WILL CANCEL ALL CHECKED ACCOUNTS BELOW. ONLY DO THIS IF YOU ARE SURE</b><br>Confirmation ID: <input type text name=connum><input type=submit value='Cancel Now'><br>"; 
echo "<table border=1 ><tr><td><b>Cancel</b></td><td><b>Username</b></td><td><b>Encrypted Password</b></td><td><b>Referrer</b></td><td><b>Full Name</b></td><td><b>Payment Account</b></td><td><b>Signup IP/PROXY and Date</b>";
while($getlist=@mysql_fetch_array($report)){
if ($getlist[count]<2){
break;
}
$userreport=@mysql_query("select * from ".mysql_prefix."users where password='$getlist[password]' and pay_account='$getlist[pay_account]' and referrer='$getlist[referrer]' and account_type!='canceled' and signup_ip_host='$getlist[signup_ip_host]' and first_name='$getlist[first_name]' and last_name='$getlist[last_name]'");
while($row=@mysql_fetch_array($userreport)){
echo "</td></tr><tr $bgcolor><td><input type=checkbox class=checkbox name=\"uid[$row[username]]\" value=1 checked></td><td><a href=viewuser.php?userid=$row[username] target=_viewuser>$row[username]</a></td><td>$row[password]</td><td>$row[referrer]</td><td>$row[first_name] $row[last_name]</td><td>$row[pay_account]</td><td>$row[signup_ip_host]<br>$row[signup_date]";
if($bgcolor){$bgcolor='';}else{$bgcolor='bgcolor=#DDDDDD';}
}
}
echo "</td></tr></table></form>";
footer();

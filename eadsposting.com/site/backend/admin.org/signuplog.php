<?
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='Sign-up Log For Paid Sign-up Ad '.$ptsid;
admin_login();
echo "<h3>Sign-up Log For Paid Sign-up Ad $ptsid</h3><hr></center>";
$report=@mysql_query("select * from ".mysql_prefix."paid_signups_$ptsid order by time desc");
echo "<table  border=1><tr><td><b>Username</b></td><td><b>Date</b>";
while($row=@mysql_fetch_array($report)){
$row[time]=mytimeread($row[time]);

echo "</td></tr><tr $bgcolor><td><a href=viewuser.php?userid=$row[username] target=_viewuser>$row[username]</a></td><td>$row[time]";
if($bgcolor){$bgcolor='';}else{$bgcolor='bgcolor=#DDDDDD';}
}
echo "</td></tr></table>";

footer();

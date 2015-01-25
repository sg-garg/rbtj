<?
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='Convert Points To Cash';
admin_login();

if (system_value('convert points')){
echo "<br><br>Points conversion has already been started. You can not process another conversion untill this one is complete";
footer();}

if ($connum){
echo "<br><br>";
if ($connum==$md5time){
@mysql_query("replace into ".mysql_prefix."system_values set name='convert points',value='$cvalue'");
echo "Point conversion will be processed during the next Cron Job. You may now close this window";
footer();
}
}
echo "<form method=post>To calculate point value, enter the TOTAL cash amount you wish the TOTAL points to be converted to: <br><input type=text name=cash value='$cash'><input type=submit value=Report></form>";
if ($connum){
if ($connum!=$md5time){
echo "Invalid confirmation ID. Nothing done!";
footer();
}
}
if ($cash){
list($report)=@mysql_fetch_row(@mysql_query("select sum(amount) from ".mysql_prefix."accounting where type='points'"));
if ($report){
echo "<br><br>The current total of points: ".number_format($report/100000,5);
echo "<br>Will be converted to a cash value of: ".number_format($cash/($report/100000),5)." per point";
$md5time=substr(md5(unixtime),0,6);
echo "<form method=post><input type=hidden name=md5time value=$md5time><br><br>To convert all points to cash and reset points to zero<br>enter this confirmation ID: <b>$md5time
</b><br><br><a href=backup.php>Download a gzipped copy of your Mysql data</a><br><br><b>THIS CAN NOT BE UNDONE. ONLY DO THIS IF YOU ARE SURE</b><br>
Confirmation ID: <input type text name=connum><input type=hidden name=md5time value=$md5time><input type=hidden name=cvalue value=".$cash/($report/100000)."><input type=submit value='Convert Now'><br>";
} 
else {echo "<br><br>There are not points to convert";} 
}
echo "</form>";
footer();




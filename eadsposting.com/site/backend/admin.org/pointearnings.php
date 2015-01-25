<?
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='Point Earnings';
admin_login();

if (!isset($to)){$to=1000000;}
if (!isset($from)){$from=-1000000;}
echo "<form method=post>List all members with account balances ranging<br>from: <input type=text name=from value=$from> to: <input type=text name=to value=$to><input type=submit name=report value=Report></form>";
if ($report){
$f=$from*100000;
$t=$to*100000;
@mysql_query("drop table ".mysql_prefix."tmppointtbl");
@mysql_query("create table ".mysql_prefix."tmppointtbl (username char(16) not null, amount bigint not null, key amount(amount))");
@mysql_query("insert into ".mysql_prefix."tmppointtbl (username,amount) select username,sum(amount) from ".mysql_prefix."accounting where type='points' group by username");
$report=@mysql_query("select * from ".mysql_prefix."tmppointtbl,".mysql_prefix."users where ".mysql_prefix."users.username=".mysql_prefix."tmppointtbl.username and account_type!='canceled' and account_type!='suspended' and amount!=0 and amount>=$f and amount<=$t order by amount desc");
echo "<table border=1><tr><td><b>Username</b></td><td><b>Amount</b>";
while($row=@mysql_fetch_array($report)){
echo "</td></tr><tr><td><a href=viewuser.php?userid=$row[username]>$row[username]</a></td><td align=right>".number_format($row[amount]/100000,5);
}
echo "</td></tr></table>";}
@mysql_query("drop table ".mysql_prefix."tmppointtbl");
footer();

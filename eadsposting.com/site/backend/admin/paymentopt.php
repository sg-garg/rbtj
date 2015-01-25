<?
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='Manage Payment Selections';
admin_login();
if ($action=='Delete')
@mysql_query("delete from ".mysql_prefix."payment_types where type='$type'");
if ($action=='Save')
@mysql_query("replace  into ".mysql_prefix."payment_types set type='$type',currency='$currency',process_fee='$process_fee'");
if ($add)
@mysql_query("insert into ".mysql_prefix."payment_types set type='$add',currency='$currency',process_fee='$process_fee'"); 
$results=@mysql_query("select * from ".mysql_prefix."payment_types order by type");
echo "<table border=0><tr><form method=post><td>Payment type: </td><td><input type=text name=add></td></tr><tr><td>Currency ID: </td><td><input type=text name=currency></td></tr><tr><td>Flate Rate Processing Fee: </td><td><input type=text name=process_fee></td></tr></table><input type=submit value=Add></form><table border=1 >";
echo '<tr><td><b>Type</b></td><td><b>Currency ID</b></td><td><b>Processing Fee</b></td><td><b>Action</b></td></tr>';
while ($row=mysql_fetch_array($results)){
echo "<tr><form method=post><td>$row[type]</td><td><input type=text name=currency value=$row[currency]></td><td><input type=text name=process_fee value=$row[process_fee]></td><td><input type=submit name=action value=Save><input type=hidden name=type value=\"$row[type]\"><input type=submit name=action value='Delete'></td></tr></form>";}

echo "</table>";
footer(); 

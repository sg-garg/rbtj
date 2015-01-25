<?
require_once("functions.inc.php");
$title='License key and serial number';
admin_login();
if ($_POST[sysval]){
reset($_POST[sysval]);
while (list($key, $value) = each($_POST[sysval])){
if(!$value)
$value=' ';
$value=system_value($key,$value);
@mysql_query("replace into ".mysql_prefix."system_values set name='$key',value='".trim($value)."'");
}
if (md5($_POST['sysval']['domain'].'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5($_POST['sysval']['domain'].'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')==$_POST['sysval']['serialnumber']){
echo 'License information saved';
footer();}
} 
?>
Enter your site license key and serial number below.
<br>
<br>
To have your license key and serial number sent to your email address (webmaster@<? echo domain;?> & support@<? echo domain;?>), <a href=http://cashcrusadersoftware.com/license.php?domain=<? echo domain;?>>Click Here</a>
<table border=0><form method=post><tr><td align=right><input type=hidden name=admin_password value='<? echo $_SESSION[admin_password];?>'>
Domain Name: </td><td><input type=text size=30 name=sysval[domain] value='<? print system_value("domain");?>'></td></tr><tr><td align=right>
License Key: </td><td><input type=text size=64 name=sysval[key] value=<? echo system_value('key');?>></td></tr><tr><td align=right>
Serial Number: </td><td><input type=text size=64 name=sysval[serialnumber] value=<? echo system_value('serialnumber');?>></td></tr></table>
<input type=submit value=Save></form>
<? footer();

<?
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');

$title='List Your Site';
admin_login();

if ($_POST[sysval]){
reset($_POST[sysval]);
while (list($key, $value) = each($_POST[sysval])){
if(!$value)    
$value=' ';
$value=system_value($key,$value);
@mysql_query("replace into ".mysql_prefix."system_values set name='$key',value='".addslashes(trim($value))."'");
}}?>
<form method=post><table border=0>
<tr><td align=right>List your site at <a href=http://cashcrusadersoftware.com/customers.php target=_cc>CashCrusaderSoftware.com</a></td><td><select name=sysval[cclist]><option value='yes' <? if (system_value("cclist")=='yes'){ echo "selected";}?>>Yes
<option value='no' <? if (system_value("cclist")=='no'){ echo "selected";}?>>No
</select>
</td></tr></table>
<input type=submit value='Save Changes'></form>
<? footer();


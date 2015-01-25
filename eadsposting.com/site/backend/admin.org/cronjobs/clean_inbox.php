<?
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');

$cronjobs[]=array('classname'=>'cc_admin_clean_inbox');

class cc_admin_clean_inbox {

var $class_name='cc_admin_clean_inbox';
var $minutes=1440;

function cronjob(){

$inboxexpire=date("YmdHis",unixtime-(inboxexpire*86400)-86400);
cronjob_query("update ".mysql_prefix."user_inbox set mails=concat('#',SUBSTRING_INDEX(mails,'#',".inboxexpire.")) where username not like '-%'");
cronjob_query("update ".mysql_prefix."user_inbox set mails=concat('#',SUBSTRING_INDEX(mails,'#',30)) where username like '-%'");
cronjob_query("delete from ".mysql_prefix."inbox_mails where time<'$inboxexpire'");
cronjob_query("optimize table ".mysql_prefix."user_inbox'");
cronjob_query("optimize table ".mysql_prefix."inbox_mails'");


}
}

return;
?>

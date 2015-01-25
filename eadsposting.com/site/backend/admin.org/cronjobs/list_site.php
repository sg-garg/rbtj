<?
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');

$cronjobs[]=array('classname'=>'cc_admin_list_site');

class cc_admin_list_site {

var $class_name='cc_admin_list_site';
var $minutes=1440;

function cronjob(){

$fp = @fsockopen("cashcrusadersoftware.com", 80,$errno, $errstr, 10);
if($fp) {
$memcount=0;
if (cclist!='no'){
$memcount=@mysql_result(cronjob_query("select count(*) from ".mysql_prefix."users where account_type!='canceled' and account_type!='suspended'"),0,0);}
   socket_set_timeout($fp, 10);
   fputs($fp,"GET /news.php?domain_name=".system_value('domain')."&url=".pages_url."&count=$memcount&ver=".version."&pop3server=".pop3server." HTTP/1.0\r\nHost: cashcrusadersoftware.com\r\n\r\n");
   fclose($fp);
}

}
}

return;
?>

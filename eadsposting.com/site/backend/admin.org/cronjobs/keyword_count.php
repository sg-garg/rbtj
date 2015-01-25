<?
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');

$cronjobs[]=array('classname'=>'cc_admin_keyword_count');

class cc_admin_keyword_count {

var $class_name='cc_admin_keyword_count';
var $minutes=1440;

function cronjob(){
$result=cronjob_query('select keyword from '.mysql_prefix.'keywords');
while ($row=@mysql_fetch_row($result)){
$row[0]=addslashes(strtolower($row[0]));
cronjob_query('update '.mysql_prefix.'keywords set keycount='.
             mysql_result(
             cronjob_query('select count(*) from '.mysql_prefix.'interests,'.mysql_prefix.'users 
             where '.mysql_prefix.'users.username='.mysql_prefix.'interests.username and account_type!="canceled" and keywords like "%||'.$row[0].'||%"'),0,0).
                    ' where keyword="'.$row[0].'"');
}

}
}

return;
?>

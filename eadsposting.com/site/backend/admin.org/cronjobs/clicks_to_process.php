<?
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');

$cronjobs[]=array('classname'=>'cc_admin_clicks_to_process');

class cc_admin_clicks_to_process {

var $class_name='cc_admin_clicks_to_process';
var $minutes=1440;

function cronjob(){
if (!mysql_result(cronjob_query('select count(*) from '.mysql_prefix.'clicks_being_processed'),0,0)){
cronjob_query('LOCK TABLES '.mysql_prefix.'clicks_to_process,'.mysql_prefix.'cronjobs,'.mysql_prefix.'clicks_being_processed WRITE'); 
cronjob_query('insert into '.mysql_prefix.'clicks_being_processed (username,type,amount,count) select username,type,sum(amount),count(*) from '.mysql_prefix.'clicks_to_process group by username,type'); 
	if (mysql_affected_rows())
	@mysql_query('delete from '.mysql_prefix.'clicks_to_process'); 
@mysql_query("unlock tables"); 
}
}
}

return;
?>

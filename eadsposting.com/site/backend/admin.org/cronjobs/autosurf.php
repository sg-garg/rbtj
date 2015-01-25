<?
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');

$cronjobs[]=array('classname'=>'cc_admin_autosurf');

class cc_admin_autosurf {

var $class_name='cc_admin_autosurf';
var $minutes=5;

function cronjob(){

for ($i=1;$i<=4;$i++){
	
	if ($i>1)
	$AS=$i;

cronjob_query('delete from '.mysql_prefix.'autosurf'.$AS.' where url=""');

cronjob_query('update '.mysql_prefix.'autosurf'.$AS.' set time=runad,runad=0 where runad>1 and (active=0 or approved=0 or quantity<=hits)'); 

cronjob_query('update '.mysql_prefix.'autosurf'.$AS.' set runad=1 where runad=0 and active=1 and approved=1 and quantity>hits');  
}

}

}
return;
?>

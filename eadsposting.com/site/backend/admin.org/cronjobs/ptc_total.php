<?
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');

$cronjobs[]=array('classname'=>'cc_admin_ptc_total');

class cc_admin_ptc_total {

var $class_name='cc_admin_ptc_total';
var $minutes=5;

function cronjob(){

$count=@mysql_result(cronjob_query('select count(*) from ' . mysql_prefix . 'ptc_ads where description!="#PAID-START-PAGE#" and  ((run_quantity>clicks  and run_type="clicks") or (run_quantity>views and run_type="views") or run_type="ongoing" or (run_type="date" and run_quantity>='.mysqldate.'))'),0,0);

cronjob_query('replace '.mysql_prefix.'system_values set name="ptcads",value="'.$count.'"'); 
}
}

return;
?>

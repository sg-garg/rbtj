<?php
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');

$cronjobs[]=array('classname'=>'cc_admin_total_debits_points');

class cc_admin_total_debits_points {

var $class_name='cc_admin_total_debits_points';
var $minutes=1440;

function cronjob(){

    $points = mysql_result(cronjob_query('select sum(amount) from ' . mysql_prefix . 'accounting where amount<0 and type="points"'),0,0); 
    cronjob_query('replace into '.mysql_prefix.'system_values set name="totaldebits_points",value="'.$points.'"');

}
}

return;
?>

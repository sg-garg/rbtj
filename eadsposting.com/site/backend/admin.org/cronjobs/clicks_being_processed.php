<?
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');

$cronjobs[]=array('classname'=>'cc_admin_clicks_being_processed');

class cc_admin_clicks_being_processed {

var $class_name='cc_admin_clicks_being_processed';
var $minutes=5;

function cronjob(){

$result=cronjob_query('select * from '.mysql_prefix.'clicks_being_processed'); 
while($row=@mysql_fetch_array($result)){
cronjob_query('update '.mysql_prefix.'last_login set '.$row['type'].'_clicks='.$row['type'].'_clicks+'.$row['count'].' where username="'.$row['username'].'"'); 
cronjob_query('delete from '.mysql_prefix.'clicks_being_processed where username="'.$row['username'].'" and type="'.$row['type'].'"'); 
creditul($row['username'],$row['amount'],$row['type']);
}

}
}

return;
?>

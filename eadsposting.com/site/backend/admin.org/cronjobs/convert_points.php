<?
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');

$cronjobs[]=array('classname'=>'cc_admin_convert_points');

class cc_admin_convert_points {

var $class_name='cc_admin_convert_points';
var $minutes=5;

function cronjob(){

$pointstoconvert=trim(system_value("convert points"));
if ($pointstoconvert!=''){
$userlist=cronjob_query("select username from ".mysql_prefix."users");
while($row=@mysql_fetch_array($userlist)){
$pointsum=@mysql_result(cronjob_query("select sum(amount) from ".mysql_prefix."accounting where username='$row[username]' and type='points'"),0,0);
$points=$pointsum/100000;
$amount=$pointsum*admin_cash_factor*$pointstoconvert;
if ($amount){
cronjob_query('insert into '.mysql_prefix.'accounting set transid="'.maketransid($row[username]).'",username="'.$row[username].'",unixtime='.unixtime.',description="'.$points.' '.convertpoints.'",type="cash",amount="'.$amount.'"');
}
if (mysql_affected_rows()){
cronjob_query("delete from ".mysql_prefix."accounting where username='$row[username]' and type='points'");
}}
cronjob_query("delete from ".mysql_prefix."system_values where name='convert points'");
cronjob_query('optimize table '.mysql_prefix.'accounting');
}

}
}

return;
?>

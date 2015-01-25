<?
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');

$cronjobs[]=array('classname'=>'cc_admin_sessions');

class cc_admin_sessions {

var $class_name='cc_admin_sessions';
var $minutes=15;

function cronjob(){
$lastupdate=unixtime-3600;
cronjob_query('delete from '.mysql_prefix.'sessions where lastupdated<'.$lastupdate);
cronjob_query('optimize table '.mysql_prefix.'sessions');
if (@mysql_num_rows(cronjob_query('describe '.mysql_prefix.'sessions'))<=0){
	cronjob_query('drop table '.mysql_prefix.'sessions');
	cronjob_query("create table ".mysql_prefix."sessions(
		sessionid    char(64) not null,
		lastupdated  bigint not null,
		datavalue    blob not null,
		primary key (sessionid)
		)
		TYPE=MyISAM");
}
}
}

return;
?>

<?
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');

$cronjobs[]=array('classname'=>'cc_admin_clean_chat');

class cc_admin_clean_chat {

var $class_name='cc_admin_clean_chat';
var $minutes=60;

function cronjob(){
$lastupdate=unixtime-86400;
cronjob_query('delete from '.mysql_prefix.'chat_messages where time<'.$lastupdate);
cronjob_query('optimize table '.mysql_prefix.'chat_messages');
if (@mysql_num_rows(cronjob_query('describe '.mysql_prefix.'chat_messages'))<=0){
	cronjob_query('drop table '.mysql_prefix.'chat_messages');
cronjob_query("create table ".mysql_prefix."chat_messages (
  time bigint not null,
  user char(16) not null,
  color char(6) not null,
  message blob not null,
  room char(16) not null,
  key time(time),
  key room(room)
) TYPE=MyISAM");
}
}
}

return;
?>

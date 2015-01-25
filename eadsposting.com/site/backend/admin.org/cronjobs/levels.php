<?
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');

$cronjobs[]=array('classname'=>'cc_admin_levels');

class cc_admin_levels {

var $class_name='cc_admin_levels';
var $minutes=1440;

function cronjob(){

cronjob_query('update '.mysql_prefix.'users set upline="" where username=upline');
cronjob_query('update '.mysql_prefix.'users set referrer="" where username=referrer');

$result=cronjob_query('select '.mysql_prefix.'levels.username from '.mysql_prefix.'levels 
	left join '.mysql_prefix.'users on '.mysql_prefix.'users.username='.mysql_prefix.'levels.username 
	where account_type="canceled" or '.mysql_prefix.'users.username is NULL group by '.mysql_prefix.'levels.username');
while($row=@mysql_fetch_row($result))
	cronjob_query('delete from '.mysql_prefix.'levels where username="'.$row[0].'"');

@mysql_free_result($result);

$result=cronjob_query('select '.mysql_prefix.'levels.upline from '.mysql_prefix.'levels 
	left join '.mysql_prefix.'users on '.mysql_prefix.'users.username='.mysql_prefix.'levels.upline 
	where account_type="canceled" or '.mysql_prefix.'users.username is NULL group by '.mysql_prefix.'levels.upline');
while($row=@mysql_fetch_row($result))
	 cronjob_query('delete from '.mysql_prefix.'levels where upline="'.$row[0].'"');

@mysql_free_result($result);

$result=cronjob_query('select '.mysql_prefix.'users.upline from '.mysql_prefix.'users 
	left join '.mysql_prefix.'levels on 
	('.mysql_prefix.'users.username='.mysql_prefix.'levels.username 
	and '.mysql_prefix.'users.upline='.mysql_prefix.'levels.upline) 
	where account_type!="canceled"  and '.mysql_prefix.'users.upline!="" and '.mysql_prefix.'levels.username is null');
while($row=@mysql_fetch_row($result))
	cronjob_query('update '.mysql_prefix.'users set rebuild_stats_cache=1 where username="'.$row[0].'"'); 

@mysql_free_result($result);

cronjob_query('optimize table '.mysql_prefix.'levels');

if (@mysql_num_rows(cronjob_query('describe '.mysql_prefix.'levels'))<=0){
cronjob_query('drop table '.mysql_prefix.'levels');
cronjob_query("CREATE TABLE ".mysql_prefix."levels (
  username char(16) NOT NULL,
  upline char(16) NOT NULL,
  level int NOT NULL,
  unique uniqueness(username,upline),
  KEY username(username),
  KEY upline(upline),
  KEY level(level)
) TYPE=MyISAM");
cronjob_query('update '.mysql_prefix.'users set rebuild_stats_cache=1');
}
}
}

return;
?>

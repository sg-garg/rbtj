<?


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

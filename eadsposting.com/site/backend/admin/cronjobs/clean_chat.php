<?

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

<?

$cronjobs[]=array('classname'=>'cc_admin_access_log');

class cc_admin_access_log {

var $class_name='cc_admin_access_log';
var $minutes=1440;

function cronjob(){
cronjob_query('delete from '.mysql_prefix.'access_log where time<DATE_SUB(now(), INTERVAL 30 DAY)');
}
}

return;
?>

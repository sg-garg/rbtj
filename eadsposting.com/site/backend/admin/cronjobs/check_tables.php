<?


$cronjobs[]=array('classname'=>'cc_admin_check_tables');

class cc_admin_check_tables {

var $class_name='cc_admin_check_tables';
var $minutes=1440;

function cronjob(){

$getfields=cronjob_query('show tables');

while($fields=@mysql_fetch_row($getfields))
     if (@mysql_num_rows(cronjob_query('describe '.$fields[0]))<=0)
           cronjob_query('repair table '.$fields[0]);
}
}
return;
?>

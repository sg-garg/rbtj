<?

$cronjobs[]=array('classname'=>'cc_admin_rotatingads');

class cc_admin_rotatingads {

var $class_name='cc_admin_rotatingads';
var $minutes=5;

function cronjob(){

cronjob_query('update '.mysql_prefix.'rotating_ads set time=runad,runad=0 where runad>1 and ((run_type="clicks" and run_quantity<=clicks) or (run_quantity<=views and run_type="views") or (run_type="date" and run_quantity<='.mysqldate.'))'); 

cronjob_query('update '.mysql_prefix.'rotating_ads set runad=1 where runad=0 and ((run_type="clicks" and run_quantity>clicks) or (run_quantity>views and run_type="views") or run_type="ongoing" or (run_type="date" and run_quantity>'.mysqldate.'))');  

$count=@mysql_fetch_row(cronjob_query('select count(*) from '.mysql_prefix.'rotating_ads where runad>0'));
cronjob_query('replace '.mysql_prefix.'system_values set name="rotatingads",value="'.$count[0].'"'); 
}
}

return;
?>

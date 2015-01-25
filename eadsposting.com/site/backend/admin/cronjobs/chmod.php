<?


$cronjobs[]=array('classname'=>'cc_admin_chmod');

class cc_admin_chmod {

var $class_name='cc_admin_chmod';
var $minutes=30;

function cronjob(){

@chmod('../conf.inc.php',0600);

}

}
return;
?>

<?

$cronjobs[]=array('classname'=>'cc_admin_backup');
class cc_admin_backup {

var $class_name='cc_admin_backup';
var $minutes=15;

function cronjob(){
global $gz,$handle,$mysql_hostname,$mysql_database,$mysql_user,$mysql_password;
if (defined('bkauto') && bkauto>0 && !ini_get('safe_mode') && (defined('bkmail') || (defined('bkftpserver') && defined('bkftpuser') && defined('bkftppass'))) && lastautobk<unixtime-(bkauto*3600)){
cronjob_query("replace into ".mysql_prefix."system_values set name='lastautobk',value='".unixtime."'");
include_once('./backup.php');
}
}
}

return;
?>

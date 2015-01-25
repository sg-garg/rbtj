<?


$cronjobs[]=array('classname'=>'cc_admin_clean_inbox');

class cc_admin_clean_inbox {

var $class_name='cc_admin_clean_inbox';
var $minutes=1440;

function cronjob(){

$inboxexpire=date("YmdHis",unixtime-(inboxexpire*86400)-86400);
cronjob_query("update ".mysql_prefix."user_inbox set mails=concat('#',SUBSTRING_INDEX(mails,'#',".inboxexpire.")) where username not like '-%'");
cronjob_query("update ".mysql_prefix."user_inbox set mails=concat('#',SUBSTRING_INDEX(mails,'#',30)) where username like '-%'");
cronjob_query("delete from ".mysql_prefix."inbox_mails where time<'$inboxexpire'");
cronjob_query("optimize table ".mysql_prefix."user_inbox'");
cronjob_query("optimize table ".mysql_prefix."inbox_mails'");


}
}

return;
?>

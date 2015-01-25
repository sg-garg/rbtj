<?php


$cronjobs[]=array('classname'=>'cc_admin_expire_upgrades');

class cc_admin_expire_upgrades
{
    var $class_name='cc_admin_expire_upgrades';
    var $minutes=5;
    
    function cronjob()
    {
        $expire_query = mysql_query("SELECT * FROM `".mysql_prefix."users` WHERE upgrade_expires <= NOW() AND upgrade_expires != '0000-00-00 00:00:00' AND upgrade_expires != '00000000000000' AND account_type != 'suspended' AND account_type != 'canceled'");
        while($erow = mysql_fetch_array($expire_query))
        {
            echo date("H:i:s") . " ". $erow['username'] ."'s upgrade '". $erow['account_type'] ."' expired<br>\n";
            cronjob_query("update " . mysql_prefix . "users set account_type = '', upgrade_expires='0000-00-00 00:00:00', free_refs = '0', commission_amount = '0' WHERE username='". $erow['username'] ."' limit 1");
            $note = gmdate(timeformat) ." - Upgrade '".mysql_real_escape_string($erow['account_type'])."' expired";
            append_note("$note", "$erow[username]");
        }
    }
}

return;
?>

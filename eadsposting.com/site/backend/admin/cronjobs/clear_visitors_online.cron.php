<?php


$cronjobs[]=array('classname'=>'cc_admin_clear_visitors_online');

class cc_admin_clear_visitors_online 
{
    var $class_name='cc_admin_clear_visitors_online';
    var $minutes=5;
    
    function cronjob()
    {
        cronjob_query("DELETE FROM `".mysql_prefix."visitors_online` WHERE time < DATE_SUB(NOW(), INTERVAL 10 MINUTE)");
    }
}

return;
?>

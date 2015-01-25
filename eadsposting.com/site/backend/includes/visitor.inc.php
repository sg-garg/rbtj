<?php
if (!defined('version'))exit;

if (empty($_SESSION['visitorip']))
{
    if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    $_SESSION['visitorip'] = $ip;
} 

mysql_query("REPLACE INTO `".mysql_prefix."visitors_online`
             (time, username, ip, request, http_referer)
            VALUES
             (NOW(),'{$_SESSION['username']}', '".mysql_real_escape_string($_SESSION['visitorip'])."','".mysql_real_escape_string($_SERVER['REQUEST_URI'])."','". mysql_real_escape_string($_SERVER['HTTP_REFERER'])."')");

?>
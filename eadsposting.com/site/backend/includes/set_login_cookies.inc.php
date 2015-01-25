<?php
if (!defined('version')){
exit;}
       if ($_POST['username'] == 'LOGOUT' || $_GET['username'] == 'LOGOUT')
        { 
        setcookie('autoipsec', '', unixtime - 2592000, '/');
        setcookie('autousername', '', unixtime - 2592000, '/');
        setcookie('autopassword', '', unixtime - 2592000, '/');
        setcookie('domain', '', unixtime - 2592000, '/');
        $_COOKIE['autopassword']='';
        $_COOKIE['autousername']=''; 
        $logout=1;
        }
else {


    if ($_POST['username']) 
        {
                $realusername='';
        	if (strpos($_POST['username'],'@'))
        		$realusername=@mysql_result(@mysql_query('select username from '.mysql_prefix.'users where email="'.$_POST['username'].'"'),0,0);
        	if ($realusername)
        		$_POST['username']=$realusername; 
        
        $_POST['username']=strtolower(
            substr(preg_replace('([^a-zA-Z0-9])', '', $_POST['username']), 0, 16));
        }

    if ($_POST['admin_form'])
        {
        if ($_POST['admin_form']==2){
        setcookie('autoipsec', '', unixtime - 2592000, '/');
 	setcookie('autousername', '', unixtime - 2592000, '/');
 	setcookie('autopassword', '', unixtime - 2592000, '/');}
        $_COOKIE['autoipsec']=0;
        $_COOKIE['autopassword']=md5(password($_POST['password']).key);
        $_COOKIE['autousername']=$_POST['username'];
        }

    if ($_POST['username'] and !$_POST['admin_form'])
    {
        $cookietime=0;
        if ($_POST['autologin'])
            $cookietime=unixtime + 2592000;

        setcookie('autoipsec', $_POST['ipsec'], $cookietime, '/');
        setcookie('autousername', $_POST['username'], $cookietime, '/');
        setcookie('autopassword', md5(password($_POST['password']).key), $cookietime, '/');
        setcookie('domain', domain, $cookietime, '/');
        $_COOKIE['autopassword']=md5(password($_POST['password']).key);
        $_COOKIE['autousername']=$_POST['username'];
        }
    }
return 1;
?>

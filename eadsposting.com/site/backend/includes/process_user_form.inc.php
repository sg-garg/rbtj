<?php
if (!defined('version')){
exit;}
$dontprocess=0;
//print_r($userform);
if ($_POST['required'])
        $checkrequired = explode(',', $_POST['required']);


$_POST['required']=',' . $_POST['required'] . ',';

    if ($_POST['user_form'] == 'userinfo')
    {
        $dontprocess=0;

        if ($_POST['userform']['send_login_info'])
        {
            list($ln, $lp, $type)=@mysql_fetch_row(@mysql_query('select username,password, account_type from ' . mysql_prefix . 'users where account_type!="canceled" and email="'.$_POST['userform']['send_login_info'].'" limit 1'));

            if (!$ln)
            {
                include (pages_dir .pages. "email_not_found.php");
                exit;
            }
            if ($type == 'suspended')
            {
                include (pages_dir .pages. "suspended.php");
                exit;
            }
            //------------------------------------------------
            // Generate new password instead of sending hash of old - CC204
            //------------------------------------------------
            $lp = substr( md5( rand(1,32000) . $ln . key . rand(1,32000) ) ,0,10);
            mysql_query('UPDATE '.mysql_prefix.'users SET password="'.password($lp).'" WHERE username="'.$ln.'" LIMIT 1');

            $login_info_subject=str_replace('<PAGES_URL>',pages_url,str_replace('<SUPPORT_EMAIL>',support_email,str_replace('<SITE_NAME>',site_name,str_replace("<USERNAME>",
                                            $ln, str_replace("<PASSWORD>", $lp, $login_info_subject)))));
            $login_info_message=str_replace('<PAGES_URL>',pages_url,str_replace('<SUPPORT_EMAIL>',support_email,str_replace('<SITE_NAME>',site_name,str_replace("<USERNAME>",
                                            $ln, str_replace("<PASSWORD>", $lp, $login_info_message)))));
            sendmail($_POST['userform']['send_login_info'], $login_info_subject,
                 $login_info_message,
                 support_email);
            include (pages_dir . pages."login_info_sent.php");
            exit;
        }

        login();

        if ($_POST['userform']['alterinfo_password']==user('password','return')  || password($_POST['userform']['alterinfo_password'])==user('password','return'))
        {
 /*          for ($idx=0; $idx < count($checkrequired); $idx++)
            {
                if (!array_key_exists($checkrequired[$idx], $_POST['userform']))
                {
                        $dontprocess=1;
                        $form_errors[$checkrequired[$idx]]=1;
                }
            }
*/
            foreach($_POST['userform'] as $key => $value)
		    {
                if (!preg_match('/password/', $key))
                {
                    $value = str_replace('<', '', str_replace('>', '', $value));
                }
                else
                {
                    $value = trim($value);
                }

                if ($key == "confirm_password" || $key == "signup_date" || $key == "disable_turing" || $key=='commission_amount' || $key=='account_type' || $key == "signup_ip_host")
                    $dontprocess=1;
                
		        if ($key == "password" and $_POST['userform']['confirm_password'] != $value)
                {
                    $dontprocess=1;

                    $form_errors[$key]=1;
                }

                if (($key == "password") and !$value)
                {
                    $dontprocess=1;
                }
				

	    		if (preg_match("/," . $key . ",/", $_POST['required'])and !$value)
                { 
			            $dontprocess=1;
                        $form_errors[$key]=1;
                }

                if ($key == 'email')
                {
                    $value=str_replace(" ", "", $value);
					
                    if (!preg_match("/@/", $value) or !$value)
                    {
                        $dontprocess=1;
                        $form_errors[$key]=1;
                    }
					echo $value;
					
                    if (!$dontprocess and $value != user('email','return'))
                    {
					    echo 'test';
					
                        $dupcheck=@mysql_fetch_array(@mysql_query('select * from ' . mysql_prefix . 'users where email="'.$value.'" limit 1'));

                        if ($dupcheck['email'])
                        {
                            $form_errors[$key]=2;
                            $dontprocess=1;
                        }
                        else
                        {
                            //----------------------------------------------
                            // Email address verification -- CC205
                            // Skip, if we are admin -- CC206
                            //----------------------------------------------
                            if(!empty($_SESSION['admin_password'])) 
                            {
                                @mysql_query ("update " . mysql_prefix . "users set email='$value' where username='$_SESSION[username]' limit 1");
                                $userinfo['email']=$value;  
                            }else{
                                include('email_update.inc.php');
                            }
                            $dontprocess = 1;
                        }
                    }
                 }

                if (!$dontprocess and $value != user($key,'return') and $key != 'username')
                {
                    if ($key == 'password')
                    {
                        $userinfo['password'] = password($value);
                        @mysql_query ('update ' . mysql_prefix . 'users set password="'.password($value).'" where username="'.$_SESSION['username'].'" limit 1');
                    }
                    else
                    {
                        @mysql_query ("update " . mysql_prefix . "users set $key='".addslashes($value)."' where username='$_SESSION[username]' limit 1");

                        $userinfo[$key]=$value;
                    }
                }
                $dontprocess=0;
            }
        }
        else
        {
            $dontprocess=1;
            $form_errors['alterinfo_password']=1;
        }
    }



return 1;
?>

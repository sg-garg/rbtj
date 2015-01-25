<?php

$cronjobs[] = array('classname' => 'cc_admin_send_mail');
     
class cc_admin_send_mail 
{
    var $class_name = 'cc_admin_send_mail';
    var $minutes = 4; 
         
    function cronjob() 
    {
        $spamsafety=10;
        ini_set(sendmail_from, massmail_email); 
  	    if (spamsafety=='no')
        {
	        $spamsafety=1000;
        }
        
        if ((int)mailsperhour > 0)
        {
            $mailsleeptime = 3600/ (int)mailsperhour;
        }
        
        $curdate = gmdate("Y-m-d", unixtime);
        $massmail = mysql_fetch_array(cronjob_query("SELECT * FROM `".mysql_prefix."mass_mailer` WHERE `current`>1 AND `stop`>0 AND (`current`<`stop` OR `current`=1) ORDER BY `time` desc LIMIT 1"));

        if (!$massmail['massmailid'])
            $massmail = mysql_fetch_array(cronjob_query("SELECT * FROM `".mysql_prefix."mass_mailer` WHERE `current`>0 AND `stop`>0 AND (`current`<`stop` OR `current`=1) ORDER BY `time` LIMIT 1"));

        if (!$massmail[0])
        {
            echo date("H:i:s") . " no massmails to send<br>\n";
            return;
        }

        echo date("H:i:s") . " sending massmail id ". $massmail['massmailid']. "<br>\n";

        $includes = explode('</INCLUDE>', $massmail['ad_text']);
        $incprocess='';
        $inc_count = count($includes);
        for ($incidx = 0; $incidx < $inc_count; $incidx++) 
        {
            $getincurl = explode('<INCLUDE>',$includes[$incidx]);
            $incprocess.=$getincurl[0];
            //----------------------------------------------
            // Do not try to include unreadable file - CC217
            //----------------------------------------------
            if(is_readable($getincurl[1]))
            {
                $incprocess.=file_get_contents($getincurl[1]);
            }
        }          
     
        $plines = explode('</PM>', $incprocess);
        $emailtosend = '';
        $inbox_message = '';
        $plines_count = count($plines);
        for ($pidx = 0; $pidx < $plines_count; $pidx++) 
        {
            $getpmid = explode('<PM>', $plines[$pidx]);
            if ($massmail['is_html'] != 'Y') 
            {
                $inbox_message.=str_replace("\n", "<br>", str_replace("\r\n", "<br>", $getpmid[0]));
            }
            else 
            {
                $inbox_message.=$getpmid[0];
            }
                
            $emailtosend.=$getpmid[0];
            if ($getpmid[1]) 
            {
                $getad = mysql_fetch_array(cronjob_query("select * from ".mysql_prefix."email_ads where emailid=$getpmid[1]"));
                cronjob_query("update ".mysql_prefix."email_ads set last_sent=NOW() where emailid=$getpmid[1]");
            }
            if ($getad[0]) 
            {
                if ($massmail['is_html'] == 'Y') 
                {
                    $htmlad = "<br><table border=0 cellpadding=0 cellspacing=0><tr><td>".$getad['ad_text']."</td></tr></table>\n<br><a href=\"".runner_url."?EA=".$getad['emailid']."\">".runner_url."?EA=".$getad['emailid']."</a><br>";
                    $emailtosend.=$htmlad;
                    $inbox_message.=$htmlad;
                } else {
                    $emailtosend.="\n".$getad['ad_text']."\n\n".runner_url."?EA=".$getad['emailid']."\n\n";
                    if(!defined('aollink'))
                        define('aollink', 'yes');
                    if(aollink == 'yes')
                        $emailtosend .= "<a href=\"".runner_url."?EA=".$getad['emailid']."\">AOL Users</a>\n";
                    $inbox_message.=str_replace("\n", "<br>", str_replace("\r\n", "<br>", $getad['ad_text']))."<br><br><a href=\"".runner_url."?EA=".$getad['emailid']."\">".runner_url."?EA=".$getad['emailid']."</a><br>";
                }
                $getad = '';
            }
        }
            
        cronjob_query('replace into '.mysql_prefix.'inbox_mails set id="'.$massmail['massmailid'].'",subject="'.addslashes($massmail['subject']).'",message="'.addslashes($inbox_message).'"');
        if (substr($massmail['subject'], 0, 2) == "! ") 
        {
            $massmail['subject'] = substr($massmail['subject'], 2, strlen($massmail['subject'])-2);
            $high = "X-Priority: 1\nImportance: High\n";
        } else {
             $high = "X-Priority: 3\n";
        }

        if ($massmail['charset']!=charset && function_exists('mb_internal_encoding'))
        {
      	    mb_internal_encoding(charset);
            $site_name = mb_convert_encoding($site_name,$massmail['charset']);
        }

        //------------------------------
        // For better SpamAssassin score
        // "FROM_EXCESS_BASE64"  - CC222
        //------------------------------
        if(!is_ascii(site_name))
        {
            $site_name = base64_encode(site_name);
            $site_name = '=?'.$massmail['charset'].'?B?'.$site_name.'?=';
        } else {
            $site_name = site_name;
        }

        $bit=7;
        if (strtolower($massmail['charset'])=='utf-8')
	    $bit=8;
        if ($massmail['is_html'] == 'Y')
            $high = $high."MIME-Version: 1.0\nContent-Type: text/html; charset=".$massmail['charset']."\nContent-Transfer-Encoding: ".$bit."bit\nContent-Disposition: inline\n";
        else
            $high = $high."MIME-Version: 1.0\nContent-Type: text/plain; charset=".$massmail['charset']."\nContent-Transfer-Encoding: ".$bit."bit\n";
             
             
        $massmail['stop'] = $massmail['stop']-$massmail['current']+1;
        $sendasbcc = 0;
        $comma = '';
        $bcccount = 0;
        if(preg_match('/<OWED>/', $massmail['subject']) || preg_match('/<OWED>/', $emailtosend))
            $containsowed = 1;
        if(preg_match("[<OWED>|<CASH_BALANCE>]", $massmail['subject']) or preg_match("[<OWED>|<CASH_BALANCE>]", $emailtosend)) 
            $cash_balance = 1;
            
        if(preg_match("/<POINT_BALANCE>/", $massmail['subject']) or preg_match("/<POINT_BALANCE>/", $emailtosend)) 
            $point_balance = 1;
            
        if (!preg_match("[<ENCRYPTEDPW>|<OWED>|<CASH_BALANCE>|<POINT_BALANCE>|<USERNAME>|<FIRSTNAME>|<LASTNAME>|<EMAIL>]", $massmail['subject']) and !preg_match("[<ENCRYPTEDPW>|<OWED>|<CASH_BALANCE>|<POINT_BALANCE>|<USERNAME>|<FIRSTNAME>|<LASTNAME>|<EMAIL>]", $emailtosend) and !$mailsleeptime) 
            $sendasbcc = 1;
            
        $keywords = explode('||', $massmail['keywords']);
        $keyword_count = count($keywords);
        for ($ki = 0; $ki < $keyword_count; $ki++) 
        {
            $value = addslashes(trim($keywords[$ki]));
            if ($value) 
            {
                if (substr($value, 0, 2) != "g:" and substr($value, 0, 2) != "c:" and substr($value, 0, 2) != "s:") 
                {
                    $words = $words.$and." keywords like '%||$value||%'";
                    $and = ' and';
                } else 
                {
                    if (substr($value, 0, 2) == "s:") 
                    {
                        $value = str_replace('s:', '', $value);
                        $states = $states.$sor." state='$value'";
                        $sor = ' or';
                    }
                    if (substr($value, 0, 2) == "c:") 
                    {
                        $value = str_replace("c:", "", $value);
                        $countries = $countries.$cor." country='$value'";
                        $cor = " or";
                    }
                    if (substr($value, 0, 2) == "g:") 
                    {
                        $value = str_replace("g:", "", $value);
                        if ($value == 'inactive') 
                        {
                                $mailinactive = 1;
                                continue;
                        }
                        if ($value == 'inactives_only') 
                        {
                                $inactives_only = 1;
                                continue;
                        }
                        if ($value == 'free') 
                        {
                                $value = '';
                        }
                        $membership = $membership.$mor." account_type='$value'";
                        $mor = " or";
                    }
                }
            }
        }
        if ($words or $countries or $states or $membership) 
        {
            if ($words) 
                $words = 'and '.$words;
            
            if ($states && $countries) 
            {
                $states = 'and ('.$states.' or ';
                $countries = $countries.')';
            } else 
            {
                if ($states) 
                {
                    if ($sor) 
                        $states = 'and ('.$states.')';
                    else 
                        $states = 'and '.$states;
                        
                 }
                if ($countries) 
                {
                    if ($cor) 
                        $countries = 'and ('.$countries.')';
                    else 
                        $countries = 'and '.$countries;       
                }
            }
            if ($membership) 
            {
                if ($mor) 
                    $membership = 'and ('.$membership.')';
                else 
                    $membership = 'and '.$membership;
            }
            $states = strtolower($states);
            $countries = strtolower($countries);
            $membership = strtolower($membership);



            if (!isset($mailinactive) && defined('nocreditdays') && nocreditdays > 0)
            {
                $activepart1 = "LEFT JOIN ".mysql_prefix."last_login ON (".mysql_prefix."users.username=".mysql_prefix."last_login.username) ";
                $activepart2 = ' time>='.gmdate("YmdH", unixtime-(nocreditdays * 86400)).' and ';
            }
            //--------------------------------------------------
            // Massmail inactives only - CC211
            //--------------------------------------------------
            if ($inactives_only == 1 && defined('nocreditdays') && nocreditdays > 0)
            {
                $activepart1 = "LEFT JOIN ".mysql_prefix."last_login ON (".mysql_prefix."users.username=".mysql_prefix."last_login.username) ";
                $activepart2 = ' (`time`<'.gmdate("YmdH",unixtime-(nocreditdays*86400)).' OR `time` is null ) and ';
            }
            $users = cronjob_query("
                                SELECT signup_date,signup_ip_host,password,first_name,last_name,email,email_setting,".mysql_prefix."users.username FROM ".mysql_prefix."users
                                $activepart1
                                LEFT JOIN ".mysql_prefix."interests ON (".mysql_prefix."users.username=".mysql_prefix."interests.username) 
                                LEFT JOIN ".mysql_prefix."user_inbox on (".mysql_prefix."user_inbox.username=concat('-',".mysql_prefix."users.username)) 
                                WHERE $activepart2 (mails NOT LIKE concat('%|',".$massmail['massmailid'].",'.%') OR ".mysql_prefix."user_inbox.username is null) AND email_setting>=0 AND account_type!='canceled' AND vacation<'$curdate' $words $states $countries $membership ORDER BY last_email LIMIT ".$massmail['stop']);

            } else {
                $users = cronjob_query("select signup_date,signup_ip_host,password,first_name,last_name,email,email_setting,".mysql_prefix."users.username from ".mysql_prefix."users left join ".mysql_prefix."user_inbox on (".mysql_prefix."user_inbox.username=concat('-',".mysql_prefix."users.username)) where email_setting>=0 and (mails not like concat('%|',".$massmail['massmailid'].",'.%') or ".mysql_prefix."user_inbox.username is null) and account_type!='canceled' and vacation<'$curdate' order by last_email limit ".$massmail['stop']);
            }
            //--------------------------------------------------
            // Massmail has too many receivers? - CC211
            //--------------------------------------------------
            if(@mysql_num_rows($users) == 0)
            {
                cronjob_query("UPDATE `".mysql_prefix."mass_mailer` SET `stop` = `current` - 1 WHERE massmailid = '$massmail[massmailid]' LIMIT 1");
            }else{
                $receivers = @mysql_num_rows($users);
                $toofew = $massmail['stop'] - $receivers;
                if($toofew > 0)
                    cronjob_query("UPDATE `".mysql_prefix."mass_mailer` SET `stop` = `stop` - $toofew WHERE massmailid = '$massmail[massmailid]' LIMIT 1");
            }
            while ($user = mysql_fetch_array($users)) 
            {
                $message = $emailtosend;
                $subject = $massmail['subject'];
                $user['email'] = trim($user['email']);
                if ($cash_balance and !$massmail['inboxonly']) 
                {
                    list($cash) = mysql_fetch_row(cronjob_query('select sum(amount) from '.mysql_prefix.'accounting where username="'.$user['username'].'" and type="cash"'));
                    $cash = $cash/100000/admin_cash_factor;
                    if ($cash < 0) {
                        $owed = $cash * -1;
                    } else {
                        $owed = 0;
                    }
                }
                if ($point_balance and !$massmail['inboxonly']) {
                    list($points) = mysql_fetch_row(cronjob_query('select sum(amount) from '.mysql_prefix.'accounting where username="'.$user['username'].'" and type="points"'));
                    $points = $points/100000;
                }
                if (!$sendasbcc and !$massmail['inboxonly']) 
                {
                    if (preg_match("/@/", $user['email']) and !preg_match("/:/", $user['email'])) 
                    {
                        $subject = str_replace('<OWED>', $owed, $subject);
                        $message = str_replace('<OWED>', $owed, $message);
                        $subject = str_replace('<CASH_BALANCE>', $cash, $subject);
                        $message = str_replace('<CASH_BALANCE>', $cash, $message);
                        $subject = str_replace('<POINT_BALANCE>', $points, $subject);
                        $message = str_replace('<POINT_BALANCE>', $points, $message);
                        $subject = str_replace('<USERNAME>', $user['username'], $subject);
                        $message = str_replace('<USERNAME>', $user['username'], $message);
                        $subject = str_replace('<ENCRYPTEDPW>', $user['password'], $subject);
                        $message = str_replace('<ENCRYPTEDPW>', $user['password'], $message);
                        $subject = str_replace('<SIGNUPDATE>', $user['signup_date'], $subject);
                        $message = str_replace('<SIGNUPDATE>', $user['signup_date'], $message);
                        $subject = str_replace('<SIGNUPIPPROXY>', $user['signup_ip_host'], $subject);
                        $message = str_replace('<SIGNUPIPPROXY>', $user['signup_ip_host'], $message);
                        $message = str_replace('<FIRSTNAME>', $user['first_name'], $message);
                        $message = str_replace('<LASTNAME>', $user['last_name'], $message);
                        $subject = str_replace('<FIRSTNAME>', $user['first_name'], $subject);
                        $subject = str_replace('<LASTNAME>', $user['last_name'], $subject);
                        $subject = str_replace('<EMAIL>', $user['email'], $subject);
                        $message = str_replace('<EMAIL>', $user['email'], $message);
                        if (!$containsowed || skiponowe != 'yes' || $owed) 
                        {
                            mysql_query('insert into '.mysql_prefix.'user_inbox set username="-'.$user['username'].'"');
                            mysql_query('update '.mysql_prefix.'user_inbox set mails=concat("|",'.$massmail['massmailid'].',".",mails) where  username="-'.$user['username'].'"');
				            mysql_query('update '.mysql_prefix.'users set last_email='.unixtime.' where username="'.$user['username'].'"');
                            if ($user['email_setting'] == 0 || $user['email_setting'] == 2) 
                            {
                                mysql_query('insert into '.mysql_prefix.'user_inbox set username="'.$user['username'].'"');
                                mysql_query('update '.mysql_prefix.'user_inbox set mails=concat("|",'.$massmail['massmailid'].',".",mails) where  username="'.$user['username'].'" and mails not like "%|'.$massmail['massmailid'].'.%"');
				            }
                            if ($user['email_setting'] > 0) 
                            {
                                sendmassmail($user['email'], trim($subject), trim($message), "Return-Path: ".massmail_email."\nFrom: \"".$site_name."\" <".system_value("massmail_email").">\nReply-To: ".system_value("massmail_email")."\nX-Mailer: Cash Crusader\n".$high,$massmail['charset']);
                                list($junk, $emaildomain) = explode("@", $user['email']);
                                $emaildomainct[$emaildomain]++;
                            }
                        }
                        cronjob_query("update ".mysql_prefix."mass_mailer set time=NOW(),current=current+1 where massmailid=".$massmail['massmailid']);
                    }
                    if ($mailsleeptime)
                        sleep($mailsleeptime);
                    if ($emaildomainct[$emaildomain] >= $spamsafety && $spamsafety == 10) 
                    {
                        sleep(2);
                        $emaildomainct = "";
                    }
                }
                else 
                {
                    if (preg_match("/@/", $user['email']) and !preg_match("/:/", $user['email'])) 
                    {
	                    mysql_query('insert into '.mysql_prefix.'user_inbox set username="-'.$user['username'].'"');
                        mysql_query('update '.mysql_prefix.'user_inbox set mails=concat("|",'.$massmail['massmailid'].',".",mails) where  username="-'.$user['username'].'"');
                        mysql_query('update '.mysql_prefix.'users set last_email='.unixtime.' where username="'.$user['username'].'"');
                        if ($user['email_setting'] == 0 || $user['email_setting'] == 2) 
                        {
                            mysql_query('insert into '.mysql_prefix.'user_inbox set username="'.$user['username'].'"');
                            mysql_query('update '.mysql_prefix.'user_inbox set mails=concat("|",'.$massmail['massmailid'].',".",mails) where  username="'.$user['username'].'" and mails not like "%|'.$massmail['massmailid'].'.%"');
			            }
                        if ($user['email_setting'] > 0) 
                        {
                            $bcc = $bcc.$comma.$user['email'];
                            list($junk, $emaildomain) = explode("@", $user['email']);
                            $emaildomainct[$emaildomain]++;
                        }
                        if ($emaildomainct[$emaildomain] >= $spamsafety) 
                            $stopbcc = 1;
                        
                        $comma = ", ";
                    }
                    $bcccount++;
                    if ($bcccount >= 1000 or $stopbcc) 
                    {
                        $emaildomainct = '';
                        $stopbcc = 0;
                         
                        if (!$massmail['inboxonly']) 
                            sendmassmail(system_value("massmail_to"), trim($subject), trim($message), "Return-Path: ".massmail_email."\nFrom: \"".$site_name."\" <".system_value("massmail_email").">\nReply-To: ".system_value("massmail_email")."\nX-Mailer: Cash Crusader\n".$high."Bcc: $bcc\n",$massmail['charset']);
                        
                        cronjob_query('update '.mysql_prefix.'mass_mailer set time=NOW(),current=current+'.$bcccount.' where massmailid='.$massmail['massmailid']);
                        $bcccount = 0;
                        $bcc = "";
                        $comma = "";
                        if ($spamsafety == 10)
                            sleep(5);
                    }
                }
            }
            if (trim($bcc)) 
                if (!$massmail['inboxonly']) 
                    sendmassmail(system_value("massmail_to"), trim($subject), trim($message), "Return-Path: ".massmail_email."\nFrom: \"".$site_name."\" <".system_value("massmail_email").">\nReply-To: ".system_value("massmail_email")."\nX-Mailer: CashCrusader\n".$high."Bcc: $bcc\n",$massmail['charset']);
	   
	        cronjob_query('update '.mysql_prefix.'mass_mailer set  time=NOW(),current=current+'.$bcccount.' where massmailid='.$massmail['massmailid']);
            cronjob_query('replace into '.mysql_prefix.'system_values (name,value) select "mailqueue",count(*) from '.mysql_prefix. 'mass_mailer where current>0 and current<stop');
        }
    }
    return;
?>

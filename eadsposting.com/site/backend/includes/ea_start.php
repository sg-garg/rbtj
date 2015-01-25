<?php
if (!defined('version'))
   exit;

$emailad = @mysql_fetch_array(@mysql_query('select *,last_sent+0 as ts_last_sent from ' . mysql_prefix . 'email_ads where emailid LIKE \''.$_GET['EA'].'\' limit 1'));

if (!$emailad[0])
{
    include (pages_dir .pages. 'invalid_paid_mail.php');
    exit;
}
//-----------------------------------------
// Enable usernames in email ad url - CC208
//-----------------------------------------
$emailad['site_url'] = str_replace("#USERNAME#", $_SESSION['username'], $emailad['site_url']);

$expireurl = $emailad['site_url'];
if (($emailad['clicks'] >= $emailad['run_quantity'] && $emailad['run_type']
            == 'clicks') or (mysqldate
            >= $emailad['run_quantity'] && $emailad['run_type']
            == 'date'))
{
    include (pages_dir . pages.'expired_paid_mail.php');
    exit;
}

if ($emailad['login'] == 'on')
{
    $_SESSION['turing_time']=1;
}
login('turing'); 

if (timer_lock == 'YES' && !$_SESSION['EA'.$_GET['EA']]['VT'])
{
    if (@mysql_result(@mysql_query('select last_click from '.mysql_prefix.'last_login where username="'.$_SESSION['username'].'"'),0,0)>unixtime)
    {
        header ('Location: '.pages_url.pages.'clicked_too_soon.php'); 
        exit;
    }
}
 
if ($emailad['cheat_link']!=1)
{
    if ($emailad['ts_last_sent']<user('ts_signup_date','return') && $emailad['ts_last_sent']!='00000000000000')
    {
        include (pages_dir . pages.'expired_paid_mail.php');
        exit;
    }

    $clickcheck=@mysql_fetch_array(@mysql_query('select * from ' . mysql_prefix . 'paid_clicks_'.$_GET['EA'].' where username="'.$_SESSION[username].'" limit 1'));
    if ($clickcheck[username]==$_SESSION[username])
    {
        include (pages_dir .pages. 'already_credited.php');
        exit;
    }
}
if ($emailad[timer])
{
    $_SESSION['EA'.$_GET[EA]][cheat_link]=$emailad[cheat_link];

	if (timer_lock == 'YES')
       @mysql_query('update '.mysql_prefix.'last_login set last_click='.unixtime.'+'.$emailad['timer'].'-7 where username="'.$_SESSION['username'].'"');

        $_SESSION['EA'.$_GET[EA]][VT]=$emailad[vtype];
        $_SESSION['EA'.$_GET[EA]][TI]=$emailad[timer];
        $_SESSION['EA'.$_GET[EA]][VA]=$emailad[value];
        //-----------------------------------------
        // Timer link session variables - CC219
        //-----------------------------------------
        $_SESSION['abuse_report']['type'] = 'email';
        $_SESSION['abuse_report']['ad'] = $_GET['EA'];
        $_SESSION['abuse_report']['url'] = $emailad['site_url'];

        //-----------------------------------------
        // Timer bar location mod - CC219
        //-----------------------------------------
        if(!defined('timerbar_location'))
        {
            define('timerbar_location', 0);
        }

        switch(timerbar_location)
        {
            case 0:
                $timerbar_location = 'top';
                break;
            case 1:
                $timerbar_location = 'bottom';
                break;
            case 2:
                if(mt_rand(0,1))
                {
                    $timerbar_location = 'top';
                }else{
                    $timerbar_location = 'bottom';
                }
                break;
            default:
                $timerbar_location = 'top';
        }
        
        if($timerbar_location == 'top')
        {
            echo
                '<script>window.focus()</script>
                <frameset framespacing=0 frameborder=1 border=1 rows="'.frame_size.',1*">  
                    <frame marginwidth=0 marginheight=0 name=timerfrm src="' . runner_url . '?EA='.$_GET['EA'].'&amp;FR=1'.add_session().'" scrolling=no> 
                    <frame name=main src="' . runner_url . '?REDIRECT=' . rawurlencode($emailad['site_url']). '&amp;hash='.md5($emailad['site_url'].key).'">
                </frameset> 
                <noframes>  
                <body>
                This page uses frames, but your browser doesn\'t support them. 
                </body> 
                </noframes>
                </frameset>';
            exit;
        }

        if($timerbar_location == 'bottom')
        {
            echo
                '<script>window.focus()</script>
                <frameset framespacing=0 frameborder=1 border=1 rows="1*,'.frame_size.'">  
                    <frame name=main src=' . runner_url . '?REDIRECT=' . rawurlencode($emailad['site_url']). '&hash='.md5($emailad['site_url'].key).'>
                    <frame marginwidth=0 marginheight=0 name=timerfrm src=' . runner_url . '?EA='.$_GET['EA'].'&FR=1'.add_session().' scrolling=no> 
                </frameset> 
                <noframes>  
                <body>
                This page uses frames, but your browser doesn\'t support them. 
                </body> 
                </noframes>
                </frameset>';
            exit;
        }
}

        @mysql_query ("insert into " . mysql_prefix . "paid_clicks_$_GET[EA] set username='$_SESSION[username]',value='$emailad[value]',vtype='$emailad[vtype]'");

if ($emailad['cheat_link']==1)
{
    @mysql_query ('update '. mysql_prefix .'last_login set cheat_links=cheat_links+1 where username="'.$_SESSION['username'].'"');
    $note = gmdate(timeformat) ." - Cheat click, email id: ". $_GET['EA'];
    append_note($note, $_SESSION['username']); 
}
        @mysql_query ("update " . mysql_prefix . "email_ads set clicks=clicks+1 where emailid=$_GET[EA]");
        $update
            =@mysql_query("UPDATE " . mysql_prefix . "latest_stats set time=concat('" . mysqldate . ",',time),type=concat('paidmail,',type),id=concat('$_GET[EA],',id) where username='$_SESSION[username]' limit 1");

        if (!mysql_affected_rows())
            {
            @mysql_query ("insert into " . mysql_prefix . "latest_stats set time='".mysqldate."',type='paidmail',id='$_GET[EA]',username='$_SESSION[username]'");
            }

        if ($emailad[value])
            {
			$emailad[value] = number_format($emailad[value],0,'',''); // Circumvent PHP5 bug http://bugs.php.net/bug.php?id=43053

            $update = @mysql_query("UPDATE " . mysql_prefix . "accounting SET amount=amount+".$emailad[value]." WHERE type='$emailad[vtype]' and username = '$_SESSION[username]' and description='".pmdescription."' limit 1");

            if (!mysql_affected_rows())
                {
                $update = @mysql_query('insert INTO ' . mysql_prefix . 'accounting set transid="'.maketransid($_SESSION[username]).'",username = "'.$_SESSION[username].'",unixtime=0,description="'.pmdescription.'",amount='.$emailad[value].',type="'.$emailad[vtype].'"');
                }

            if ($emailad[value] > 0)
                {
                creditulclicks($_SESSION[username], $emailad[value], $emailad[vtype]);
                }
            }

        header ('Location: ' . runner_url . '?hash='.md5($emailad['site_url'].key).'&REDIRECT=' . rawurlencode(
            $emailad[site_url]));
        exit;
?>
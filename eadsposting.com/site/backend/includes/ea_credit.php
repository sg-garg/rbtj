<?php
if (!defined('version'))
    exit;

if ($_SESSION['EA'.$_GET[EA]][cheat_link]!=1)
{
    $clickcheck = @mysql_fetch_array(@mysql_query('select username from ' . mysql_prefix . 'paid_clicks_' . $_GET[EA] . ' where username="' . $_SESSION['username'] . '" limit 1'));
}
if ($clickcheck['username']!=$_SESSION['username'])
{
    if ($_SESSION['EA'.$_GET[EA]][cheat_link]==1)
    {
        @mysql_query ('update '. mysql_prefix .'last_login set cheat_links=cheat_links+1 where username="'.$_SESSION['username'].'"');
        $note = gmdate(timeformat) ." - Cheat click, email id: ". $_GET['EA'];
        append_note($note, $_SESSION['username']);            
    }
            @mysql_query ('insert into ' . mysql_prefix . 'paid_clicks_'.$_GET[EA].' set username="'.$_SESSION[username].'",value='.$_SESSION['EA'.$_GET[EA]][VA].',vtype="'.$_SESSION['EA'.$_GET[EA]][VT].'"');
            $update = @mysql_query("UPDATE " . mysql_prefix . "latest_stats set time=concat('" . mysqldate . ",',time),type=concat('paidmail,',type),id=concat('$_GET[EA],',id) where username='$_SESSION[username]' limit 1");

            if (!mysql_affected_rows())
            {
                @mysql_query ("insert into " . mysql_prefix . "latest_stats set time='".mysqldate."',type='paidmail',id='$_GET[EA]',username='$_SESSION[username]'");
            }

            @mysql_query ("update " . mysql_prefix . "email_ads set clicks=clicks+1 where emailid=$_GET[EA]");

            if ($_SESSION['EA'.$_GET[EA]][VA])
            {
                $update = @mysql_query('UPDATE ' . mysql_prefix . 'accounting SET amount=amount+'.$_SESSION['EA'.$_GET[EA]][VA].' WHERE type="'.$_SESSION['EA'.$_GET[EA]][VT].'" and username = "'.$_SESSION[username].'" and description="'.pmdescription.'" limit 1');


                if (!mysql_affected_rows())
                    $update = @mysql_query('INSERT INTO ' . mysql_prefix . 'accounting set transid="'.maketransid($_SESSION[username]).'",username = "'.$_SESSION[username].'",unixtime=0,description="'.pmdescription.'",amount='.$_SESSION['EA'.$_GET[EA]][VA].',type="'.$_SESSION['EA'.$_GET[EA]][VT].'"');

                if ($_SESSION['EA'.$_GET[EA]][VA] >= 0 && $_SESSION['EA'.$_GET[EA]][cheat_link]!=1)
                    creditulclicks($_SESSION[username], $_SESSION['EA'.$_GET[EA]][VA], $_SESSION['EA'.$_GET[EA]][VT]);
            }
}    
unset($_SESSION['EA'.$_GET[EA]]);
include (pages_dir . pages.'account_credited.php');
exit;
?>

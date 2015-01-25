<?php
if (!defined('version'))
  exit;

$user_country = mysql_real_escape_string(user('country','return'));
$ptcad = mysql_fetch_array(mysql_query('SELECT * FROM ' . mysql_prefix . 'ptc_ads WHERE ptcid="'.$_GET['PA'].'" AND (targeting LIKE "%|c:'. $user_country .'|%" OR targeting = "") LIMIT 1'));
$_SESSION['lastptc']=$_GET['PA'];

if (!$ptcad[0])
{
    include (pages_dir . pages.'invalid_paid_mail.php');
    exit;
}
//---------------------------------
// Trigger Turing check - CC222
//---------------------------------
if ($ptcad['turing'])
{
    $_SESSION['turing_time'] = 1;
}

if ($ptcad['image_url'] || $ptcad['text_ad'])
    login('turing');
else  
    login();

$ptcad['site_url'] = str_replace("#USERNAME#", $_SESSION['username'], $ptcad['site_url']);
$expireurl=$ptcad['site_url'];
if (($ptcad['clicks'] >= $ptcad['run_quantity'] && $ptcad['run_type']
            == 'clicks') or (mysqldate
            >= $ptcad['run_quantity'] && $ptcad['run_type']
            == 'date') or ($ptcad['views']
            >= $ptcad['run_quantity'] && $ptcad['run_type'] == 'views'))
{
    include (pages_dir . pages.'expired_ptc_ad.php');
    exit;
}

if (timer_lock == 'YES' && !$_SESSION['PA'.$_GET['PA']]['VT'])
{
    if (mysql_result(mysql_query('select last_click from '.mysql_prefix.'last_login where username="'.$_SESSION['username'].'"'),0,0)>unixtime)
    {
        header ('Location: '.pages_url.pages.'clicked_too_soon.php'); 
        exit;
    }
}

$clickcheck=mysql_fetch_array(mysql_query('SELECT * FROM `' . mysql_prefix . 'paid_clicks` WHERE id='.$_GET['PA'].' and username="'.$_SESSION['username'].'" LIMIT 1'));

if ($clickcheck['username']==$_SESSION['username'])
{
    include (pages_dir .pages. 'already_credited.php');
    exit;
}

if (timer_lock == 'YES')
    mysql_query('update '.mysql_prefix.'last_login set last_click='.unixtime.'+'.$ptcad['timer'].'-7 where username="'.$_SESSION['username'].'"');
 
if ($ptcad['timer'] && ($ptcad['image_url'] || $ptcad['text_ad']))
{
    $_SESSION['PA'.$_GET['PA']]['cheat_link']=$ptcad['cheat_link'];
    $_SESSION['PA'.$_GET['PA']]['VT']=$ptcad['vtype'];
    $_SESSION['PA'.$_GET['PA']]['TI']=$ptcad['timer'];
    $_SESSION['PA'.$_GET['PA']]['VA']=$ptcad['value'];
    //-----------------------------------------
    // Timer link session variables - CC219
    //-----------------------------------------
    $_SESSION['abuse_report']['type'] = 'PTC';
    $_SESSION['abuse_report']['ad'] = $_GET['PA'];
    $_SESSION['abuse_report']['url'] = $ptcad['site_url'];

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
                '<script type="text/javascript">window.focus()</script>

        <frameset framespacing=0 frameborder=1 border=1 rows="'.frame_size.',1*">  
            <frame  marginwidth=0 marginheight=0 name=timerfrm src=' . runner_url . '?PA='.$_GET['PA'].'&amp;FR=1'.add_session().' scrolling=no> 
            <frame  marginwidth=0 marginheight=0 name=Main src=' . runner_url . '?REDIRECT=' . rawurlencode($ptcad['site_url']). '&amp;hash='.md5($ptcad['site_url'].key).'> 
        </frameset> 

        <noframes>  
            <body>
            This page uses frames, but your browser does not support them.
            </body> 
        </noframes> 
        </frameset>';
        exit;
    }
    if($timerbar_location == 'bottom')
    {
            echo
                '<script type="text/javascript">window.focus()</script>

        <frameset framespacing=0 frameborder=1 border=1 rows="1*,'.frame_size.'">  
            <frame  marginwidth=0 marginheight=0 name=Main src=' . runner_url . '?REDIRECT=' . rawurlencode($ptcad['site_url']). '&amp;hash='.md5($ptcad['site_url'].key).'> 
            <frame  marginwidth=0 marginheight=0 name=timerfrm src=' . runner_url . '?PA='.$_GET['PA'].'&amp;FR=1'.add_session().' scrolling=no> 
        </frameset> 

        <noframes>  
            <body>
            This page uses frames, but your browser does not support them.
            </body> 
        </noframes> 
        </frameset>';
        exit;
    }
}

@mysql_query ("insert into " . mysql_prefix . "paid_clicks set username='$_SESSION[username]',id='$_GET[PA]',value='$ptcad[value]',vtype='$ptcad[vtype]'");

if ($ptcad['cheat_link']==1)
{
        @mysql_query ('update '. mysql_prefix .'last_login set cheat_links=cheat_links+1 where username="'.$_SESSION['username'].'"');
        $note = gmdate(timeformat) ." - Cheat click, PTC id: ". $ptcad['ptcid'];
        append_note($note, $_SESSION['username']);
}


        @mysql_query ("update " . mysql_prefix . "ptc_ads set clicks=clicks+1 where ptcid=$_GET[PA]");
        $update = @mysql_query("UPDATE " . mysql_prefix . "latest_stats set time=concat('" . mysqldate . ",',time),type=concat('ptc,',type),id=concat('$_GET[PA],',id) where username='$_SESSION[username]' limit 1");

        if (!mysql_affected_rows())
        {
            @mysql_query ("insert into " . mysql_prefix . "latest_stats set time='".mysqldate."',type='paidmail',id='$_GET[PA]',username='$_SESSION[username]'");
        }

        if ($ptcad['value'])
        {
            $update = @mysql_query("UPDATE " . mysql_prefix . "accounting SET amount=amount+$ptcad[value] WHERE type='$ptcad[vtype]' and username = '$_SESSION[username]' and description='".ptcdescription."' limit 1");

            if (!mysql_affected_rows())
            {
                $update = @mysql_query('insert INTO ' . mysql_prefix . 'accounting set transid="'.maketransid($_SESSION[username]).'", username = "'.$_SESSION[username].'",unixtime=0,description="'.ptcdescription.'",amount='.$ptcad[value].',type="'.$ptcad[vtype].'"');
            }

            if ($ptcad['value'] > 0)
            {
                creditulclicks($_SESSION['username'], $ptcad['value'], $ptcad['vtype']);
            }
        }

        if (!$ptcad[image_url] && !$ptcad[text_ad] )
        {
            echo '<script> 
window.focus(); 
</script>';

            include (pages_dir .pages. 'account_credited.php');
        }
        else
        {
            header ('Location: ' . runner_url . '?hash='.md5($ptcad['site_url'].key).'&REDIRECT=' . rawurlencode(
                $ptcad[site_url]));
        }

        exit;
?>

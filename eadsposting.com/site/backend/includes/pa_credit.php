<?php
if (!defined('version'))
   exit;

if ($_SESSION['PA'.$_GET[PA]][cheat_link] != 1)
{
    $clickcheck=@mysql_fetch_array(@mysql_query("select * from " . mysql_prefix . "paid_clicks where id=$_GET[PA] and username='$_SESSION[username]' limit 1"));
}
if ($clickcheck[username]!=$_SESSION[username])
{
    if ($_SESSION['PA'.$_GET[PA]][cheat_link]==1)
    {
        @mysql_query ('update '. mysql_prefix .'last_login set cheat_links=cheat_links+1 where username="'.$_SESSION['username'].'"');
        $note = gmdate(timeformat) ." - Cheat click, PTC id: ". $_GET['PA'];
        append_note($note, $_SESSION['username']);
    }

            @mysql_query ('insert into ' . mysql_prefix . 'paid_clicks set username="'.$_SESSION[username].'",id='.$_GET[PA].',value='.$_SESSION['PA'.$_GET[PA]][VA].',vtype="'.$_SESSION['PA'.$_GET[PA]][VT].'"');
            @mysql_query ('update ' . mysql_prefix . 'ptc_ads set clicks=clicks+1 where ptcid='.$_GET[PA]);
            $update = @mysql_query("UPDATE " . mysql_prefix . "latest_stats set time=concat('" . mysqldate . ",',time),type=concat('ptc,',type),id=concat('$_GET[PA],',id) where username='$_SESSION[username]' limit 1");

            if (!mysql_affected_rows())
            {
                @mysql_query ("insert into " . mysql_prefix . "latest_stats set time='".mysqldate."',type='ptc',id='$_GET[PA]',username='$_SESSION[username]'");
            }


            if ($_SESSION['PA'.$_GET[PA]][VA])
            {
                $update = @mysql_query('UPDATE ' . mysql_prefix . 'accounting SET amount=amount+'.$_SESSION['PA'.$_GET[PA]][VA].' WHERE type="'.$_SESSION['PA'.$_GET[PA]][VT].'" and username = "'.$_SESSION[username].'" and description="'.ptcdescription.'" limit 1');

                if (!mysql_affected_rows())
                {
                    $update = @mysql_query('INSERT INTO ' . mysql_prefix . 'accounting set transid="'.maketransid($_SESSION[username]).'",username = "'.$_SESSION[username].'",unixtime=0,description="'.ptcdescription.'",amount='.$_SESSION['PA'.$_GET[PA]][VA].',type="'.$_SESSION['PA'.$_GET[PA]][VT].'"');
                }

                if ($_SESSION['PA'.$_GET[PA]][VA] >= 0 && $_SESSION['PA'.$_GET['PA']]['cheat_link'] != 1)
                {
                    creditulclicks($_SESSION['username'],$_SESSION['PA'.$_GET[PA]][VA], $_SESSION['PA'.$_GET[PA]][VT]);
                }
            }
}
unset($_SESSION['PA'.$_GET[PA]]);
include (pages_dir .pages. 'account_credited.php');
exit;
            
?>

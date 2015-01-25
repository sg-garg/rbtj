<?php
include("functions.inc.php");

if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
    exit('Invalid Serial Number');

if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);

$title='Referral Stats';
admin_login();

if (!isset($to)){$to=date('m/d/Y',unixtime);}
if (!isset($from)){$from=date('m/01/Y',unixtime);}

echo "<form method=post>List all referrals counts for the dates of<br>from: <input type=text name=from value=$from> to: <input type=text name=to value=$to><input type=submit name=report value=Report></form> (mm/dd/yyyy)";
list($m,$d,$y)=explode("/",trim($from));
$f=$y.$m.$d;
list($m,$d,$y)=explode("/",trim($to));
$t=$y.$m.$d;
if ($report)
{
    $report = @mysql_query("select referrer,count(*) as counter from ".mysql_prefix."users where signup_date>='$f' and signup_date<='$t' group by referrer order by counter desc");
    echo "<br><table class='centered' border='1' cellpadding='3' cellspacing='0'>
            <tr><td><b>Username</b></td><td><b>Total Referrals</b>";
    while($row=@mysql_fetch_array($report))
    {
        $user=@mysql_fetch_row(@mysql_query("select username from ".mysql_prefix."users where account_type!='canceled' and username='{$row['referrer']}'"));
        if ($user[0] or $row['referrer']=='No Referrer')
        {
            if($bgcolor == ' class="row1" ')
            {
                $bgcolor=' class="row2" ';
            }
            else
            {
                $bgcolor=' class="row1" ';
            }
            if ($row['referrer']=='No Referrer')
            {
                echo "</td></tr><tr $bgcolor><td>{$row['referrer']}</td><td align=right>{$row['counter']}";
            }
            else 
            {
                echo "</td></tr><tr $bgcolor><td><a href=viewuser.php?userid={$row['referrer']} target=_viewuser>{$row['referrer']}</a></td><td align=right>{$row['counter']}";
            }
        }
    }
    echo "</td></tr></table>";
}
footer();

<?php
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='Purge Old Accounts';
admin_login();

if (!isset($days))
{
    if (defined('nocreditdays') && nocreditdays>0)
        $days=nocreditdays;
    else 
        $days=30;
}
echo "<form method=post><table border=0><tr><Td>Days inactive:</td><Td><input type=text name=days value=$days></td></tr><tr><td>Account type:</td><td><select name=account_type><option value=''>All<option value='canceled'>Canceled<option value='suspended'>Suspended</select></td></tr></table><input type=submit name=report value=Report></form>";
if ($connum)
{
    if ($connum!=$md5time)
    {
        echo "Invalid confirmation ID. Nothing deleted";
        footer();
    }
    if ($_POST['uid'])
    {
        foreach ($_POST['uid'] as $key => $value)
        {
            list($upline)=@mysql_fetch_row(@mysql_query("select upline from  ".mysql_prefix."users where username='$key'"));
            delete_user($key,$upline);
            echo "Deleted: $key<br>";
        }
        footer();
    }
}
if ($report)
{
    @mysql_query("delete from ".mysql_prefix."last_login where time<2003000000");
    $result=@mysql_query("select ".mysql_prefix."users.username from ".mysql_prefix."users left join ".mysql_prefix."last_login on ".mysql_prefix."users.username=".mysql_prefix."last_login.username where ".mysql_prefix."last_login.username is NULL");
    while($row=@mysql_fetch_array($result))
    {
        @mysql_query("insert into ".mysql_prefix."last_login set time='".substr(mysqldate,0,10)."',username='$row[username]'");
    }

    $time=date('YmdH',unixtime-($days*60*60*24));
    if ($account_type)
        $account_type='and account_type="'.$account_type.'"';

    $report=@mysql_query("select ".mysql_prefix."last_login.username,".mysql_prefix."last_login.time,".mysql_prefix."users.free_refs,".mysql_prefix."users.commission_amount,".mysql_prefix."users.account_type,".mysql_prefix."users.vacation from ".mysql_prefix."last_login,".mysql_prefix."users where ".mysql_prefix."last_login.username=".mysql_prefix."users.username $account_type and ".mysql_prefix."last_login.time<=$time order by ".mysql_prefix."last_login.time desc ");
    $md5time=substr(md5(unixtime),0,6);

    echo "<form method=post><input type=hidden name=md5time value=$md5time><br><br>To delete all checked accounts below<br>enter this confirmation ID: <b>$md5time
    </b><br><br><a href=backup.php>Download a gzipped copy of your Mysql data</a><br><br><b>THIS WILL DELETE ALL CHECKED ACCOUNTS BELOW. ONLY DO THIS IF YOU ARE SURE</b><br>Confirmation ID: <input type text name=connum><input type=submit value='Delete Now'><br>"; 
    echo "<table class='centered' border='1' cellpadding='3' cellspacing='0'><tr><td><b>Delete</b></td><td><b>Username</b></td><td><b>Account Type</b></td><td><b>Vacation</b></td><td><b>Last Login</b>";
    while($row=@mysql_fetch_array($report))
    {
        if($bgcolor == ' class="row1" ')
            $bgcolor=' class="row2" ';
        else
            $bgcolor=' class="row1" ';
        list($y,$m,$d)=explode("-",$row['vacation']);
        $row['vacation']=$m."/".$d."/".$y;
        if ($row['account_type']!='suspended' && $row['account_type']!='canceled' && (intval($m) or intval($d) or intval($y) or $row['free_refs']>0 or $row['commission_amount']>0 or $row['account_type']))
        {
            $checked='';
            if (!$row['account_type'])
            {
                $row['account_type']='Free Refs: '.$row['free_refs'].' Extra Comm: '.$row['commission_amount'];
            }
        } 
        else 
        {
            $checked='checked';
        }
        if (!intval($m) and !intval($d) and !intval($y))
        {
            $row['vacation']='';
        }
        echo "\n</td></tr><tr $bgcolor><td><input type=checkbox class=checkbox name=\"uid[$row[username]]\" value=1 $checked></td><td><a href='viewuser.php?userid=$row[username]' target=_viewuser>$row[username]</a></td><td>$row[account_type]</td><td>$row[vacation]</td><td align=right>".mytimeread(substr($row['time'],0,4).'-'.substr($row['time'],4,2).'-'.substr($row['time'],6,2).' '.substr($row['time'],8,2).':00:00');

    }
    echo "</td></tr></table></form>";
}
footer();


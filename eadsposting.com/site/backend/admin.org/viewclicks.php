<?php
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title=str_replace(array('c','p'),array('C','P'),$_GET['type']).' Click Counters';
admin_login();

if (!isset($to)){$to=1000000;}
if (!isset($from)){$from=0;}
echo "<b>$title</b><br>
    <form method='POST' action='viewclicks.php?type=".$_GET['type']."'>
    <table border='0'>
    <tr><td align='right'>Username:</td><td><input type='text' name='username' value='$username'>(leave blank to list all)</td></tr>
    <tr>
    <td>Membership:</td>
    <td><select name='account_type'>
              <option value=''>All Membership Types</option>
              <option value='advertiser'>Advertiser</option>
              <option value='suspended'>Suspended</option>
              <option value='canceled'>Canceled</option>
              <option value='free'>Free accounts</option>
              <option value='free_without'>Free accounts without RandomRef count or Extra Commission</option>
              <option value='free_with'>Free accounts with RandomRef count or Extra Commission</option>";
                $getkeys=@mysql_query("select ".mysql_prefix."member_types.description from ".mysql_prefix."member_types 
                                        left join ".mysql_prefix."users on ".mysql_prefix."member_types.description=".mysql_prefix."users.account_type 
                                        where ".mysql_prefix."users.account_type is not null group by ".mysql_prefix."users.account_type 
                                        order by ".mysql_prefix."users.account_type"); 
                while ($row=@mysql_fetch_row($getkeys))
                {                 
                    echo "<option value='".strtolower($row[0])."'>".$row[0]."</option>\n";
                }                
                
echo "       </select>
    </td>
    </tr>
    <tr><td align='right'>From:</td><td><input type='text' name='from' value='$from'> clicks</td></tr>
    <tr><td align='right'>To:</td><td><input type='text' name='to' value='$to'> clicks</td></tr>
    <tr><td colspan='2' align='center'><input type='submit' name='report' value='Report'></td></tr></table>
    </form>";
$f=$from;
$t=$to;
if ($connum)
{
    if ($connum!=$md5time)
    {
        echo "Invalid confirmation ID. Counters not reset";
    }
    else 
    { 
        if ($_GET['type']=='Total')
        {
            mysql_query("update ".mysql_prefix."last_login set points_clicks=0");
            mysql_query("update ".mysql_prefix."last_login set cash_clicks=0"); 
        } else {
            mysql_query("update ".mysql_prefix."last_login set ".$_GET['type']."_clicks=0");
        }
    }
}

$md5time=substr(md5(unixtime),0,6);
echo "<form method='POST'  action='viewclicks.php?type=".$_GET['type']."'><input type='hidden' name='md5time' value='$md5time'><br><br>To reset ".$_GET['type']." click counters for all accounts<br>enter this confirmation ID: <b>$md5time</b><br><br><a href='backup.php'>Download a gzipped copy of your Mysql data</a><br><br><b>THIS WILL RESET ALL COUNTERS FOR ALL ACCOUNTS</b><br>Confirmation ID: <input type='text' name='connum'><input type='submit' value='Reset Now'></form>";
if ($report)
{   
    //-------------------------------------
    // Username search, CC210
    //-------------------------------------
    if(!empty($_POST['username']))
    {
        $usernamewhere = " AND users.username = '{$_POST['username']}'";
    }else{
        $usernamewhere = '';
    }
    //-------------------------------------
    // Membership search, CC210
    //-------------------------------------
    $accountwhere = '';
    if ($_POST['account_type']=='free_with')
    {
        $accountwhere = 'and account_type="" and (free_refs>0 or commission_amount>0)';
    }
    else if ($_POST['account_type']=='free_without')
    {
        $accountwhere = 'and account_type="" and (free_refs=0 or commission_amount=0)';
    }
    else if ($_POST['account_type']=='free')
    {
        $accountwhere = 'and account_type=""';
    }
    else 
    {
        if (!empty($_POST['account_type']))
        {
            $accountwhere = ' AND account_type="'.addslashes($_POST['account_type']).'"';
        }
    }
    if ($_GET['type']=='Total')
    {
        $report=@mysql_query("select ".mysql_prefix."users.username,cash_clicks+points_clicks as total_clicks  from ".mysql_prefix."last_login,".mysql_prefix."users where ".mysql_prefix."users.username=".mysql_prefix."last_login.username $usernamewhere $accountwhere and account_type!='canceled' and cash_clicks+points_clicks!=0 and cash_clicks+points_clicks>=$f and cash_clicks+points_clicks<=$t order by total_clicks desc");
    } else {
        $report=@mysql_query("select ".mysql_prefix."users.username,".$_GET['type']."_clicks from ".mysql_prefix."last_login,".mysql_prefix."users where  ".mysql_prefix."users.username=".mysql_prefix."last_login.username $usernamewhere $accountwhere and account_type!='canceled' and ".$_GET['type']."_clicks!=0 and ".$_GET['type']."_clicks>=$f and ".$_GET['type']."_clicks<=$t order by ".$_GET['type']."_clicks desc");
    }
    $type2 = str_replace(array('c','p'),array('C','P'),$_GET['type']);
    echo "<br>\n\n<center><table border='1' cellpadding='2' cellspacing='0'>\n<tr><th>Username</th><th>$type2 Clicks</th></tr>\n\n";
    while($row=@mysql_fetch_array($report))
    {
        if($bgcolor == 'class="row1"')
        {$bgcolor = 'class="row2"';}
        else
        {$bgcolor = 'class="row1"';}
        echo "<tr $bgcolor><td><a href='viewuser.php?userid=$row[username]' target='_viewuser'>$row[username]</a></td>\n<td align='right'>$row[1]</td></tr>\n\n";
    }
    echo "</table></center><br>";
}
footer();

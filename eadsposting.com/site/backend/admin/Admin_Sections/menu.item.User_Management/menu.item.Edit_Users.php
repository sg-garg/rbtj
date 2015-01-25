<?php
//LDF SCRIPTS
include("../../functions.inc.php");

if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='Search Users';
admin_login();

if ($_POST['connum'])
{
    if ($_POST['connum']!=$_POST['md5time'])
    {
        echo "Invalid confirmation ID. Nothing canceled";
    }
    elseif ($_POST['uid'])
    {
        foreach ($_POST['uid'] as $key => $value)
        {
            mysql_query('update '.mysql_prefix.'users set account_type="canceled" where username="'.$key.'"');
            echo "Canceled: $key<br>";
        }
    }
}
?>

<form  method='post'>Search Users Database (leave blank to list all users) 
<table border='0'>
<tr><td>Created between: </td><td><input type='text' name='joined_from' value='0000-00-00 00:00:00'> - <input type='text' name='joined_to' value='<?php echo gmdate("Y-m-d G:i:s");?>'> (yyyy-mm-dd hh:mm:ss)</td></tr>
<tr><td>Username: </td><td><input type=text name='user'></td></tr>
<tr><td>eMail:</td><td><input type=text name=email></td></tr>
<tr><td>First Name:</td><td><input type=text name=first></td></tr>
<tr><td>Last Name:</td><td><input type=text name=last></td></tr>
<tr><td>Address:</td><td><input type=text name=address></td></tr>
<tr><td>City:</td><td><input type=text name=city></td></tr>
<tr><td>State:</td><td>

<select name='state'>
<option value=''>All States</option>
<?php
$getkeys=@mysql_query("select ".mysql_prefix."states.state from ".mysql_prefix."states left join ".mysql_prefix."users on ".mysql_prefix."states.state=".mysql_prefix."users.state where ".mysql_prefix."users.state is not null group by ".mysql_prefix."users.state order by ".mysql_prefix."users.state");
while ($row=@mysql_fetch_row($getkeys))
{
    echo '<option value="'.strtolower($row[0]).'">'.$row[0].'</option>';
}
?>
</select>
</td>
</tr>
<tr><td>Zip Code:</td><td><input type=text name=zipcode></td></tr>
<tr><td>Country:</td><td>
<select name='country'><option value=''>All Countries
<?php
$getkeys=@mysql_query("select ".mysql_prefix."countries.country from ".mysql_prefix."countries left join ".mysql_prefix."users on ".mysql_prefix."countries.country=".mysql_prefix."users.country where ".mysql_prefix."users.country is not null group by ".mysql_prefix."users.country order by ".mysql_prefix."users.country");
while ($row=@mysql_fetch_row($getkeys))
{
    echo '<option value="'.strtolower($row[0]).'">'.$row[0];
}
?>
</select>

</td></tr> 
<tr>
  <td><input type=hidden name=get value=search>Security Level:</td>
  <td><select name=account_type>
              <option value=''>All Security Levels</option>
           
              <option value='suspended'>Suspended</option>
              <option value='canceled'>Canceled</option>

                <?php
                $getkeys=@mysql_query("select ".mysql_prefix."member_types.description from ".mysql_prefix."member_types 
                left join ".mysql_prefix."users on ".mysql_prefix."member_types.description=".mysql_prefix."users.account_type 
                where ".mysql_prefix."users.account_type is not null group by ".mysql_prefix."users.account_type 
                order by ".mysql_prefix."users.account_type"); 
                        while ($row=@mysql_fetch_row($getkeys))
                        {                 
                            echo '<option value="'.strtolower($row[0]).'">'.$row[0].'</option>';
                        }                
                
                ?>
            </select>
</td>
</tr>
<tr>
  <td>Activity:</td>
  <td><select name='activity'>
              <option value=''>All</option>
              <option value='active'>Active</option>
              <option value='inactive'>Inactive</option>
            </select>
</td>
</tr>

<tr>
    <td>
        Order by 
    </td>
    <td>
        <select name=order><option value='username' selected>Username<option value='signup_date'>Creation Date</select>
    
    </td>
</tr>
<tr>
    <td>
        List
    </td>
    <td>
        <input type=text name=limit value=100> Users
    </td>
</tr>
<tr>
<td colspan='2' align='center'>
    <input type=submit value='Search'></form>
</td>
</tr>
</table> 
<?php
if ($_POST['get']=='search' or $_POST['nouplinesearch'])
{
    $_POST['user']=substr(preg_replace("([^a-zA-Z0-9])", "", $_POST['user']),0,16);
    $_POST['upline']=substr(preg_replace("([^a-zA-Z0-9])", "", $_POST['upline']),0,16);
    $_POST['referrer']=substr(preg_replace("([^a-zA-Z0-9])", "", $_POST['referrer']),0,16);
    if ($_POST['account_type']=='free_with')
    {
        $account_type='and account_type="" and (free_refs>0 or commission_amount>0)';
    }
    else if ($_POST['account_type']=='free_without')
    {
        $account_type='and account_type="" and (free_refs=0 or commission_amount=0)';
    }
    else if ($_POST['account_type']=='free')
    {
        $account_type='and account_type=""';
    }
    else 
    {
        if ($_POST['account_type'])
            $account_type='and account_type="'.addslashes($_POST['account_type']).'"';
        else 
            $account_type='and account_type!="canceled"';
    }
    if ($_POST['keyword'])
    {
        $_POST['keyword']="keywords like '%||".addslashes($_POST['keyword'])."||%' and ";
    }
    //--------------------------------------
    // Signup date search - CC208
    //--------------------------------------
    $account_type .= " AND signup_date BETWEEN '$_POST[joined_from]' AND '$_POST[joined_to]' ";
    $md5time=substr(md5(unixtime),0,6);

    //--------------------------------------
    // Activity search - CC220
    //--------------------------------------
    if(isset($_POST['activity']))
    {
        if(!defined('nocreditdays'))
        {
            $activity_limit = 0;
        }else{
            $activity_limit = date('YmdH', unixtime - nocreditdays * 24 * 60 * 60);
        }
        switch($_POST['activity'])
        {
            case 'active':
                $activity = " AND time >= $activity_limit ";
                break;
            case 'inactive':
                $activity = " AND time < $activity_limit ";
                break;
            default:
                $activity = '';
        }
    }else{
        $activity = '';
    }

    //--------------------------------------
    // Vacation search - CC220
    //--------------------------------------
    if(isset($_POST['vacation']))
    {
        switch($_POST['vacation'])
        {
            case 'active':
                $vacation = " AND vacation >= '". date('Y-m-d', unixtime)."' ";
                break;
            case 'expired':
                $vacation = " AND vacation < '". date('Y-m-d', unixtime)."' AND vacation != '0000-00-00' ";
                break;
            case 'blank':
                $vacation = " AND vacation = '0000-00-00' ";
                break;
            default:
                $vacation = '';
        }
    }else{
        $vacation = '';
    }
    ?>

    <form method='POST'>
    <input type='hidden' name='md5time' value='<?php echo $md5time;?>'><br>
    <br>
    To cancel all checked accounts below<br>
    enter this confirmation ID: <b><?php echo $md5time;?></b><br>
    <br>
    <a href='backup.php'>Download a gzipped copy of your Mysql data</a><br>
    <br>
    <b>THIS WILL CANCEL ALL CHECKED ACCOUNTS BELOW. ONLY DO THIS IF YOU ARE SURE</b><br>
    Confirmation ID: <input type='text' name='connum'><input type=submit value='Cancel Now'><br>
    
    <table class='centered' border='1' cellspacing='0' cellpadding='2' width=100%>
    <tr>
        <th>Cancel</th>
        <th colspan='2'>User Information</th>
    </tr>

    <?php
    if ($_POST['limit']){$_POST['limit']="limit $_POST[limit]";}
    if (!$_POST['nouplinesearch'])
    {
        $q = "SELECT ".mysql_prefix."users.* 
                                FROM ".mysql_prefix."users 
                                LEFT JOIN ".mysql_prefix."last_login on (".mysql_prefix."users.username=".mysql_prefix."last_login.username)
                                WHERE ($_POST[keyword] ".mysql_prefix."users.username like '%$_POST[user]%' and city like '%".addslashes($_POST['city'])."%' and address like '%".addslashes($_POST[address])."%' and zipcode like '$_POST[zipcode]%' and email like '%$_POST[email]%' and first_name like '%$_POST[first]%' and last_name like '%$_POST[last]%' and country like '%".addslashes($_POST[country])."%' and state like '%$_POST[state]%' $account_type and (signup_ip_host like '%$_POST[host]%' OR ip_host like '%$_POST[host]%'))
                                $activity $vacation
                                ORDER BY $_POST[order] $_POST[limit]";
        //echo $q;
        $getads=mysql_query($q); 
    }
    else {
        $getads=mysql_query("select ".mysql_prefix."users.* from ".mysql_prefix."users left join ".mysql_prefix."users as ".mysql_prefix."users2 on ".mysql_prefix."users.upline=".mysql_prefix."users2.username where ".mysql_prefix."users2.username is NULL");
    }
    while($row=mysql_fetch_array($getads))
    {
        if($bgcolor == 'class="row1"')
        {$bgcolor = 'class="row2"';}
        else
        {$bgcolor = 'class="row1"';}

        list($lastip,$lastdate,$compid)=@mysql_fetch_row(@mysql_query("select ip_host,time,computerid from ".mysql_prefix."last_login where username='$row[username]'"));
        if ($lastdate)
        {
            $lastdate=substr($lastdate,0,4).'-'.substr($lastdate,4,2).'-'.substr($lastdate,6,2).' '.substr($lastdate,8,2).':00:00';
        }
        $linect++;
        if (!$row['account_type'])
        {
            $row['account_type']='No Level Set';
        }

        echo "<tr $bgcolor>
        <td align=center>
            <input type=checkbox class=checkbox name=\"uid[$row[username]]\" value=1>
        </td>
        <td colspan=2>
            <table border='0'>
            <tr>
                <th width=120 align=right>Username:</th>
                <td><a href='user-edit.php?userid=$row[username]'>$row[username]</a></td>
                <th width=120 align=right>Name:</th>
                <td>$row[first_name] $row[last_name]</td>
            </tr>
            <tr>
                <th width=120 align=right>Security Level:</th>
                <td>$row[account_type]</td>
                <th width=120 align=right>Country</th>
                <td>$row[country]</td>
            </tr>
            <tr>
                <th width=120 align=right></th>
                <td></td>
                <th width=120 align=right>Address:</th>
                <td>$row[address]</td>
            </tr>
            <tr>
                <th width=120 align=right>eMail:</th>
                <td><a href='mailto:$row[email]'>$row[email]</a></td>
                <th width=120 align=right>City:</th>
                <td>$row[city]</td>
            </tr>
            <tr>
                <th width=120 align=right></th>
                <td></td>
                <th width=120 align=right>State:</th>
                <td>$row[state]</td>
            </tr>
            <tr>
                <th width=120 align=right></th>
                <td></td>
                <th width=120 align=right>Zip Code:</th>
                <td>$row[zipcode]</td>
            </tr>
            </table>
        </td>
        </tr>
        <tr $bgcolor>
            <th align=center>$linect</th>
            <td><b>Creation:</b> ". mytimeread($row['signup_date']) ."<br>
                <b>IP/Proxy:</b> $row[signup_ip_host]</td>
            <td><b>Last Login:</b> ". mytimeread($lastdate) ."<br>
                <b>IP/Proxy:</b> $lastip</td>
        </tr>";

    }
    echo "</table>";
    $count=@mysql_num_rows($getads);
    echo "<b>".$count." record(s) found</b></form>";

}
footer();
?>

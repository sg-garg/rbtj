<?php
//LDF SCRIPTS
include("../../functions.inc.php");

if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='User Profile';
admin_login();


if ($_POST['add'])
{
    $userid=strtolower(substr(preg_replace('([^a-zA-Z0-9])', '', $_POST['add']), 0, 16));
    mysql_query('insert into '.mysql_prefix.'users set username="'.$userid.'",email="'.$userid.'@'.unixtime.'.com",password="'.unixtime.'", signup_date = NOW()');
}

if ($_POST['save_notes'])
{
    @mysql_query('replace into '.mysql_prefix.'notes set username="'.$_POST['userid'].'",notes="'.addslashes($_POST['notes']).'"');
}

if (!$userid)
{
    delete_user('','');
}

$_SESSION['username']=$userid;


if ($_POST['user_form']=='userinfo' and isset($_POST['userform']['disable_turing']))
{
    mysql_query('update '.mysql_prefix.'users set disable_turing="'.$_POST['userform']['disable_turing'].'" where username="'.$_SESSION['username'].'"');
}
if ($_POST['user_form']=='userinfo' and isset($account_type))
{
    mysql_query('update '.mysql_prefix.'users set account_type="'.addslashes($account_type).'" where username="'.$userid.'"');
    if ($account_type)
    {
        $commission_amount=0;
        
        list($commission_amount)=@mysql_fetch_row(@mysql_query('select commission_amount from '.mysql_prefix.'member_types where description = "'.addslashes($account_type).'"'));
        $commission_amount=$commission_amount/100000;
    }
    mysql_query("update ".mysql_prefix."users set commission_amount=".$commission_amount."*100000 where username='$username'");
}
if ($_POST['user_form']=='userinfo' and isset($upgrade_expires))
{
    mysql_query('update '.mysql_prefix.'users set upgrade_expires="'.addslashes($upgrade_expires).' 00:00:00" where username="'.$userid.'"');
}

$userinfo=@mysql_fetch_array(@mysql_query("select * from ".mysql_prefix."users where username='$userid'"));
$username=$userinfo['username'];
$password=$userinfo['password'];
//echo "<pre>";
//print_r($_SESSION);
//print_r($_COOKIE);
//echo "</pre>";
?>
<style>
    .viewuserfield {
        width: 330px;
    }
</style>
<table width="100%" border='0'>
<tr>
<td valign=top>
    <center>

        <table border='0'>
        <tr>
        <td width="110">
            Username:
        </td>
        <td width="430"> 
            <form name="loginas" style="margin:0px; padding:0px;" method=post  action=<?php echo pages_url;?>userinfo.php target='_membersarea'>
            <input type=hidden value=2 name=admin_form>
            <input type=hidden name=username value="<?php user("username");?>">
            <input type=hidden name=password value="<?php echo $_SESSION['admin_password'];?>">
            <b><?php user("username");?></b> 
            <a href="#" onClick="javascript:document.forms['loginas'].submit();">(Login as <?php user("username");?>)</a>
            </form>
        </td>
        </tr>
        <tr>
        <td>
            Status:
        </td>
        <td> 
            <?php 
                //------------------------------------------
                // Calculate if user is active and get some data
                //------------------------------------------
                list($lastip,$lastdate,$lastbrowser,$badturing, $cheatclicks) = mysql_fetch_row(mysql_query("select ip_host,time,browser,bad_turing,cheat_links from ".mysql_prefix."last_login where username='$userid'"));
                if ($lastdate)
                {
                    $lastlogin = mytimeread(substr($lastdate,0,4).'-'.substr($lastdate,4,2).'-'.substr($lastdate,6,2).' '.substr($lastdate,8,2).':00:00');
                    $lastdate=substr($lastdate,0,4).'-'.substr($lastdate,4,2).'-'.substr($lastdate,6,2);
                    $active = strtotime($lastdate);
                    if((unixtime - $active) > (nocreditdays * 86400) AND nocreditdays != '')
                    {
                        $active = FALSE;
                    }
                    else
                    {
                        $active = TRUE;
                    }
                }
                else
                {
                    $active = FALSE;
                }

                //------------------------------------------
                // Default values when there is no last_login -- CC220
                //------------------------------------------
                if(empty($lastlogin)) {
                    $lastlogin = '[ never ]';
                }

                if(empty($lastip)) {
                    $badturing = 'N/A';
                    $cheatclicks = 'N/A';
                    $lastip = '[ no logins ]';
                }

                if(empty($lastbrowser)) {
                    $lastbrowser = '[ no logins ]';
                }


                if($userinfo['account_type'] == 'suspended')
                {
                    echo "<font color='orange'>suspended</font>, last login $lastlogin<br>";
                }
                else if($userinfo['account_type'] == 'canceled')
                {
                    echo "<font color='red'>canceled</font>, last login $lastlogin<br>";
                }
                else
                {
                    if(empty($userinfo['account_type']))
                        $account = "No Security Level Set";
                    else
                        $account = $userinfo['account_type'];

                    if($active)
                        $status = "<font color='green'>Active";
                    else
                        $status = "<font color='grey'>Inactive";

                    echo "<b>$status $account</font></b>, last login $lastlogin<br>";
                }
            ?>
        </td>
        </tr>
    
        <tr>
        <td colspan='2' style='height: 20px;'>
            <hr>
        </td>
        </tr>


        <tr>
        <td>
            Creation Date:
        </td>
        <td>
            <?php 
            $signupdate = user("signup_date", 'return');
            if(substr($signupdate,0,10) == "0000-00-00")
            {
                echo "[ not available ]";
            }
            else
            {
                echo mytimeread($signupdate);
            }?>
        </td>
        </tr>
        <tr>
        <td>
            Creation IP/Host:
        </td>
        <td>
            <?php 
            $signuphost = user("signup_ip_host", 'return');
            if(empty($signuphost))
            {
                echo "[ not available ]";
            }
            else
            {
                echo safeentities($signuphost);
            }?>
        </td>
        </tr>

        <tr>
        <td>
            Last IP/Host:
        </td>
        <td>
            <?php echo safeentities($lastip); ?>
        </td>
        </tr>

        <tr>
        <td>
            Last browser:
        </td>
        <td>
            <?php echo safeentities($lastbrowser); ?>
        </td>
        </tr>

        <tr>
        <td colspan='2' style='height: 20px;'>
            <hr>
        </td>
        </tr>
    <form style="margin:0px; padding:0px;"  method=post>
    <input type=hidden name=userid value='<?php echo $userid;?>'>
    <input type=hidden name=user_form value=userinfo>
    <input type=hidden name=required value='email,first_name,last_name,address,city,state,zipcode,country,phone'>
    <?php form_errors("email","You must place an email address in the email address field","The email address you select is already in use please try another","<tr><td colspan=2 bgcolor=red align=center>","</td></tr>");?> 
    <tr>
    <td>
        <a href=mailto:<?php user("email");?>>E-Mail:</a>
    </td>
    <td> 
        <input class="viewuserfield" type="text" name="userform[email]" value="<?php user("email");?>">
    </td>
    </tr>

    <tr>
    <td>
        Send emails to:
    </td>
    <td> 
        <select name='userform[email_setting]'>
        <?php existing_member_email_setting();?>
        </select>
    </td>
    </tr>
    <?php form_errors("first_name","You must place your first name in the first name field","N/A","<tr><td colspan=2 bgcolor=red align=center>","</td></tr>");?>
    <?php form_errors("last_name","You must place your last name in the last name field","N/A","<tr><td colspan=2 bgcolor=red align=center>","</td></tr>");?> 
    <tr>
    <td>First, Last Name:</td>
    <td> 
        <input style="width:163px;" type="text" name="userform[first_name]" value="<?php user("first_name");?>"> 
        <input style="width:163px;" type="text" name="userform[last_name]" value="<?php user("last_name");?>">
    </td>
    </tr>
    <?php form_errors("address","You must place your street address in the address field","N/A","<tr><td colspan=2 bgcolor=red align=center>","</td></tr>");?>
    <tr>
    <td>Address:</td>
    <td> 
        <input class="viewuserfield" type="text" name="userform[address]" value="<?php user("address");?>">
    </td>
    </tr>
    <?php form_errors("city","You must place your city in the city field","N/A","<tr><td colspan=2 bgcolor=red align=center>","</td></tr>");?>   
    <?php form_errors("zipcode","You must place your zip or postal code in the zip code field","N/A","<tr><td colspan=2 bgcolor=red align=center>","</td></tr>");?>
    <tr>
        <td>
            City, zipcode:
        </td>
        <td> 
            <input style="width:163px;" type="text" name="userform[city]" value="<?php user("city");?>">
            <input style="width:163px;" type="text" name="userform[zipcode]" value="<?php user("zipcode");?>">
        </td>
    </tr>
    <?php form_errors("state","You must place your state in the state field","N/A","<tr><td colspan=2 bgcolor=red align=center>","</td></tr>");?>
    <tr>
        <td>
            State:
        </td>
        <td> 
            <select class="viewuserfield" name="userform[state]">
            <option value=''>Please select your state
            <?php existing_member_states();?>
            </select>
        </td>
    </tr>            
    <?php form_errors("country","Please select your country","N/A","<tr><td colspan=2 bgcolor=red align=center>","</td></tr>");?>
    <tr>
        <td>
            Country:
        </td>
        <td> 
            <select name="userform[country]">
            <option value=''>Please select your country
            <?php existing_member_countries();?>
        </select>
        </td>
    </tr>
<tr>
        <td>
            Cell Phone #:
        </td>
        <td> 
		<input class="viewuserfield" type="text" name="userform[cell_phone]" value="<?php user("cell_phone");?>">
        </td>
</tr>
<tr>
        <td>
            Ad Phone #:
        </td>
        <td> 
		<input class="viewuserfield" type="text" name="userform[ad_phone]" value="<?php user("ad_phone");?>">
        </td>
</tr>
<tr>
        <td>
            FAX #:
        </td>
        <td> 
		<input class="viewuserfield" type="text" name="userform[fax]" value="<?php user("fax");?>">
        </td>
</tr>
<tr><td colspan=2><hr /></td></tr>
<tr>
        <td>
            DBA #:
        </td>
        <td> 
		<input class="viewuserfield" type="text" name="userform[dba]" value="<?php user("dba");?>">
        </td>
</tr>

<tr>
        <td>
            EIN/SS #:
        </td>
        <td> 
		<input class="viewuserfield" type="text" name="userform[ss]" value="<?php user("ss");?>">
        </td>
</tr>
<tr>
        <td>
            Sales Tax #:
        </td>
        <td> 
		<input class="viewuserfield" type="text" name="userform[sales_tax_id]" value="<?php user("sales_tax_id");?>">
        </td>
</tr>
<tr>
        <td>
            Division:
        </td>
        <td> 
		<input class="viewuserfield" type="text" name="userform[division]" value="<?php user("division");?>">
        </td>
</tr>
<tr>
 <td>   DOB:
 </td>
 <td>
            <input type='date' name='userform[dob]'value="<?php user("dob");?>">
           
            <br />(input format yyyy-mm-dd)
        </td>
    </tr>
<tr>
<td colspan=2>

    <hr>
</td>
</tr>

    <tr>
    <td colspan=2 align=center>
        <b>Security Level</b>

    </td>
    </tr>

            <tr>
            <td>
                Security Level:
            </td>
            <td>
            <select
            <?php $result=@mysql_query('select description from '.mysql_prefix.'member_types order by description');
            echo '<select name=account_type><option value="">Use custom settings below';
            $selected='';
            if ($userinfo['account_type']=='suspended')
            $selected='selected';
            echo '<option value="suspended" '.$selected.'>Suspended';
            $selected='';
            if ($userinfo['account_type']=='canceled')
            $selected='selected';
            
            echo '<option value="canceled" '.$selected.'>Canceled';
            
            while ($row=mysql_fetch_row($result)){
                $selected='';
                if ($userinfo['account_type']==$row[0])
                $selected='selected';
                echo '<option '.$selected.' value="'.safeentities($row[0]).'">'.safeentities($row[0]);
            }
            ?>
        
            </select>
            </td>
            </tr>
            <tr>
            <td>
                Auto-expires:
            </td>
            <td>
                <input type='text' name='upgrade_expires' 
                value='<?php 
                list($upgrade_expires) = @mysql_fetch_array(@mysql_query('select upgrade_expires from ' . mysql_prefix . 'users where username="' . $_SESSION['username'] . '" limit 1')); 
                if(substr($upgrade_expires, 0, 4) == '0000')
                    echo '';
                else
                    echo substr($upgrade_expires, 0, 10);
                ?>'><br>
                (input format yyyy-mm-dd, blank to disable)
            </td>
            </tr>

        <tr>
        <td colspan='2' align='center'>
        <br>
        <b>Custom Settings</b>
        </td>
        </tr> 
        <tr>
            <td>
                Discount (%):
            </td>
        <td>
        <input type=text name=commission_amount value=<?php echo number_format($userinfo['commission_amount']/100000,5);?>></td></tr>
 
        <tr>
        <td>
           Captcha:
        </td>
        <td>
            <select name=userform[disable_turing]>
            <option value='0' <?php if ($userinfo[disable_turing]==0){ echo 'selected';}?>>Enabled<option value='1' <?php if ($userinfo[disable_turing]==1){ echo
            'selected';}?>>Disabled
                </select>
        </td>
        </tr>
        </td>
        </tr>

        <tr><td colspan='2' style='height:8px'></td></tr>  

        <?php form_errors("password","The password you entered did not match what you put in the confirmation field","N/A","<tr><td colspan=2 bgcolor=red align=center>","</td></tr>");?>
        <tr>
            <td>
                New Password:
            </td>
            <td>
                <input class="viewuserfield" type=password name=userform[password] value="">
            </td>
        </tr>
        <tr>
            <td>
                Confirm New Password:
            </td>
            <td>
                <input class="viewuserfield" type=password name=userform[confirm_password] value="">
            </td>
        </tr>
        <tr>
            <td colspan=2 align=center>
                <input type="submit" value="Save Changes">
            </td>
        </tr>         
        <input type=hidden value=1 name=admin_form>
        <input type=hidden value="<?php user("username");?>" name=username>
        <input type=hidden value="<?php user("password");?>" name=userform[alterinfo_password]>
        <input type=hidden value="<?php echo $_SESSION[admin_password];?>" name=password>
        </form>
</table>
</center>


<table style="width: 100%">
    <tr>
    <form method=post>
    <td align=center>
        <hr>
        <br>
        <b>Notes:</b><br>
        <textarea name=notes rows=10 style="width: 97%"><?php list($notes)=@mysql_fetch_row(@mysql_query('select notes from '.mysql_prefix.'notes where username="'.$_SESSION[username].'"'));echo safeentities($notes);?></textarea><br>
        <center>
        <input type=submit name=save_notes value='Save Notes'>
        <input type=hidden name=userid value=<?php echo $userid;?>>
        </center>
    </td>
    </tr>
    </form>
</table>

<br>
<hr>
<br>


<table border='0' width="100%">
    <tr>
    <td valign='top' align='right'>
        
        <table width="100%" border='1' cellpadding='2' cellspacing='0'>
            <tr><th colspan=3 align=center>Transactions <a href=transactions.php?usersearch=<?php user("username");?>&transtype=cash>(edit cash transactions)</a></td></tr>
            <?php cash_transactions("all","<tr><td align=right>","</td><td align=right>","</td></tr>","desc","yes","</td><td>",5,admin_cash_factor,10);?>
            <tr><td align=right><b>Account Balance:</b></td><td colspan=2 align=right><?php cash_totals("all",5,admin_cash_factor);?></td></tr>
        </table>

    </td>
    </tr>
</table>

<br>
<?php 
$_SESSION[username]='';
footer();

?>

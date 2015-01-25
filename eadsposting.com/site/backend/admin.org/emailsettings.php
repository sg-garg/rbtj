<?php
include("functions.inc.php");
$title='eMail Settings';
admin_login();

if ($_POST['sysval'])
{
    reset($_POST['sysval']);
    while (list($key, $value) = each($_POST['sysval']))
    {
        if(!$value)
            $value = ' ';
        $value = system_value($key,$value);
        @mysql_query("replace into ".mysql_prefix."system_values set name='$key',value='".addslashes(trim($value))."'");
    }
}
$aollink = system_value("aollink");
?>
<form method=post><table border=0>
<input type=hidden name=admin_password value='<?php echo $_SESSION['admin_password'];?>'>
<tr><td align=right>Your Site Name: </td><td><input type=text size=30 name=sysval[site_name]  value='<?php print system_value("site_name");?>'></td></tr>
<tr><td align=right>Your Domain Name: </td><td><input type=text size=30 name=sysval[domain]  value='<?php print system_value("domain");?>'></td></tr>   
<tr><td align=right>Redemption notices are sent to: </td><td><input type=text size=30 name=sysval[redemption_email] value='<?php print system_value("redemption_email");?>'></td></tr>
<tr><td align=right>Advertising emails are sent to: </td><td><input type=text size=30 name=sysval[advertising_email]  value='<?php print system_value("advertising_email");?>'></td></tr>
<tr><td align=right>Support emails are sent to: </td><td><input type=text size=30 name=sysval[support_email]  value='<?php print system_value("support_email");?>'></td></tr>     
<tr><td align=right>Mass emails are sent as being from: </td><td><input type=text size=30 name=sysval[massmail_email]  value='<?php print system_value("massmail_email");?>'></td></tr>
<tr><td align=right>To: address for BCC Mass emails: </td><td><input type=text size=30 name=sysval[massmail_to]  value='<?php print system_value("massmail_to");?>'></td></tr>
<tr><td align=right>When sending emails that contain the &lt;OWED&gt; tag skip members that do not owe anything:</td>
    <td><select name=sysval[skiponowe]>
            <option value='no' <?php if (system_value("skiponowe")=='no'){ echo "selected";}?>>No</option>
            <option value='yes' <?php if (system_value("skiponowe")=='yes'){ echo "selected";}?>>Yes</option>
        </select>
</td></tr>
<tr><td align=right>Create AOL links to plain text massmails: </td>
    <td><select name=sysval[aollink]>
            <option value='no' <?php if ($aollink =='no'){ echo "selected";}?> >No</option>
            <option value='yes' <?php if ($aollink =='yes' OR empty($aollink)){ echo "selected";}?> >Yes</option>
        </select>
</td></tr>
<tr><td align=right>Throttle back the send speed of mass mails to help prevent being blacklisted and reduce load on your server:<br>(Do not turn this off with out having prior permission from your host.)</td><td><select name=sysval[spamsafety]><option value='yes' <?php if (system_value("spamsafety")=='yes'){ echo "selected";}?>>Yes
<option value='no' <?php if (system_value("spamsafety")=='no'){ echo "selected";}?>>No
</select>
</td></tr>
<tr><td align=right>Maximum recipients to send to per hour:<br>(Set to 0 for unlimited)</td><td><input type=text size=4 name=sysval[mailsperhour] value='<?php print system_value('mailsperhour');?>'></td></tr>
<?php if (!ini_get('safe_mode')){
echo '<tr><td align=right>Force Return-Path eMail header:<br>(do not use unless you\'re not receiving bounced email notices)</td><td><select name=sysval[force_return]><option value=NO>No<option value=YES';

	if (system_value('force_return')=='YES')
	echo ' selected';

echo '>Yes</select></td></tr>
<tr><td align=right>Custom Header Tag<br>(leave blank unless you KNOW what your doing}</td><td><input type=text size=30 name=sysval[customheader]  value="'.system_value('customheader').'"></td></tr>
<tr><td align=right>Alternate sendmail or qmail-inject path OR smtp server for mass mailer:<br>ie: /var/qmail/bin/qmail-inject<br>ie: mysmtpserver.com:2525<br>(Leave blank to disable)</td><td><input type=text size=30 name=sysval[sendmailpath] value="'.system_value('sendmailpath').'"></td></tr>';
}
?>

<tr><td align=right>Number of days to keep email IDs in internal inboxes:</td><td> <input type=text size=3 name=sysval[inboxexpire] value='<?php echo system_value('inboxexpire');?>'></td></tr>
<tr><td colspan=2 align='left'><div align='center'><b>CashCrusader Software Sendmail Server Config</b></div><br>
<?php if (ccsss!='enabled') {?>
<b>What is CCSSS and what can it do for my site?</b><br><br> 
<li>CCSSS is a virtual sendmail appliance designed for the CashCrusader Scripts.</li>
<li>It allows you to send all your emails from any other server on any other network where you host the appliance.</li> 
<li>For a list of hosting companies that support hosting for CCSSS (can run run a Linux, MAC or Windows Servers) <a href=http://cashcrusadersoftware.com/ccsss.php>click here</a></li>
<li>It supports round-robin binding to multiple IPs to distribute concurrent connections.</li>
<li>It can round-robin as many as 253 ips as long as they are all in the same network block</li>
<li>It automatically tags user accounts with the status of their email address: success, deferral or failure.</li>
<li>It shows the last response received from a user's email service provider.</li>
<li>It has counters that keep track of how many deferrals or failures a user's email account has had. </li>
<li>It flags user accounts to 'site inbox only' on these failures, and automatically sets them to retry at a later time.</li>
<li>You can control how many times the CCSSS will retry sending individual email messages to a mailbox</li>
<li>You can control how many concurrent connections are allowed</li>
<li>If the CCSSS is offline CC scripts will fall back to using the local mailer</li>
<br><b>In short take control of your massmail list! Stop sending to dead email accounts. Reduce deferrals and failures. Get your mail out quicker and to more people with CCSSS</b><br>
<br><a href=http://cashcrusadersoftware.com/ccsss.php>Click here to order today!</a>
<?php }
if (!defined('ccsss_license') || ccsss_verified=='false' ){
if (!defined('ccsss_license')) 
echo 'Please enter your CCSSS license key<br>';
if (ccsss_verified=='false')
echo 'They license key you entered it incorrect, please resolve<br>';
echo 'CCSSS License Key: <input type=text size=64 name=sysval[ccsss_license] value="'.system_value('ccsss_license').'">';
}
echo '</td></tr><tr><td align=right>';
echo 'How long should deferred emails be retried before quiting:<br>(enter time in seconds. Recommend: 1800)</td><td><input type=text size=30 name=sysval[ccsss_retry] value="'.system_value('ccsss_retry').'"></td></tr>';
echo '</td></tr><tr><td align=right>';
echo 'Max concurrent remote connections per domain:<br>(1 - 255. Recommend: 1)</td>
<td><input type=text size=30 name=sysval[ccsss_connections] value="'.system_value('ccsss_connections').'"></td></tr>';
echo '</td></tr><tr><td align=right>';
echo 'Retry sending to deferred user accounts after how many days:<br>(Recommend: 2)</td>
<td><input type=text size=30 name=sysval[ccsss_deferred_retry] value="'.system_value('ccsss_deferred_retry').'"></td></tr>';
echo '</td></tr><tr><td colspan=2 align=right>After a member\'s deferral counter reaches <input type=text size=3 name=sysval[ccsss_deferred_max] value="'.system_value('ccsss_deferred_max').'"> change the member\'s account to ';
$result=@mysql_query('select description from '.mysql_prefix.'member_types order by description');
            $selected='';
            if (system_value('ccsss_deferred_account')=='advertiser')
            $selected='selected';
            echo '<select name=sysval[ccsss_deferred_account]><option value="">Do not change<option value="advertiser" '.$selected.'>Advertiser';
            $selected='';
            if (system_value('ccsss_deferred_account')=='suspended')
            $selected='selected';
            echo '<option value="suspended" '.$selected.'>Suspended';
            $selected='';
            if (system_value('ccsss_deferred_account')=='canceled')
            $selected='selected';
            
            echo '<option value="canceled" '.$selected.'>Canceled';
            
            while ($row=mysql_fetch_row($result)){
                $selected='';
                if (system_value('ccsss_deferred_account')==$row[0])
                $selected='selected';
                echo '<option '.$selected.' value="'.safeentities($row[0]).'">'.safeentities($row[0]);
            }
echo '</select></td></tr><tr><td align=right>';
echo 'Retry sending to failed user accounts after how many days:<br>(Recommend: 7)</td>
<td><input type=text size=30 name=sysval[ccsss_failed_retry] value="'.system_value('ccsss_failed_retry').'"></td></tr>';
echo '<tr><td colspan=2 align=right>After a member\'s failure counter reaches <input type=text size=3 name=sysval[ccsss_failure_max] 
 value="'.system_value('ccsss_failure_max').'"> change the member\'s account to ';
$result=@mysql_query('select description from '.mysql_prefix.'member_types order by description');
            $selected='';
            if (system_value('ccsss_failure_account')=='advertiser')
            $selected='selected';
            echo '<select name=sysval[ccsss_failure_account]><option value="">Do not change<option value="advertiser" '.$selected.'>Advertiser';
            $selected='';
            if (system_value('ccsss_failure_account')=='suspended')
            $selected='selected';
            echo '<option value="suspended" '.$selected.'>Suspended';
            $selected='';
            if (system_value('ccsss_failure_account')=='canceled')
            $selected='selected';

            echo '<option value="canceled" '.$selected.'>Canceled';
            while ($row=mysql_fetch_row($result)){
                $selected='';
                if (system_value('ccsss_failure_account')==$row[0])
                $selected='selected';
                echo '<option '.$selected.' value="'.safeentities($row[0]).'">'.safeentities($row[0]);
            }
echo '</select></td></tr><tr><td align=right><b>CCSSS primary IP:</b></td><td>'.system_value('ccsss_primary_ip').'</td></tr><tr><td align=right><b>Additional IPs:</b><br>Add up to 253 IPs to this list for round-robin. They must be in the same network block as your primary ip.</td><td  align=right><textarea name="sysval[ccsss_other_ips]" rows="15" cols="28">'.system_value('ccsss_other_ips').'</textarea></center>
</td></tr></table>';
echo '<div align=center>
<input type=submit value="Save Changes"></form><br>
<b>CCSSS Server Status: ';
if (ccsss=='enabled'){
echo 'Enabled ';
echo '</b><table border=1><tr><td align=left>'; 
?>
<div align=center><form method='post' action='mysql_admin.php'><input type='hidden' name='returnpage' value='emailsettings.php'>
<b>Mail Delivery Reports</b><br><select name='wyoqta'>
<option value="select count(*) as totals ,ccsss_last_email_status from <?php echo mysql_prefix;?>users where ccsss_last_email_status!='' group by ccsss_last_email_status order by totals desc">Status Totals
<option value="select ccsss_last_email_time,email,ccsss_last_email_status,ccsss_last_email_response from <?php echo mysql_prefix;?>users order by ccsss_last_email_time desc limit 100">Last 100 eMails
<option value="select ccsss_last_email_time,email,ccsss_last_email_status,ccsss_last_email_response from <?php echo mysql_prefix;?>users where 
ccsss_last_email_status='success' order by ccsss_last_email_time desc limit 100">Last 100 successful eMails
<option value="select ccsss_last_email_time,email,ccsss_last_email_status,ccsss_last_email_response from <?php echo mysql_prefix;?>users where
ccsss_last_email_status like 'deferral%' order by ccsss_last_email_time desc limit 100">Last 100 deferred eMails
<option value="select ccsss_last_email_time,email,ccsss_last_email_status,ccsss_last_email_response from <?php echo mysql_prefix;?>users where
ccsss_last_email_status like 'failure%' order by ccsss_last_email_time desc limit 100">Last 100 failed eMails
<option value="select count(*) as total ,SUBSTRING_INDEX(email, '@', -1)as
domain,ccsss_last_email_response from <?php echo mysql_prefix;?>users group by ccsss_last_email_response order by total desc limit 100">Top 100 server responces
<option value="select count(*) as total ,SUBSTRING_INDEX(email, '@', -1)as
domain,ccsss_last_email_response from <?php echo mysql_prefix;?>users where ccsss_last_email_status like 'success' group by domain order by total desc">List successes by domain
<option value="select count(*) as total ,SUBSTRING_INDEX(email, '@', -1)as 
domain,ccsss_last_email_response from <?php echo mysql_prefix;?>users where ccsss_last_email_status like 'deferral%' group by domain order by total desc">List deferrals by domain
<option value="select count(*) as total ,SUBSTRING_INDEX(email, '@', -1)as
domain,ccsss_last_email_response from <?php echo mysql_prefix;?>users where ccsss_last_email_status like 'failure%' group by domain order by total desc">List failures by domain
<option value="select count(*) as total ,SUBSTRING_INDEX(email, '@', -1) as  dn from <?php echo mysql_prefix;?>users where email_setting>0 group by dn order by total desc limit 500">List top 500 domain totals 
</select>
<br><input type=submit name='runquery' value='Submit Query'>
<?php echo '</div><hr><b>Server Report:</b><pre>';
$status=@mysql_fetch_row(@mysql_query('select value from '. mysql_prefix .'system_values where name="ccsss_status"'));
echo $status[0];
echo '</pre></td></tr></table>';
}
else echo 'Not Enabled ';
?>
</div>

<?php footer(); ?>


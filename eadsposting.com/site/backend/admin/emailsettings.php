<?php
//LDF SCRIPTS
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

<tr><td align=right>Support emails are sent to: </td><td><input type=text size=30 name=sysval[support_email]  value='<?php print system_value("support_email");?>'></td></tr>     
<tr><td align=right>Mass emails are sent as being from: </td><td><input type=text size=30 name=sysval[massmail_email]  value='<?php print system_value("massmail_email");?>'></td></tr>
<tr><td align=right>To: address for BCC Mass emails: </td><td><input type=text size=30 name=sysval[massmail_to]  value='<?php print system_value("massmail_to");?>'></td></tr>

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

<tr><td align=right>Number of days to keep email IDs in internal inboxes:</td><td> <input type=text size=3 name=sysval[inboxexpire] value='<?php echo system_value('inboxexpire');?>'></td></tr></table><br />
<div align=center><input type=submit value="Save Changes"></form><br></div>

<?php footer(); ?>


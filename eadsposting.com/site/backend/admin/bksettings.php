<?php
//LDF SCRIPTS
include("functions.inc.php");

if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='Backup Settings';
admin_login();

if (ini_get('safe_mode'))
{
    echo "<font color=red>Your server is running in safe mode. The backup feature provided with the CC scripts will not run in safe mode. Please use the MySQL backup features provided by your hosting company.";
    footer();
}
if ($_POST[sysval])
{
    reset($_POST[sysval]);
    while (list($key, $value) = each($_POST[sysval]))
    {
        if(!$value)    
        $value=' ';
        $value=system_value($key,$value);
        @mysql_query("replace into ".mysql_prefix."system_values set name='$key',value='".trim($value)."'");
    }
}
?>
<form method='POST'>
<table border='0' cellpadding='2' cellspacing='0'>
<tr>
<td>
    Display backup notice
</td>
<td>
    <input type=text size=3 name=sysval[bkwarn] value=<?php echo system_value('bkwarn');?>> hour(s) after your last successful backup
</td>
</tr>
<tr>
    <td>
        Local path for backup:
    </td>
    <td>
        <input style='width:400px' type='text' name='sysval[bklocalpath]' value=<?php echo system_value('bklocalpath');?>>
    </td>
</tr>
<tr>
    <td colspan='2'> 
        <br>
        <b>Send backup via email</b>
    </td>
</tr>
<tr>
    <td>
        eMail Address: 
    </td>
    <td>
        <input style='width:400px' type='text' name='sysval[bkmail]' value=<?php echo system_value('bkmail');?>>
    </td>
</tr>
<tr>
<td colspan='2'>     
    <br>
    <b>Upload backup via ftp</b>
</td>
</tr>
<tr>
    <td>
    FTP Server:
    </td>
    <td>
    <input style='width:400px' type='text' name='sysval[bkftpserver]' value='<?php echo system_value('bkftpserver');?>'>
    </td>
</tr>
<tr>
    <td>
        FTP Username:
    </td>
    <td>
        <input style='width:400px' type='text' name='sysval[bkftpuser]' value='<?php echo system_value('bkftpuser');?>'>
    </td>
</tr>
<tr>
    <td>
        FTP Password:
    </td>
    <td>
        <input style='width:400px' type='text' name='sysval[bkftppass]' value='<?php echo system_value('bkftppass');?>'>
    </td>
</tr>
<tr>
<td>
    FTP Path:
</td>
<td>
    <input style='width:400px' type='text' name='sysval[bkftppath]' value='<?php echo system_value('bkftppath');?>'>
</td>
</tr>
<tr>
<td colspan='2'>
    Make backups automaticly every <input type=text size=3 name=sysval[bkauto] value=<?php echo system_value('bkauto');?>> hour(s)
</td>
</tr>
<tr>
<td colspan='2'>
Requires the email address or FTP settings configured above
</td>
</tr>
</table>
<br>
<input type=submit value='Save Changes'></form>
<?php 

footer();

?>

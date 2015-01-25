<?php
//LDF SCRIPTS
include("functions.inc.php");

$title='IP Blocker';
admin_login();

//-----------------------------------
// New block added
//-----------------------------------
if ($_POST['save'] == 'Add Block' AND !empty($_POST['blockip']) AND $_POST['new'] == 'true')
{
    mysql_query("insert into ".mysql_prefix."ips set ip='".mysql_real_escape_string($_POST['blockip'])."', note='".mysql_real_escape_string($_POST['note'])."'");
}
//-----------------------------------
// Block Edited
//-----------------------------------
if ($_POST['save'] == 'Add Block' AND !empty($_POST['blockip']) AND $_POST['new'] == 'false')
{
    $query = "UPDATE ".mysql_prefix."ips SET ip='".mysql_real_escape_string($_POST['blockip'])."', note='".mysql_real_escape_string($_POST['note'])."' WHERE ip ='".mysql_real_escape_string($_POST['oldip'])."'";
    mysql_query($query);
}
//-----------------------------------
// Block Removed
//-----------------------------------
if ($_POST['save'] == 'Unblock')
{
    mysql_query("delete from ".mysql_prefix."ips where ip='".mysql_real_escape_string($_POST['ip'])."'");
}
//-----------------------------------
// Several entries unblocked
//-----------------------------------
if ($_POST['save'] == 'Unblock Select IPs')
{
    reset($_POST['uid']);
    while (list($key, $value) = each($_POST['uid']))
    {
        mysql_query("delete from ".mysql_prefix."ips where ip='".mysql_real_escape_string($value)."'");
    }
}
//-----------------------------------
// Fetch current blocks
//-----------------------------------
$report = mysql_query("select * from ".mysql_prefix."ips order by ip");

//-----------------------------------
// Are we editing a block?
//-----------------------------------
if ($_POST['save'] == 'Edit')
{
    $new = 'false';
    list($ip, $note) = mysql_fetch_array(mysql_query("select ip,note from ".mysql_prefix."ips WHERE ip = '".mysql_real_escape_string($_POST['ip'])."'"));
}else{
    $new = 'true';
    $ip = '';
    $note = 'Blocked on '.gmdate(timeformat,unixtime);
}
?>

<form method='POST' action='<?php echo $_SERVER['PHP_SELF'] ?>'>
<input type='hidden' name='new' value='<?php echo $new;?>'>
<input type='hidden' name='oldip' value='<?php echo $ip;?>'>
Use <b>%</b> as a wild card. ex: If you wish to block all IPs starting with <b>192.168.</b> then put <b>192.168.%</b> in the field below.<br>
If you want to block all hosts that are Squid cache servers put <b>%squid%</b> in the field below<br>
<br>
<table border='0'>
<tr>
    <td>IP or host name:</td>
    <td><input style='width: 300px' type='text' size='30' maxlength='64' name='blockip' value='<?php echo $ip;?>'></td>
</tr>
<tr>
    <td align='right'>Note:</td>
    <td><input style='width: 300px' type='text' size='30' maxlength='255' name='note'  value='<?php echo $note;?>'></td>
</tr>
<tr>
    <td colspan='2' align='center'><input type='submit' name='save' value='Add Block'></td>
</tr>
</table>
</form>
<br>

<table class='centered' cellpadding='2' cellspacing='0' border='1'>
<tr>
    <th>IP</th>
    <th>Note</th>
    <th>Action</th>
</tr>

<?php
while($row=mysql_fetch_array($report))
{
    if($bgcolor == ' class="row1"')
    {
        $bgcolor=' class="row2"';
    }else{
        $bgcolor=' class="row1"';
    }
    $counter++;
    $checked='';
    echo "\n<tr $bgcolor>
            <td>
                ".$row['ip']."
            </td>
            <td>
                ".$row['note']."
            </td>
            <td>
                <form method='POST' action='".$_SERVER['PHP_SELF']."'>
                <input type='hidden' name='ip' value='".$row['ip']."'>
                <input type='submit' name='save' value='Unblock'>
                <input type='submit' name='save' value='Edit'>
                </form>
            </td>
        </tr>";
}
?>

</table>
<br>

<?php footer(); ?>
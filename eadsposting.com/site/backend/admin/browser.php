<?php
//LDF SCRIPTS
include("functions.inc.php");

$title='IP Blocker';
admin_login();

//-----------------------------------
// New block added
//-----------------------------------
if ($_POST['save'] == 'Add Block' AND !empty($_POST['blockbrowser']) AND $_POST['new'] == 'true')
{
    mysql_query("insert into ".mysql_prefix."browsers set agent='%".mysql_real_escape_string($_POST['blockbrowser'])."%', note='".mysql_real_escape_string($_POST['note'])."'");
}
//-----------------------------------
// Block Edited
//-----------------------------------
if ($_POST['save'] == 'Add Block' AND !empty($_POST['blockbrowser']) AND $_POST['new'] == 'false')
{
    $query = "UPDATE ".mysql_prefix."browsers SET agent='".mysql_real_escape_string($_POST['blockbrowser'])."', note='".mysql_real_escape_string($_POST['note'])."' WHERE agent ='".mysql_real_escape_string($_POST['oldagent'])."'";
    mysql_query($query);
}
//-----------------------------------
// Block Removed
//-----------------------------------
if ($_POST['save'] == 'Unblock')
{
    mysql_query("delete from ".mysql_prefix."browsers where agent='".mysql_real_escape_string($_POST['browser'])."'");
}

//-----------------------------------
// Fetch current blocks
//-----------------------------------
$report = mysql_query("select * from ".mysql_prefix."browsers order by agent");

//-----------------------------------
// Are we editing a block?
//-----------------------------------
if ($_POST['save'] == 'Edit')
{
    $new = 'false';
    list($ip, $note) = mysql_fetch_array(mysql_query("select agent,note from ".mysql_prefix."agents WHERE agent = '".mysql_real_escape_string($_POST['browser'])."'"));
}else{
    $new = 'true';
    $browser = '';
    $note = 'Blocked on '.gmdate(timeformat,unixtime);
}
?>

<form method='POST' action='<?php echo $_SERVER['PHP_SELF'] ?>'>
<input type='hidden' name='new' value='<?php echo $new;?>'>
<input type='hidden' name='oldbrowser' value='<?php echo $browser;?>'>
Browser blocking is done based on the <b>HTTP_USER_AGENT</b> browser header. You can use <b>%</b> as a wild card.<br>
<br>
<table border='0'>
<tr>
    <td>Browser:</td>
    <td><input style='width: 300px' type='text' size='30' maxlength='64' name='blockbrowser' value='<?php echo $browser;?>'></td>
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
    <th>Browser</th>
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
                ".$row['agent']."
             </td>
            <td>
                ".$row['note']."
            </td>
            <td>
                <form method='POST' action='".$_SERVER['PHP_SELF']."'>
                <input type='hidden' name='browser' value='".$row['agent']."'>
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
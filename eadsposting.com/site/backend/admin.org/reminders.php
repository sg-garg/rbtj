<?php
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
$title='Admin Reminders/Notes';
admin_login();

$messagetext = "";
$duetext = "-";
$id = "new";
//-----------------------------------------
// Are we editing note?
//-----------------------------------------
if(!empty($_GET['edit']))
{
    list($id,$messagetext, $duetext) = mysql_fetch_array(mysql_query("SELECT id, message, due FROM ". mysql_prefix ."reminders WHERE id='$_GET[edit]' LIMIT 1"));
    $messagetext = str_replace("<br>", "\n", $messagetext);
}
//-----------------------------------------
// Message marked as done?
//-----------------------------------------
if(!empty($_GET['done']))
{
    mysql_query("UPDATE ". mysql_prefix ."reminders SET done = CURRENT_TIMESTAMP WHERE id='$_GET[done]' LIMIT 1") or die ("Oops, MySQL Query error");
}
//-----------------------------------------
// Delete Message?
//-----------------------------------------
if(!empty($_GET['del']))
{
    mysql_query("DELETE FROM ". mysql_prefix ."reminders WHERE id='$_GET[del]' LIMIT 1") or die ("Oops, MySQL Query error");
}
//-----------------------------------------
// Message submitted? Create if needed
//-----------------------------------------
if(!empty($_POST['message']))
{
    $message = str_replace("<", "&lt;", str_replace(">","&gt;",$_POST['message']));
    $message = str_replace("\n", "<br>", $message);
    $message = mysql_real_escape_string($message);
    if($_POST['id'] == "new")
    {
        $replace1 = "INSERT INTO";
        $replace2 = "";
    }else
    {
        $replace1 = "UPDATE";
        $replace2 = " WHERE id = '$_POST[id]'";
    }
    mysql_query("$replace1 ". mysql_prefix ."reminders SET created = CURRENT_TIMESTAMP,message = '$message', due = '$_POST[due]' $replace2") or die ("Oops, MySQL Query error");
}
//-----------------------------------------
// Ordering
//-----------------------------------------
if(empty($_GET['order_by']))
{
    $order_by = 'created';
}else{
    $order_by = preg_replace("([^a-zA-Z0-9])", "", $_GET['order_by']);
}
if(empty($_GET['order']))
{
    $order = 'ASC';
}else{
    $order = preg_replace("([^a-zA-Z0-9])", "", $_GET['order']);
}
//-----------------------------------------
// Ordering links
//-----------------------------------------
$created = "<a href='reminders.php?order_by=created&amp;order=ASC'><img style='border: 0' alt='[o]' src='arrow_up.gif'></a>";
if($order_by == 'created')
{
    if($order == 'ASC')
    {
        $created = "<a href='reminders.php?order_by=created&amp;order=DESC'><img style='border: 0' alt='[o]' src='arrow_up.gif'></a>";;
    }
    if($order == 'DESC')
    {
        $created = "<a href='reminders.php?order_by=created&amp;order=ASC'><img style='border: 0' alt='[o]' src='arrow_down.gif'></a>";;
    }
}
$due = "<a href='reminders.php?order_by=due&amp;order=ASC'><img style='border: 0' alt='[o]' src='arrow_up.gif'></a>";;
if($order_by == 'due')
{
    if($order == 'ASC')
    {
        $due = "<a href='reminders.php?order_by=due&amp;order=DESC'><img style='border: 0' alt='[o]' src='arrow_up.gif'></a>";;
    }
    if($order == 'DESC')
    {
        $due = "<a href='reminders.php?order_by=due&amp;order=ASC'><img style='border: 0' alt='[o]' src='arrow_down.gif'></a>";;
    }
}
$done = "<a href='reminders.php?order_by=done&amp;order=ASC'><img style='border: 0' alt='[o]' src='arrow_up.gif'></a>";;
if($order_by == 'done')
{
    if($order == 'ASC')
    {
        $done = "<a href='reminders.php?order_by=done&amp;order=DESC'><img style='border: 0' alt='[o]' src='arrow_up.gif'></a>";;
    }
    if($order == 'DESC')
    {
        $done = "<a href='reminders.php?order_by=done&amp;order=ASC'><img style='border: 0' alt='[o]' src='arrow_down.gif'></a>";;
    }
}
//-----------------------------------------
// Read messages and show them
//-----------------------------------------
$query = mysql_query("SELECT * FROM `".mysql_prefix."reminders` WHERE 1 ORDER BY $order_by $order");
echo "
    <table style='width:100%' border='1' cellpadding='3' cellspacing='0'>
    <tr>
        <td align='center' nowrap><b>Created</b> $created</td>
        <td align='center' style='width:90%'><b>Note</b></td>
        <td align='center' nowrap><b>Due</b> $due</td>
        <td align='center' nowrap><b>Done</b> $done</td>
        <td align='center' nowrap><b>Mark done / delete</b></td>
    </tr>";

while($row = mysql_fetch_array($query))
{
    if($bgcolor == ' class="row1" ')
        $bgcolor=' class="row2" ';
    else
        $bgcolor=' class="row1" ';

    $due = explode("-",$row['due']);
    $due = $due[0] * 365 + $due[1] * 30 + $due[2]; 
    $now = date("Y") * 365 + date("m") * 30 + date("d");
    if($due <= $now AND $due != 0 AND $row['done'] == "0000-00-00 00:00:00")
    {
        $duestyle = "color: #FF0000; font-weight: bold;";
    }else
    {
        $duestyle = "";
    }
    if($row['done'] == "0000-00-00 00:00:00")
    {
        $done = "&nbsp;";
    }else{
        $done = mytimeread($row['done']);
    }
    if($row['due'] == "-")
    {
        $row['due'] = "&nbsp;";
    }
    echo "
    <tr $bgcolor>
        <td nowrap>". mytimeread($row['created'])."</td>
        <td>$row[message]</td>
        <td nowrap style='$duestyle' align='center'>$row[due]</td>
        <td nowrap>$done</td>
        <td align='center' nowrap><a href='reminders.php?edit=$row[id]'>edit</a> / <a href='reminders.php?done=$row[id]'>done</a> / <a href='reminders.php?del=$row[id]'>del.</a></td>
    </tr>";
}
//-----------------------------------------
// Show submit form for message creation
//-----------------------------------------
?>
</table>
<br>
<br>
<table>
<tr>
<td>
    Note:
</td>
<td>
    <form action="reminders.php" method="POST">
    <textarea style="width:500px; height: 200px" name="message"><?php echo $messagetext;?></textarea>
</td>
</tr>
<tr>
<td>
Due:
</td>
<td>
    <input type="text" name="due" value="<?php echo $duetext;?>"> (Format: yyyy-mm-dd, <b>-</b> to disable )
    <input type="hidden" name="id" value="<?php echo $id;?>">
</td>
</tr>
<tr>
<td style="padding-left:60px" colspan='2'>
    <input type="submit" value="Create">
    </form>
</td>
</tr>
</table>




<?php

footer();
?>

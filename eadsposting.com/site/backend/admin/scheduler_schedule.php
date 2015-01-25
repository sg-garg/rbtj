<?php
include("../conf.inc.php");
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
$title='eMail Schedule';
admin_login();
include("scheduler_menu.php");
?>

<h2>Email Schedule</h2>

<?php
//-------------------------------------
// User Actions 
//-------------------------------------
if($_POST['action'] == "Delete")
{
    $id = (int)$_POST['id'];
    $query = "DELETE FROM ".mysql_prefix."scheduler_emails WHERE id = '$id'";

    echo "Removing scheduled email: "; 
    $result = mysql_query($query); 
    if (!$result) 
    {
        $msg  = " <font color='red'><b>ERROR!</b></font><br><b>Invalid query:</b> " . mysql_error() . "<br><br><b>Query:</b> " . $query;
        die($msg);
    }else{
        echo " <font color='green'><b>done</b></font><br>";
    }
}
if($_POST['action'] == "Pause")
{
    $id = (int)$_POST['id'];
    $query = "UPDATE ".mysql_prefix."scheduler_emails SET status = 'Paused' WHERE id = '$id'";

    echo "Pausing scheduled email: "; 
    $result = mysql_query($query); 
    if (!$result) 
    {
        $msg  = " <font color='red'><b>ERROR!</b></font><br><b>Invalid query:</b> " . mysql_error() . "<br><br><b>Query:</b> " . $query;
        die($msg);
    }else{
        echo " <font color='green'><b>done</b></font><br>";
    }
}
if($_POST['action'] == "Restore")
{
    $id = (int)$_POST['id'];
    $query = "UPDATE ".mysql_prefix."scheduler_emails SET status = '' WHERE id = '$id'";

    echo "Restoring scheduled email: "; 
    $result = mysql_query($query); 
    if (!$result) 
    {
        $msg  = " <font color='red'><b>ERROR!</b></font><br><b>Invalid query:</b> " . mysql_error() . "<br><br><b>Query:</b> " . $query;
        die($msg);
    }else{
        echo " <font color='green'><b>done</b></font><br>";
    }
}
if($_POST['action'] == "SendNow")
{
    $id = (int)$_POST['id'];
    $query = "UPDATE ".mysql_prefix."scheduler_emails SET next_send = NOW() WHERE id = '$id'";

    echo "Re-scheduling email: "; 
    $result = mysql_query($query); 
    if (!$result) 
    {
        $msg  = " <font color='red'><b>ERROR!</b></font><br><b>Invalid query:</b> " . mysql_error() . "<br><br><b>Query:</b> " . $query;
        die($msg);
    }else{
        echo " <font color='green'><b>done</b></font><br>";
    }
}
if($_GET['action'] == "deleteold")
{
    if($_GET['subaction'] == "days")
    {
        echo "<form style='margin:0px; padding:0px;' method='GET' action='scheduler_schedule.php'>\n";
        echo "<input type='hidden' value='deleteold' name='action'>\n";
        echo "<input type='hidden' value='list' name='subaction'>\n";
        echo "List expired scheduled massmails sent last time over ";
        echo "<input style='width:30px;' type='text' value='15' name='days'> ago<br>\n";
        echo "<input type='submit' value='Report'\n";
        echo "</form>";
        footer();
    }
    if($_GET['subaction'] == "list")
    {
        $confirmation = substr(md5(unixtime), 0,6);
        ?>
        <form style='margin:0px; padding:0px;' method='GET' action='scheduler_schedule.php'>
        <input type='hidden' value='<?php echo $confirmation;?>' name='confirmation'>
        <input type='hidden' value='deleteold' name='action'>
        <input type='hidden' value='delete' name='subaction'>

        <table border='0'>
        <tr>
        <td>
            Confirmation code:
        </td>
        <td>
            <b><?php echo $confirmation;?></b>
        </td>
        <td rowspan='2'>
            &nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' value='Delete selected ads'><br>
        </td>
        </tr>
        <tr>
        <td>
            Confirm: 
        </td>
        <td>
            <input style='width:50px;' type='text' value='' name='confirmation_user'>
        </td>
        </tr>
        </table>

        <br>   

        <table border='1' cellpadding='2' cellspacing='0'>
        <tr>
        <td align='center'>
            <b>ID</b>
        </td>
        <td align='center'>
            <b>Subject</b>
        </td>
        <td align='center'>
            <b>Last Sent</b>
        </td>
        <td align='center'>
            <b>Delete</b>
        </td>
        </tr>
        <?php
        $query = mysql_query("SELECT * FROM ".mysql_prefix."scheduler_emails WHERE last_sent <= DATE_SUB(NOW(), INTERVAL ". $_GET['days'] ." DAY) ORDER BY last_sent ASC");
        while($row = mysql_fetch_array($query))
        {

            if($row['status'] == '') 
            {
                $row['status'] = check_scheduled_status($row['id']);
            }
            if($row['status'] == 'Expired')
            {
                echo "<tr>
                <td>
                    $row[id]
                </td>
                <td>
                    $row[subject]
                </td>
                <td>
                    ". mytimeread($row['last_sent']) ."
                </td>
                <td><b>
                    <input type='checkbox' value='$row[id]' name='delete[]' checked>
                </td>
                </tr>\n\n";
            }
        }
        echo "</table></form>";
        footer();
    }
    if($_GET['subaction'] == "delete")
    {
        if ($_GET['confirmation'] != $_GET['confirmation_user'])
        {
            echo "Invalid confirmation ID. Nothing deleted";
            footer();   
        }
        foreach($_GET['delete'] as $id)
        {
            $id = (int)$id;
            $query = "DELETE FROM ".mysql_prefix."scheduler_emails WHERE id = '$id'";
        
            echo "Removing scheduled email id $id: "; 
            $result = mysql_query($query); 
            if (!$result) 
            {
                $msg  = " <font color='red'><b>ERROR!</b></font><br><b>Invalid query:</b> " . mysql_error() . "<br><br><b>Query:</b> " . $query;
                die($msg);
            }else{
                echo " <font color='green'><b>done</b></font><br>";
            }
        }
        footer();
    }
}
?>

<ul>
    <li>Click subject to edit scheduled email</li>
    <li>Hover over status to see used Headerset</li>
    <li>Click <i>Next Send</i> time to force active email to be sent into massmailer when next cron runs</li>
    <li><a href='scheduler_schedule.php?action=deleteold&amp;subaction=days'>List/Delete expired scheduled massmails</a></li>
</ul> 

<table border='1' cellpadding='2' cellspacing='0' width='100%'>
<tr>
<td align='center'><b>
    Subject</b>
</td>
<td align='center'><b>
    Status</b>
</td>
<td align='center'><b>
    Freq.</b>
</td>
<td align='center'><b>
    Last sent</b>
</td>
<td align='center'><b>
    Next Send</b>
</td>
<td align='center'><b>
    Action</b>
</td>
</tr>

<?php
//--------------------------------------------
// Fetch stuff from email schedule and display
//--------------------------------------------
$query = mysql_query("SELECT * FROM ".mysql_prefix."scheduler_emails WHERE 1 ORDER BY next_send ASC");

for($i = 0; $i <= 23; $i++)
{
    $hour[$i] = '';
}


while($row = mysql_fetch_array($query))
{

    $email_hour = trim(trim(substr($row['next_send'],11,2).".", '0'), '.');
    if(empty($email_hour))
    {
        $email_hour = '0';
    }

    if($row['status'] == '') 
    {
        $row['status'] = check_scheduled_status($row['id']);
    }
    if($row['status'] == "Active")
    {
        $toggle = "Pause";
        $status = "<span style='color:green; font-weight: bold;' title='$row[headerset]'>$row[status]</span>";
    }
    else if($row['status'] == "Expired")
    {
        $toggle = "Pause";
        $status = "<span style='color:grey; font-weight: bold;' title='$row[headerset]'>$row[status]</span>";
    }
    else if($row['status'] == "Paused")
    {
        $toggle = "Restore";
        $status = "<span style='color:red; font-weight: bold;' title='$row[headerset]'>$row[status]</span>";
    }
  
    $hour[$email_hour] .= "<tr>
    <td>
        <form name='edit$row[id]' style='margin:0px; padding:0px;' method='POST'  action='scheduler_schedule_editor.php'>
        <input type='hidden' name='id' value='$row[id]'>
        <input type='hidden' name='action' value='Edit'>
        <a href='#' onClick=\"javascript:document.forms['edit$row[id]'].submit();\">$row[subject]</a>
        </form>
    </td>
    <td>
        $status
    </td>
    <td align='right'>
        ". number_format($row['frequenzy'] / 60,1) ."h
    </td>
    <td align='center'>";
    if($row['last_sent'] == '0000-00-00 00:00:00')
    {
        $hour[$email_hour] .=  "<span style='color:grey; font-weight: bold;'><i>Never sent before</i></span>";
    }
    else
    {
        $hour[$email_hour] .=  mytimeread($row['last_sent']);
    }
    $hour[$email_hour] .= "</td><td align='center'>\n";
    if($row['status'] == 'Expired' OR $row['status'] == 'Paused')
    {
        $hour[$email_hour] .=  "<span style='color:grey; font-weight: bold;'><i>n/a</i></span>";
    }
    else
    {
        $hour[$email_hour] .=  "
        <form name='send$row[id]' style='margin:0px; padding:0px;' method='POST'>
        <input type='hidden' name='id' value='$row[id]'>
        <input type='hidden' name='action' value='SendNow'>
        <a href='#' onClick=\"javascript:document.forms['send$row[id]'].submit();\">". mytimeread($row['next_send']) ."</a>
        </form>";
    }
    $hour[$email_hour] .=  "\n</td>
    <td>
        <form style='margin:0px' method='POST'><input type='hidden' name='id' value='$row[id]'><input type='submit' name='action' value='$toggle'> / <input type='submit' name='action' value='Delete'></form>
    </td>
    </tr>
    \n\n";

}
for($i = 0; $i <= 23; $i++)
{
    if(!empty($hour[$i]))
    {
        $email_hour_to = $i + 1;
        echo "<tr><td colspan='8' height='30' valign='bottom'><center><small><b>Hour $i:00 - $email_hour_to:00</b></small></center></td></tr>\n";
        echo $hour[$i];
    }
}
echo "</table>";

footer(); 
?>

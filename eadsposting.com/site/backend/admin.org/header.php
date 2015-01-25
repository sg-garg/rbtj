<?php
if (!defined('version'))
exit('Access Denied');
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=<?php echo charset;?>">
    <link href="admin.css" rel="stylesheet" type="text/css">
    <?php
    //----------------------------------------------
    // Support for custom admin CSS styling
    //----------------------------------------------
    if (file_exists("admin.custom.css"))
    {
        echo "<link href='admin.custom.css' rel='stylesheet' type='text/css'>";
    }?>
    
    <title><?php echo $title.' for '.domain;?></title>
    <script type="text/javascript" language="javascript">
        var revertback = false;
        function closeNote()
        {
            if(revertback)
            {
                document.getElementById("note").style.right = "10px";
                document.getElementById("closer").innerHTML = "x";
                revertback = false;
            }else{
                document.getElementById("note").style.right = "-130px";
                document.getElementById("closer").innerHTML = "<<<<<<<<<<<<<<<<<<<<<<<";
                revertback = true;
            }
        
        }
    </script>

    <!--[if gt IE 5.0]>
    <![if lt IE 7]>  
    <style type="text/css">  
    /* that IE 5+ conditional comment makes this only visible in IE 5+ */  
    ul.admin_mainmenu li {  
        behavior: url( IEtweak.htc );  
    }  
    ul.admin_mainmenu ul {  
        display: none; 
        position: absolute; 
        top: 1px; 
        left: 108px;  
    }  
    </style>  
    <![endif]>
    <![endif]-->

    <?php
    global $ptarget;
    if($ptarget)
    plugin($ptarget, 'java_load');
    ?>
</head>
<body>
<a name="top"></a>
<?php

//---------------------------------------------------
// Note checker
//---------------------------------------------------
$query = mysql_query("SELECT * FROM `".mysql_prefix."reminders` WHERE 1 ORDER BY id ASC");
$msg = "";
while($row = mysql_fetch_array($query))
{
    $due = explode("-",$row['due']);
    $due = $due[0] * 365 + $due[1] * 30 + $due[2]; 
    $now = date("Y") * 365 + date("m") * 30 + date("d");
    if($due <= $now AND $due != 0 AND $row['done'] == "0000-00-00 00:00:00")
    {
        $msg .= "<hr><center><b>$row[due]</b></center><br>";
        $msg .= $row['message'];
    }
}
//---------------------------------------------------
// Abuse checker
//---------------------------------------------------
$query = mysql_query("SELECT * FROM `".mysql_prefix."abuse` WHERE 1 ORDER BY id ASC LIMIT 1");
while($row = mysql_fetch_array($query))
{
    $msg .= "<hr><center><b><a href='abusereports.php'>Pending abuse<br>reports!</a></b></center>";
}
//---------------------------------------------------
// Custom reminders
//---------------------------------------------------
if (file_exists('reminders.custom.php'))
{
    echo "\n<!-- reminders.custom.php included -->\n";
    ob_start();
    include('reminders.custom.php');
    $msg .= ob_get_contents();
    ob_end_clean();
}
if(!empty($msg))
{
    echo "<div id='note' class='reminder'>";
    echo "<center><b><a href='reminders.php'>Reminders due today</a></b> <span id='closer' onClick=\"closeNote()\">x</span></center>$msg</div>";
}

?>
<table class='header_table' cellpadding='0' cellspacing='0'>
<tr>
<td valign='top'>
    <ul class="admin_mainmenu">
    <li class="top_level_link"><a href="index.php">Main</a>
    </li>
    <li>Accounting
        <ul>
        <li><a href="redeemmgr.php">Redemption Options</a></li>
        <li><a href="paymentopt.php">Payment Options</a></li>    
        <li><a href="transactions.php">Transaction Ledger</a></li>
        <li><a href="convertpoints.php">Convert Points To Cash</a></li>
        <li><a href="cashearnings.php">Cash Balances</a></li>
        <li><a href="pointearnings.php">Point Balances</a></li>
        </ul>
    </li>
    <li>User Management
        <ul>

        <li><a href="adduser.php">Add a User</a></li>
        <li><a href="membertypes.php">Membership Types</a></li>
        <li><a href="usermanager.php">View/Edit/Delete Users</a></li>
        <li><a href="visitors.php">Visitors Online</a></li>
        <li><a href="purge.php">Purge Old Accounts</a></li>
        </ul>
    </li>
    <li>Clicks &amp; Referrals
        <ul>
    
        <li><a href="viewrefs.php">Referral Stats</a></li>
        <li><a href="viewclicks.php?type=cash">Cash Click Counters</a></li>
        <li><a href="viewclicks.php?type=points">Point Click Counters</a></li>
        <li><a href="viewclicks.php?type=Total">Total Click Counter</a></li>
        </ul>
    </li>
    <li>Find Cheaters
        <ul>
        <li><a href="dupfinder.php">List Cheaters</a></li>
        <li><a href="posdupfind.php">List Possible Cheaters</a></li>
        <li><a href="samecomputer.php">Accounts Accessed By Same PC</a></li>
        <li><a href="possamecomputer.php">Accounts Possibly Accessed By Same PC</a></li>
        <li><a href="viewturing.php">Bad Turing Clicks Report</a></li>
        <li><a href="cheatlink.php">Cheat Link Report</a></li>
        </ul>
    </li>
    <li>Ad Management
        <ul>
        <li><a href="autosurf.php?AS=1">Auto/Manual Surf</a></li>
        <li><a href="emailadmgr.php">Email Ads</a></li>
        <li><a href="ptcadmgr.php">Paid2Click Ads</a></li>
        <li><a href="reviewadmgr.php">Paid2RevieW Ads</a></li>
        <li><a href="ptsadmgr.php">Paid2Signup Ads</a></li>
        <li><a href="startpage.php">Paid Start Page</a></li>
        <li><a href="admgr.php">Rotating Ads</a></li>
        <li><a href="massmail.php">Send eMail To Members</a></li>
        <li><a href="scheduler.php">Paidmail Scheduler</a></li>
        </ul>
    </li>
    <li>Blocking Options
        <ul>
        <li><a href="browser.php">Block certain browsers</a></li>
        <li><a href="emails.php">Block email addresses and domains</a></li>
        <li><a href="ips.php">Block IPs and host names</a></li>
        </ul>
    </li>
    <li>Site settings
        <ul>
        <li><a href="password.php">Set Admin Password</a></li>
        <li><a href="charset.php">Set Default Character Set</a></li>
        <li><a href="urlsanddirs.php">Set URLs and Directories</a></li>
        <li><a href="timer_settings.php">Timer Settings</a></li>
        <li><a href="autosurfconfig.php">Auto/Manual Surf Settings</a></li>
        <li><a href="accounting_settings.php">Accounting Settings</a></li>
        <li><a href="emailsettings.php">Site eMail Settings</a></li>
        <li><a href="anticheat.php">Anti-Cheat Settings</a></li>
        <li><a href="bksettings.php">MySQL Backup Settings</a></li>
        <li><a href="othersettings.php">Other Settings</a></li>
        <li><a href="listme.php">List Your site</a></li>
        <li><a href="reminders.php">Admin reminders/notes</a></li>
        <li><a href="keywords.php">Keyword Selections</a></li>
        <li><a href="states.php">State Selections</a></li>
        <li><a href="countries.php">Country Selections</a></li>
        </ul>
    </li>
    <li>Database Utilities
        <ul>
        <li><a href="backup.php">Backup MySQL Data</a></li>
        <li><a href="restore.php">Restore MySQL Data</a></li>
        <li><a href="maillist.php">Download eMail Addresses</a></li>
        <li><a href="mysql_admin.php">MySQL Admin</a></li>
        </ul>
    </li>
    <li class="top_level_link"><a href="helpdesk.php">Helpdesk</a>
    </li>
    </ul>

</td>
  
<td valign='top'>
    <div class='header_div'>
        <div class='header_picture'><div class='header_version'><?php echo "version ".version;?></div></div>

        <table border='0' cellpadding='0' cellspacing='0'>
        <tr>
        <td width='40'>
            <a href='plugins.php'><img border=0 alt='Expand your possibilities with CC add ons' src='plugin.jpg'></a>
        </td>
        <td align=center width='555'>
            <span class='pagetitle'><?php echo domain.': '.$title;?></span>
        </td>
        <td width='40'>
            <a href="helpdesk.php"><img alt='Help' border='0' src='help.jpg' width='40'></a>
        </td>
        </tr>
        <tr>
        <td colspan='3' id='header_quickmenu'>
            <a href='usermanager.php' accesskey='u'><img class='header_button' alt='View/Edit Users Shortcut: ALT-U ENTER' src='users.jpg'></a>
            <a href='transactions.php' accesskey='l'><img class='header_button' alt='Tranaction Ledger Shortcut: ALT-L ENTER' src='ledger.jpg'></a>
            <a href='massmail.php' accesskey='m'><img class='header_button' src='sendmail.jpg' alt='Send eMail Shortcut: ALT-M ENTER'></a>
            <a href='backup.php' accesskey='b'><img class='header_button' src='backup.jpg' alt='Backup MySQL Shortcut: ALT-B ENTER'></a>
            <div class='header_logout' ><a href='logout.php' accesskey='o'></a></div>
        </td>
        </tr>
        </table>
     </div>
</td>
</tr>
</table>


<table class='content_table' cellpadding='0' cellspacing='0'>
<tr>
<td>
<!-- CONTENT START -->

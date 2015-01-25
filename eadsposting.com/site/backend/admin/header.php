<?php
//LDF SCRIPTS
if (!defined('version'))
exit('Access Denied');
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=<?php echo charset;?>">
    <link href="/backend/admin/admin.css" rel="stylesheet" type="text/css">
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
<table class='header_table' cellpadding='0' cellspacing='0' width=1024>
<tr>
<td valign='top'>
    <ul class="admin_mainmenu">
    <li class="top_level_link"><a href="/backend/admin/index.php">Main</a>
    </li>
 <?php
 
 $admin_sections=scandir(BACKEND_PATH.'/admin/Admin_Sections');
 
 foreach ($admin_sections as $section)
 	{
 	
	if (strpos('.'.$section,'menu.item.'))
		{
    	echo '<li>'.str_replace('menu.item.','',str_replace('_',' ',$section)).'<ul>';
		$admin_options=scandir(BACKEND_PATH.'/admin/Admin_Sections/'.$section);
	
		foreach ($admin_options as $option)
			{
			if (strpos('.'.$option,'menu.item.'))
    		echo '<li><a href="/backend/admin/Admin_Sections/'.$section.'/'.$option.'">'.substr(str_replace('menu.item.','',str_replace('_',' ',$option)),0,-4).'</a></li>';
  			}	
 
 		echo '</ul></li>';
		}
 	}
 
 ?>

    <li>Security Options
        <ul>
        <li><a href="/backend/admin/password.php">Set Admin Password</a></li>
        <li><a href="/backend/admin/securitylevels.php">Security Levels</a></li>
        <li><a href="/backend/admin/captcha.php">Captcha Settings</a></li>
        <li><a href="/backend/admin/browser.php">Block certain browsers</a></li>
        <li><a href="/backend/admin/ips.php">Block IPs and host names</a></li>
        </ul>
    </li>
    <li>Site settings
        <ul>
        
        <li><a href="/backend/admin/charset.php">Set Default Character Set</a></li>
        <li><a href="/backend/admin/urlsanddirs.php">Set URLs and Directories</a></li>
       
        <li><a href="/backend/admin/emailsettings.php">Site Settings</a></li>
    
        <li><a href="/backend/admin/bksettings.php">MySQL Backup Settings</a></li>
        <li><a href="/backend/admin/othersettings.php">Other Settings</a></li>
        
        <li><a href="/backend/admin/reminders.php">Admin reminders/notes</a></li>
       
        <li><a href="/backend/admin/states.php">State Selections</a></li>
        <li><a href="/backend/admin/countries.php">Country Selections</a></li>
        </ul>
    </li>
    <li>Database Utilities
        <ul>
        <li><a href="/backend/admin/backup.php">Backup MySQL Data</a></li>
        <li><a href="/backend/admin/restore.php">Restore MySQL Data</a></li>
        <li><a href="/backend/admin/maillist.php">Download eMail Addresses</a></li>
        </ul>
    </li>
    <li class="top_level_link"><a href="/backend/admin/logout.php">Log out</a>
    </li>
    </ul>

</td>
  
<td valign='top' align='center'>

            <img src=/backend/admin/website_logo.png>

</td></tr>

<tr><td colspan=2>
<table class='content_table' cellpadding='0' cellspacing='0' width=1024>

<tr>
<td bgcolor='#2B86B7' align=center>
    <span style='color: white; font-size: 130%; font-weight: bold;text-shadow: black 0.1em 0.1em 0.2em'><?php echo $title;?></span>
</td>
</tr>
<tr>
<td>
<!-- CONTENT START -->

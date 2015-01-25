<?php
if(empty($_GET['state']))
    $_GET['state'] = '';
if(empty($_POST['state']))
    $_POST['state'] = '';

$domain = str_replace('www.','',$_SERVER['HTTP_HOST']);
require_once('../conf.inc.php');
//-----------------------------------------
// Test if the script is installed already
//-----------------------------------------
@mysql_connect($mysql_hostname, $mysql_user, $mysql_password);
if (@mysql_select_db($mysql_database))
{
    list($pw) = @mysql_fetch_array(@mysql_query("SELECT value FROM ".$mysql_prefix."system_values WHERE name = 'admin password' LIMIT 1"));
    if(!empty($pw))
    {
        if($_GET['state'] != 'post') die("Script already installed");
    }
}
if($_GET['state'] == 'post' OR $_POST['state'] == 'install')
    require_once('functions.inc.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
    <link href="admin.css" rel="stylesheet" type="text/css">
    <title>CashCrusader Installer</title>
    <style type='text/css'>
        .text_input {
            width: 300px;
        }
    </style>
    </head>
<body>
<table class='centered' cellpadding='0' cellspacing='0'>
<tr>
  
<td valign='top'>
    <div class='header_div'>
        <div class='header_picture'></div>
        <table border='0' cellpadding='0' cellspacing='0'>
        <tr>
        <td align=center width='555'>
            <div class='pagetitle' style='padding: 10px;'>CashCrusader Installer</div>
        </td>
        </tr>
        </table>
     </div>
</td>
</tr>
</table>

<br>


<table style='width: 590px ! important' class='content_table' cellpadding='0' cellspacing='0'>
<tr>
<td>

    <div class='centered' style='font-weight: bold ! important; border: 2px solid black; margin: 10px 30px; padding: 5px 15px;'>
        <p style='text-align: justify'>This script (CashCrusader) is copyrighted to 
        CashCrusader Software. Selling duplications of this 
        script is a violation of the copyright and purchase 
        agreement unless you have received approval from  
        CashCrusader Software before doing so.</p>
        
        <p style='text-align: justify'>LICENSES CAN NOT BE TRANSFERRED TO A NEW DOMAIN NAME
        ONCE THE SITE HAS AN ESTABLISHED INTERNET PRESENCE.</p>
        
        <p style='text-align: justify'>Alteration of this script in any way voids any
        responsibility CashCrusader Software has towards the
        functioning of the script.</p>
    </div>

<?php

if(empty($_GET['state']) AND empty($_POST['state']))
{
?>

<form action='<?php echo $_SERVER['PHP_SELF'];?>' method='GET'>
<input type='hidden' name='state' value='config'>
<table width='90%' border='0' class='centered'>
<tr>
<td align='left' colspan='2'>
    <hr>
</td>
</tr>

<tr>
<td align='left' colspan='2'>
    <h2>Pre Install Tasks</h2>
</td>
</tr>

<tr>
<td align='left' colspan='2'>
    1. Create a MySQL database from your hosting control panel<br>
    2. Create a MySQL user<br>
    3. Give all privileges for above user for above database<br>
    4. Edit <i>/scripts/conf.inc.php</i> with appropriate information<br>
    5. Create following email addresses<br>
&nbsp;&nbsp;&nbsp;&nbsp;support@<?php echo $domain;?><br>
&nbsp;&nbsp;&nbsp;&nbsp;advertising@<?php echo $domain;?><br>
&nbsp;&nbsp;&nbsp;&nbsp;redemptions@<?php echo $domain;?><br>
<hr>

</td>
</tr>

<?php
} else if($_GET['state'] == 'config') { 
?> 

<form action='<?php echo $_SERVER['PHP_SELF'];?>' method='POST'>
<input type='hidden' name='state' value='install'>
<table width='90%' border='0' class='centered'>
<tr>
<td align='left' colspan='2'>
    <hr>
</td>
</tr>

<tr>
<td align='left' colspan='2'>
    <h2>Configuration</h2>
</td>
</tr>

<tr>
<td align='left' colspan='2'>
    &nbsp;<b>These settings are edited from /scripts/conf.inc.php</b>
</td>
</tr>

<tr>
<td>
    MySQL host:
</td>
<td>
    <?php echo $mysql_hostname;?>
</td>
</tr>

<tr>
<td>
    MySQL username:
</td>
<td>
    <?php echo $mysql_user;?>
</td>
</tr>

<tr>
<td>
    MySQL password:
</td>
<td>
    <?php echo $mysql_password;?>
</td>
</tr>

<tr>
<td>
    MySQL database:
</td>
<td>
    <?php echo $mysql_database;?>
</td>
</tr>

<tr>
<td align='center' colspan='2'>
    <hr>
</td>
</tr>

<tr>
<td align='left' colspan='2'>
    <h2>Site Details</h2>
</td>
</tr>

<tr>
<td>
    Site name:
</td>
<td>
    <input class='text_input' type='text' name='site_name' value='<?php echo $domain;?>'>
</td>
</tr>

<tr>
<td>
    Domain:
</td>
<td>
    <input class='text_input' type='text' name='domain' value='<?php echo $domain;?>'>
</td>
</tr>

<tr>
<td>
    License key:
</td>
<td>
    <input class='text_input' type='text' name='key' value=''>
</td>
</tr>

<tr>
<td>
    Serial number:
</td>
<td>
    <input class='text_input' type='text' name='serial' value=''>
</td>
</tr>

<tr>
<td align='center' colspan='2'>
    <hr>
</td>
</tr>

<tr>
<td align='left' colspan='2'>
    <h2>Administrator</h2>
</td>
</tr>

<tr>
<td>
    Admin password:
</td>
<td>
    <input class='text_input' type='text' name='password' value=''>
</td>
</tr>

<tr>
<td>
    Confirm password:
</td>
<td>
    <input class='text_input' type='text' name='password_confirm' value=''>
</td>
</tr>

<tr>
<td align='center' colspan='2'>
    <hr>
</td>
</tr>

<?php
} else if($_POST['state'] == 'install') {
?> 
<form action='<?php echo $_SERVER['PHP_SELF'];?>' method='GET'>
<input type='hidden' name='state' value='post'>
<table width='90%' border='0' class='centered'>
<tr>
<td align='left' colspan='2'>
    <hr>
</td>
</tr>

<tr>
<td align='left' colspan='2'>
    <h2>Install</h2>
</td>
</tr>

<tr>
<td align='left' colspan='2'>
    <?php
    //------------------------------------------------------
    // Verify input
    //------------------------------------------------------
    $ok = true;
    if($_POST['password'] != $_POST['password_confirm'])
    {
        echo "<span style='color: red'>Passwords do not match!</span> <a href='installer.php?state=config'>[Back]</a><br><br>\n";
        $ok = false;
    } else if(empty($_POST['password']))
    {
        echo "<span style='color: red'>You did not enter password!</span> <a href='installer.php?state=config'>[Back]</a><br><br>\n";
        $ok = false;
    } else if(empty($_POST['key']))
    {
        echo "<span style='color: red'>You did not license key!</span> <a href='installer.php?state=config'>[Back]</a><br><br>\n";
        $ok = false;
    } else if(empty($_POST['serial']))
    {
        echo "<span style='color: red'>You did not serial key!</span> <a href='installer.php?state=config'>[Back]</a><br><br>\n";
        $ok = false;
    }
    else
    {
        //------------------------------------------------------
        // Verify that we are licensed
        //------------------------------------------------------
        $domain = trim($_POST['domain']);
        $serial =trim ($_POST['serial']);
        $key = trim ($_POST['key']);
        if (md5($domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5($domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!= $serial)
        {
            echo "<span style='color: red'>Invalid serial number!</span> <a href='installer.php?state=config'>[Back]</a><br>\n";
            $ok = false;
        }

    }
    //------------------------------------------------------
    // Perform install
    //------------------------------------------------------
    if($ok)
    {
        //-------------------------------------------------
        // MySQL connection
        //-------------------------------------------------
        echo "Connecting to MySQL...";
        if (!@mysql_connect($mysql_hostname, $mysql_user, $mysql_password))
        {
            echo " <span style='color: red'>ERROR</span><br><br>";
            echo "Error message from MySQL:<br> <b>".mysql_error()."</b><br><br>Check your <i>conf.inc.php</i> settings. <a href='installer.php?state=config'>[Back]</a><br><br>";
            exit;
        }else{
            echo " <span style='color: green'>OK</span><br>\n";
        }
        echo "Selecting MySQL database...";
        if (!@mysql_select_db($mysql_database))
        {
            echo " <span style='color: red'>ERROR</span><br><br>";
            echo "Error message from MySQL:<br> <b>".mysql_error()."</b><br><br>Check your <i>conf.inc.php</i> settings. <a href='installer.php?state=config'>[Back]</a><br><br>";
            exit;
        }else{
            echo " <span style='color: green'>OK</span><br>\n";
        }
        //-------------------------------------------------
        // Database
        //-------------------------------------------------
        echo "Populating database...";
        include('create.php');
        if(mysql_error() != '')
        {
            echo " <span style='color: red'>ERROR</span><br>\n";
            echo mysql_error() ."<br>";
            exit;
        }else{
            echo " <span style='color: green'>OK</span><br>\n";
        }
        //-------------------------------------------------
        // Admin login
        //-------------------------------------------------
        echo "Creating admin login...";
        mysql_query("REPLACE INTO ".mysql_prefix."system_values SET name='admin password',value='".password($_POST['password'])."'");
        if(mysql_errno() != '')
        {
            echo " <span style='color: red'>ERROR</span><br>\n";
            echo mysql_error() ."<br>";
            exit;
        }else{
            echo " <span style='color: green'>OK</span><br>\n";
        }
        //-------------------------------------------------
        // License
        //-------------------------------------------------
        echo "Saving license... ";
        mysql_query("REPLACE INTO ".mysql_prefix."system_values SET name='serialnumber',value='$serial'");
        if(mysql_errno() != '')
        {
            echo " <span style='color: red'>ERROR</span><br>\n";
            echo mysql_error() ."<br>";
            exit;
        }else{
            //echo " <span style='color: green'>OK</span><br>\n";
        }
        mysql_query("REPLACE INTO ".mysql_prefix."system_values SET name='key',value='$key'");
        if(mysql_errno() != '')
        {
            echo " <span style='color: red'>ERROR</span><br>\n";
            echo mysql_error() ."<br>";
            exit;
        }else{
            echo " <span style='color: green'>OK</span><br>\n";
        }
        //-------------------------------------------------
        // Site name
        //-------------------------------------------------
        echo "Naming site...";
        mysql_query("REPLACE INTO ".mysql_prefix."system_values SET name='site_name',value='".mysql_real_escape_string($_POST['site_name'])."'");
        if(mysql_errno() != '')
        {
            echo " <span style='color: red'>ERROR</span><br>\n";
            echo mysql_error() ."<br>";
            exit;
        }else{
            echo " <span style='color: green'>OK</span><br>\n";
        }
    }
  
    ?>
</td>
</tr>
<tr>
<td align='left' colspan='2'>
    <hr>
</td>
</tr>
<?php
} else if($_GET['state'] == 'post') { 
?> 

<form action='index.php' method='GET'>
<input type='hidden' name='state' value='post'>
<table width='95%' border='0' class='centered'>
<tr>
<td align='left' colspan='2'>
    <hr>
</td>
</tr>

<tr>
<td align='left' colspan='2'>
    <h2>Post Install Tasks</h2>
</td>
</tr>

<tr>
<td align='left' colspan='2'>
    1. Make following command to run every 10 minutes (cronjob):<br>
lynx --dump <?php echo system_value('scripts_url');?>admin/cronjobs.php &gt; /dev/null<br>
<br>
    2. Delete <i>/scripts/converters/</i> folder<br>
</td>
</tr>
<tr>
<td align='left' colspan='2'>
    <hr>
</td>
</tr>

<?php } ?>

<tr>
<td colspan='2' align='center'>
    <input type='submit' value='Continue'></form>
</td>
</tr>
</table>

</body>
</html>
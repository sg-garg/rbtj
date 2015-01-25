<?php
include("functions.inc.php");
$title='CC Updater';
admin_login();
define("autoupdater",1);

echo " <hr><b>[Testing for update requirements]</b><br>";
//------------------------------------------------------
// Test safemode
//------------------------------------------------------
echo "Testing for safe mode: ";
if (ini_get('safe_mode'))
{
    echo "<font color='red'>error</font><br><br>";
    echo "<br><font color=red>Your server is running in Safe Mode. Safe mode is not really the most affective way to secure websites on a server. Hosting that offers CHROOT security, and NO SSH access is a much safer configuration. Your site's security is relying on Safe Mode protection and because of this the update utility would pose a security risk. The updater requires the permissions on the scripts dir and everything in it to be set to 777<br>
            <br>Cash Crusader Updater disabled... Sorry. ";
    footer();
}
echo "<b>disabled, good</b><br>";

//------------------------------------------------------
// Test folder permissions
//------------------------------------------------------
echo "Testing for permissions: ";
if (!($tempfile=@fopen('../permtest.tmp',"w")))
{
    $baddir[]=scripts_dir;
}
else {
    fclose($tempfile);
    unlink ('../permtest.tmp');
}
    
if (!($tempfile=@fopen('../admin/permtest.tmp',"w")))   
{
    $baddir[]=scripts_dir.'admin/';
}
else {
    fclose($tempfile);
    unlink ('../admin/permtest.tmp');
}
    
if (!($tempfile=@fopen('../admin/cronjobs/permtest.tmp',"w")))
{
    $baddir[]=scripts_dir.'admin/cronjobs/';
}
else {
    fclose($tempfile);
    unlink ('../admin/cronjobs/permtest.tmp');
}

if (!($tempfile=@fopen('../admin/ioncube/permtest.tmp',"w")))
{
    $baddir[]=scripts_dir.'admin/ioncube/';
}
else {
    fclose($tempfile);
    unlink ('../admin/ioncube/permtest.tmp');
}


if (!($tempfile=@fopen('../images/permtest.tmp',"w")))  
{ 
    $baddir[]=scripts_dir.'images/';
}
else {
    fclose($tempfile);
    unlink ('../images/permtest.tmp');
}

if (!($tempfile=@fopen('../includes/permtest.tmp',"w")))  
{ 
    $baddir[]=scripts_dir.'includes/';
}
else {
    fclose($tempfile);
    unlink ('../includes/permtest.tmp');
}
    
if (!($tempfile=@fopen('../plugins/permtest.tmp',"w")))
{   
    $baddir[]=scripts_dir.'plugins/';
}
else {
    fclose($tempfile);
    unlink ('../plugins/permtest.tmp');
}

if (!($tempfile=@fopen('../templates/permtest.tmp',"w")))
{
   $baddir[]=scripts_dir.'templates/';
}
else {
   fclose($tempfile);
   unlink ('../templates/permtest.tmp');
}
    
if ($baddir)
{ 
    echo "<font color='red'>error(s), aborting update</font><br><br>";
    echo "CashCrusader must have write permission to <i>".scripts_dir."</i> and all directories within it. Usually this means you must to chmod them to 777.<br>";
    echo "The following directories need their permissions fixed:<br>";
    foreach ($baddir as $k) 
    {
        echo '<b>'.$k.'</b><br>';
    }
    footer();
}
echo "<b>done</b><br>";

//------------------------------------------------------
// Test for TAR
//------------------------------------------------------
echo "Testing for tar: ";

if(!function_exists(exec))
{
    echo "<font color='red'>exec() function disabled from server, aborting update</font><br>";
    footer();
}
else
{
    exec("which tar", $output, $ret_val);

    if( is_array($output) )
    {
        $output = implode($output);
    }

    if(preg_match("/no tar in/i", $output))
    {
        echo "<font color='red'>TAR not found from PATH, please contact your hosting provider about tar on this server.</font><br>";
        footer();
    }
    else if(empty($output))
    {
        echo "<font color='red'>server returned blank. Assuming <b>/bin/tar</b>. If autoupdate fails, please contact your hosting provider about tar on this server.</font><br>";
        $tar = '/bin/tar';
    }
    else
    {
        $tar = $output;
        echo "<b>$tar</b><br>";
    }
}

//------------------------------------------------------
// Remove old log file
//------------------------------------------------------
echo "Removing old log: ";

if(file_exists("../tar_error.log"))
{
    if(!unlink("../tar_error.log"))
    {
        echo "<b><font color='red'>error</font></b><br><br>";
        echo "Unable to delete <b>".scripts_dir."tar_error.log</b>. Please delete it manually ad re-run the update.<br>";
        footer();
    }else{
        echo "<b>done</b><br>";
    }
}else{
    echo "<font color='grey'><i>log does not exist [ok]</i></font><br>";
}

//------------------------------------------------------
// Do the install
//------------------------------------------------------
echo " <hr><b>[Downloading update]</b><br>";
//echo "<a href=http://cashcrusadersoftware.com/news.php?domain_name=".system_value('domain')."&scripts=".scripts_url."&key=".system_value("key")."&ins=1 target=_new>Please visit the update site to see if there are any additional instructions</a><br><br>";



//------------------------------------------------------
// Verify that we are on licensed site
//------------------------------------------------------
$fp3 = @fsockopen("cashcrusadersoftware.com", 80,$errno, $errstr, 10);
echo "Verifying license: ";
fputs($fp3,"GET /verifylicense.php?domain=".system_value('domain')."&key=".key." HTTP/1.0\r\nHost: cashcrusadersoftware.com\r\n\r\n");
socket_set_timeout($fp3, 10);
$tmp = "";
$write = 0;
while(!feof($fp3)) 
{
    $line = fread($fp3, 1024);
    $tmp .= $line;
}
$tmp2 = explode('||',$tmp);
$status = $tmp2[1];
$md5 = $tmp2[2];
$versionavailable = $tmp2[3];
if($status == "good")
{
    echo "<b>done</b><br>";
}
else if($status == "expired")
{
    echo "<font color='red'>your license key has expired, please renew if you want to update, aborting</font><br>";
    footer();
    exit();
}
else
{
    echo "<font color='red'>'<b>".key."</b>' was not valid license key, aborting</font><br>";
    footer();
    exit();
}
fclose($fp3);


//------------------------------------------------------
// Download the install
//------------------------------------------------------
echo "Connecting update server: ";
$fp1 = @fsockopen("cashcrusadersoftware.com", 80,$errno, $errstr, 10);
if(!$fp1) 
{
    echo '<font color="red">could not connect to update server, aborting update</font> ('.$errno.': '.$errstr.')<br>';
    footer();
} 
else 
{
    echo "<b>connected</b><br>";
    echo "Downloading update $versionavailable: ";

    fputs($fp1,"GET /news.php?domain_name=".system_value('domain')."&scripts=".scripts_url."&key=".key."&ts=".unixtime."&gettar=2 HTTP/1.0\r\nHost: cashcrusadersoftware.com\r\n\r\n");
    socket_set_timeout($fp1, 10);
    $fp2 = fopen("../ccupdate.tar.gz","wb");
    if(!$fp2)
    {
        echo "<font color='red'>failed to save the update file to server</font><br>";
        footer();
    }
    else
    {
        $write = 0;
        while(!feof($fp1)) 
        {
            $line = fread($fp1, 1024);
            if (!$write)
            { 
                list(,$line) = explode('<!--start-->',$line);
            }
            if (!$write and preg_match("/Invalid Licence Key/",$line))
            {
                $showline=1;
            }
            if ($showline)
            {
                echo $line;
            } 
            else 
            {
                fputs($fp2,$line,strlen($line));
                $write=1;
            }
        }
        //echo getcwd() . "\n";
        chdir ("../");
        //echo getcwd() . "\n";
    }
}
fclose($fp1);
fclose($fp2);

if (!file_exists("ccupdate.tar.gz"))
{
    echo "<font color='red'>failed to save the update file to server or it was removed before installation</font><br>";
    footer();
}
echo "<b>done</b><br>";

//------------------------------------------------------
// Verify MD5sum
//------------------------------------------------------
echo "Verify MD5sum: ";
$hash = md5_file("ccupdate.tar.gz");
if($hash != $md5)
{
    echo "<font color='red'>mismatch, corrupted update file, aborting update</font> Hashes: $hash vs $md5<br>";
    footer();
    exit();
}
else
{
    echo "<b>done</b><br>";
}



if (!$showline)
{
    //------------------------------------------------------
    // Perform the install
    //------------------------------------------------------
    echo " <hr><b>[Install update]</b><br>";
    echo "Uncompress update: ";

    if(!exec("$tar xvzf ccupdate.tar.gz 2> tar_error.log", $output, $ret_val))  
    {
       echo "<font color='red'>error</font><br>";
       $error = file_get_contents("tar_error.log");
       echo "Tar_error.log: <b>$error</b><br>";
       footer();
    }

    echo "<b>done</b><br>";

    
    //------------------------------------------------------
    // Install done
    //------------------------------------------------------
    echo " <hr><b>[Verify updated files]</b><br>";
    echo "Verify: ";
    $output = "";
    exec("$tar -dvzf ccupdate.tar.gz | grep -i 'size differs'", $output, $ret_val);  
    $output = implode($output);
    if(empty($output))
    {
        echo "<font color='green'><b>done</b></font><br>";
        $skip_mysql = FALSE;
    }
    else
    {
        $output = str_replace("differs", "differs<br>", $output);
        echo "<font color='red'>difference(s) occured, errors updating some files.<br>Please verify your server has permission to update your CashCrusader and re-run the autoupdate to make sure you do not end up having crippled installation. Contact your webhosting provider if you are unable to get server to let CashCrusader overwrite old files.</font><br>Output:<br>$output";
        if(substr_count($output,"updatecheck.php") >= 1)
        {
            $skip_mysql = TRUE;
        }

    }
    
    unlink("ccupdate.tar.gz");
    chdir('admin');

    //------------------------------------------------------
    // Install done
    //------------------------------------------------------
    echo " <hr><b>[Perform possible database update]</b><br>";
    if($skip_mysql)
        echo "<font color='red'>There was an error updating MySQL updatefile, skipping MySQL update. Please make sure your server has permission to update your CashCrusader files and re-run the autoupdate to make sure you do not end up having crippled installation.</font><br>";
    else
        include("updatecheck.php");

    echo "<hr><b>[Done]</b><br>";
    echo "Visit <a href='http://cashcrusadersoftware.com/changelog.php' target='_blank'>changelog</a> for information what has been changed and if there are anything you must do after the update.";

}
footer();


?>

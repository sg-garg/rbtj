<?php
//LDF SCRIPTS
if (!defined('ranfromcron'))
{
    include("functions.inc.php");

    
    error_reporting(E_ALL ^ E_NOTICE);
    $noheader=1;
    admin_login();
}

$backup_folder = system_value('bklocalpath');

//--------------------------------------
// Make sure folder has backslash at the end
//--------------------------------------
$backup_folder = trim($backup_folder);
if(substr($backup_folder, -1, 1) != "/")
{
    $backup_folder = $backup_folder ."/";
}

ob_end_clean();

if (ini_get('safe_mode'))
{
    if (defined('ranfromcron'))
    {
        echo date("H:i:s") . " Your server is running in safe mode. The backup feature provided with the CC scripts will not run in safe mode. Please use the MySQL backup features provided by your hosting company.<br>\n";
        return;
    }
    
    echo date("H:i:s") . " Your server is running in safe mode. The backup feature provided with the CC scripts will not run in safe mode. Please use the MySQL backup features provided by your hosting company.<br>\n";
    exit;
}

set_time_limit(0);
if ($_SESSION['tmpfname'] && !$_GET['tmpfname'])
{
    $_GET['tmpfname']=$_SESSION['tmpfname'];
    unset($_SESSION['tmpfname']);
}
if ($_GET['tmpfname'])
{
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");
    header("Content-Transfer-Encoding: binary");
    header('Content-disposition: attachment; filename=' . $mysql_database . '-' . mysqldate . '.sql.gz;');
    header("Content-Length: " . filesize($_GET['tmpfname']));
    readfile($_GET['tmpfname']);
    unlink($_GET['tmpfname']);
    mysql_query("replace into " . mysql_prefix . "system_values set name='last_backup',value='" . unixtime . "'");
    
    exit;
}

$file_name=$mysql_database . '-' . mysqldate . '.sql';

if (function_exists('gzopen'))
{
    echo date("H:i:s") . " gzip <b>available</b><br>\n";
    $file_name=$file_name . '.gz';
    $gz   =1;
}
else
{
    echo date("H:i:s") . " gzip <b>unavailable</b><br>\n";
}




//--------------------------------------
// Define backup file to write to
//--------------------------------------
$tmpfname=ini_get('session.save_path') . '/sess_' . substr(md5(system_value('domain')), 0, 18) . mysqldate;
if ($gz)
{
    $handle=@gzopen($backup_folder . $file_name, "w9");
    
    if ($handle)
    {
        $tmpfname=$backup_folder . $file_name;
    } else {
        echo date("H:i:s") . " unable to write/open file <b>.". $backup_folder . $file_name."</b> (check your permissions), trying session save path...<br>\n";
        $handle=@gzopen($tmpfname, "w9");
    }
} else {
    if (defined('ranfromcron'))
    {
        $handle=@fopen($backup_folder . $file_name, "w");
        
        if ($handle)
        {
            $tmpfname=$backup_folder . $file_name;
        } else {
            echo date("H:i:s") . " unable to write/open file <b>.". $backup_folder . $file_name."</b> (check your permssions), trying session save path...<br>\n";
            $handle=@fopen($tmpfname, "w");
        }
    }
}


if (!$handle)
{
    echo date("H:i:s") . " unable to write/open file <b>$tmpfname</b>, aborting backup<br>\n";

}else{
    
    echo date("H:i:s") . " using file <b>$tmpfname</b><br>\n";
    
    if (!$gz and !defined('ranfromcron'))
    {
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-disposition: attachment; filename=' . $mysql_database . '-' . mysqldate . '.sql;');
    }
    
    //--------------------------------------
    // Write data to backup file
    //--------------------------------------
    function write_to_backup($data)
    {
        global $handle, $gz;
    
        if ($gz)
        {
            gzwrite($handle, $data);
        } else {
            if (!$handle)
            {
                echo $data;
            } else {
                fwrite($handle, $data);
            }
        }
    
        return 0;
    }
    
    function get_def($table)
    {
        global $gz, $handle, $mysql_hostname, $mysql_user, $mysql_password, $mysql_database;
    
        mysql_connect($mysql_hostname, $mysql_user, $mysql_password);
        mysql_select_db($mysql_database);
        
        $result = mysql_query("SHOW CREATE TABLE `$table`");
    
        $def = mysql_fetch_array($result);
        $def = $def[1];
        return($def.";");
    }
    
    function write_content($table)
    {
        global $nlcounter,$gz, $handle, $mysql_hostname, $mysql_user, $mysql_password, $mysql_database;
        $content="";
        mysql_connect($mysql_hostname, $mysql_user, $mysql_password);
        mysql_select_db($mysql_database);
        $result=mysql_unbuffered_query("SELECT * FROM $table");
        
        while ($row=mysql_fetch_row($result))
        {
            $insert = "INSERT INTO `$table` VALUES (";
            
            for ($j=0; $j < mysql_num_fields($result); $j++)
            {
                if (!isset($row[$j]))
                {
                    $insert.="NULL,";
                } else if ($row[$j] != "")
                {
                    $insert.="'" . addslashes($row[$j]) . "',";
                } else {
                    $insert.="'',";
                }
            }
            
            $insert=preg_replace("(,$)", "", $insert);
            $insert.=");\n";
            if ($handle)
            {
                if ($counter>5000)
                {
                    echo '.';
                    flush();
                    $counter=0;
                    $nlcounter++;
                }
                $counter++;
            }
            if ($nlcounter>120)
            {
                $nlcounter=0;
                echo '<br>';
            }
            
            write_to_backup($insert);
        }
    }
    
    $cur_time  =date("Y-m-d H:i");
    $tables    =mysql_query("show tables");
    $num_tables=mysql_num_rows($tables);
    $i         =0;
    
    echo date("H:i:s") . " writing database tables to file...<br>\n";
    write_to_backup("-- -----------------------------\n");
    write_to_backup("-- CashCrusader v". version ." backup\n");
    write_to_backup("-- -----------------------------\n\n");
    
    //--------------------------------------
    // Loop thru all database tables
    //--------------------------------------
    while ($i < $num_tables)
    {
        $table = mysql_tablename($tables, $i);
        mysql_query('repair table '.$table);
    
        if ($handle)
        {
            echo '.';
            flush();
            $nlcounter++;
        }
        if ($nlcounter>120)
        {
            $nlcounter=0;
            echo '<br>';
        }
    
        write_to_backup(get_def($table));
        write_to_backup("\n\n");
        write_content($table);
        write_to_backup("\n\n");
        
        $i++;
    }
    
    write_to_backup("-- Backup complete");
    echo "<br>".date("H:i:s") . " database tables written<br>\n";
    
    if ($handle)
    {
        if ($gz)
        {
            gzclose($handle);
        } else {
            fclose($handle);
        }
    }
    
    echo date("H:i:s") . " backup size <b>". number_format(filesize($tmpfname)/1024,2) ."</b> kilobytes<br>\n";
    echo date("H:i:s") . " database backup completed<br>\n";
    
    if ($gz && !defined('ranfromcron'))
    {
        $_SESSION['tmpfname']=$tmpfname;
        echo "\n".'<head>
                <noscript>
                <META HTTP-EQUIV="REFRESH" CONTENT="1;URL=backup.php?tmpfname='.$tmpfname.'">
                </noscript>
                ';
        ?>
        
        <script language = "JavaScript">
        <!--
        
        var sURL = '<?php echo 'backup.php?tmpfname='.$tmpfname;?>';
        
        setTimeout("refresh()", 1000 );
        
        function refresh()
        {
            window.location.href = sURL;
        }
        //-->
        </script>
        
        <script language="JavaScript1.1">
        <!--
        function refresh()
        {
            window.location.replace(sURL );
        }
        //-->
        </script>
        </head><?php
        exit;
    }
    
    if (defined('ranfromcron'))
    {
        if (preg_match('/@/', bkmail))
        {
            $send_to      =bkmail;
            $send_from    =support_email;
            $subject      ="MySQL Database (" . $mysql_database . ") Backup - " . date("j F Y");
            $fileatt_type =filetype($tmpfname);
            $headers      ="From: $send_from";
            $fp           =fopen($tmpfname, 'rb');
            $data         =fread($fp, filesize($tmpfname));
            fclose($fp);
            $semi_rand    =md5(unixtime);
            $mime_boundary="==Multipart_Boundary_x{$semi_rand}x";
            $headers.="\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n"
            . " boundary=\"{$mime_boundary}\"";
            $message="This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n"
            . "Content-Type: text/plain; charset=\"iso-8859-1\"\n"
            . "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";
            $data=chunk_split(base64_encode($data));
            $message.="--{$mime_boundary}\n" . "Content-Type: application/octetstream;\n"
            . " name=\"{$file_name}\"\n" . "Content-Disposition: attachment;\n"
            . " filename=\"{$file_name}\"\n" . "Content-Transfer-Encoding: base64\n\n" . $data
            . "\n\n" . "--{$mime_boundary}--\n";
            
            echo date("H:i:s") . " emailing backup to <b>". bkmail ."</b>...<br>\n";
            if (force_return != 'YES')
            {
                $ok=mail($send_to, $subject, $message, $headers);
            } else {
                $ok=mail($send_to, $subject, $message, $headers, '-f' . $send_from);
            }
        }
        
        if (defined('bkftpserver') && defined('bkftpuser') && defined('bkftppass') && bkftpserver && bkftpuser && bkftppass)
        {
            if ($gz)
            {
                $mode=FTP_BINARY;
            } else {
                $mode=FTP_ASCII;
            }
            
            echo date("H:i:s") . " transferring backup to <b>". bkftpserver ."</b>...<br>\n";
            $ftp_id      =ftp_connect(bkftpserver);
            $login_result=ftp_login($ftp_id, bkftpuser, bkftppass);
            $ftpok       =ftp_put($ftp_id, bkftppath . '/' . $file_name, $tmpfname, $mode);
            ftp_close($ftp_id);
    
            if(!$ftpok)
            {
                echo date("H:i:s") . " transfer failed<br>\n";
            }else
            {
                echo date("H:i:s") . " transfer done<br>\n";
            }
            
            if (!$ok)
            {
                $ok=$ftpok;
            }
        }
    }
    
    if ($tmpfname)
    {
        unlink($tmpfname);
    }
    
    if (!defined('ranfromcron'))
    {
        $ok=1;
    }
    
    if ($ok)
    {
        mysql_query("replace into " . mysql_prefix . "system_values set name='last_backup',value='" . unixtime . "'");
    }
}
?>





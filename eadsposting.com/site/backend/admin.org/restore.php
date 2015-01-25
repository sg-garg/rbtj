<?php
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
$title='MySQL Restore';
admin_login();

ini_set("auto_detect_line_endings", true);
$comment[0]="#";           
$comment[1]="--";
$error=false;

if (!$error)
{ $upload_max_filesize=ini_get("upload_max_filesize");
  if (preg_match("/([0-9]+)K/i",$upload_max_filesize,$tempregs)) $upload_max_filesize=$tempregs[1]*1024;
  if (preg_match("/([0-9]+)M/i",$upload_max_filesize,$tempregs)) $upload_max_filesize=$tempregs[1]*1024*1024;
  if (preg_match("/([0-9]+)G/i",$upload_max_filesize,$tempregs)) $upload_max_filesize=$tempregs[1]*1024*1024*1024;
}

if (!$error && isset($_POST["uploadbutton"]))
{ if (is_uploaded_file($_FILES["dumpfile"]["tmp_name"]) && ($_FILES["dumpfile"]["error"])==0)
  { 
    $uploaded_filename=str_replace(" ","_",$_FILES["dumpfile"]["name"]);
    $uploaded_filepath=str_replace("\\","/","../mysql_restore/".$uploaded_filename);
    	
    if (file_exists($uploaded_filename))
    { echo "<p>File $uploaded_filename already exist! Delete and upload again!</p>\n";
    }
    else if (!@move_uploaded_file($_FILES["dumpfile"]["tmp_name"],$uploaded_filepath))
    { echo "<p>Error moving uploaded file ".$_FILES["dumpfile"]["tmp_name"]." to the $uploaded_filepath</p>";
      echo "<p>Check the directory permissions for ".scripts_dir."mysql_restore (must be 777)!</p>";
    }
    else
    { echo "<p>Uploaded file saved as $uploaded_filename</p>";
    }
  }
  else
  { echo "<p>Error uploading file ".$_FILES["dumpfile"]["name"]."</p>";
  }
}



if (!$error && isset($_GET["delete"]))
{ if (@unlink('../mysql_restore/'.$_GET["delete"]))
    echo "<p>".$_GET["delete"]." was removed successfully</p>";
  else
    echo "<p>Can't remove ".$_GET["delete"]."</p>";
}


if (!$error && !isset($_GET["fn"]) && $filename=="")
{ if ($dirhandle = opendir('../mysql_restore')) 
  { $dirhead=false;
    while (false !== ($dirfile = readdir($dirhandle)))
    { if (preg_match('/\.sql/i',$dirfile))
      { if (!$dirhead)
        { echo "Below is a list of files located at:<br>".scripts_dir."mysql_restore/ <br><br>To prevent unauthorized access to these files, it is highly recomended that you delete these files once you have completed your restore.<table cellspacing=\"2\" cellpadding=\"2\">";
          $dirhead=true;
        }
        echo "<tr><td>$dirfile</td>";
        if (!preg_match("/\.gz$/",$dirfile) || function_exists("gzopen")) 
          echo "<td><a href=restore.php?start=1&fn=$dirfile&foffset=0&totalqueries=0>Start Restore</a></td>";
        else
          echo "<td>&nbsp;</td>";
        echo "<td><a href=restore.php?delete=$dirfile>Delete file</a></td></tr>";
      } 

    }
    if ($dirhead) echo "</table>";
    else echo "No backup files found at: <br>".scripts_dir."mysql_restore/";
    closedir($dirhandle); 
  }
  else
  { echo "<p>Error listing directory ".scripts_dir."mysql_restore/</p>";
    $error=$true;
  }
}


if (!$error && !isset($_GET["fn"]) && $filename=="")
{ 


  do { $tempfilename='../mysql_restore/'.unixtime.".tmp"; } while (file_exists($tempfilename));
  if (!($tempfile=@fopen($tempfilename,"w")))
  { echo "<p>Upload form disabled. Permissions for the working directory <i>".scripts_dir."mysql_restore/</i> <b>must be set to 777</b> in order ";
    echo "to upload files from here. Alternatively you can upload your backup files via FTP.</p>";
  }
  else
  { fclose($tempfile);
    unlink ($tempfilename);
 
    echo "<p>You can now upload your backup file up to $upload_max_filesize bytes (".round ($upload_max_filesize/1024/1024)." Mbytes)  ";
    echo "directly from your browser to the server. Alternatively you can upload your backup files of any size via FTP.</p>";
?>
<form method="POST" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $upload_max_filesize;?>">
<p>Backup file: <input type="file" name="dumpfile" accept="*/*" size=40"></p>
<p><input type="submit" name="uploadbutton" value="Upload"></p>
</form>
<?
  }
}



if (!$error && isset($_GET["fn"]))
{ 


  if (preg_match("/\.gz$/i",$_GET["fn"])) 
    $gzipmode=true;
  else
    $gzipmode=false;

  if ((!$gzipmode && !$file=fopen('../mysql_restore/'.$_GET["fn"],"rt")) || ($gzipmode && !$file=gzopen('../mysql_restore/'.$_GET["fn"],"rt")))
  { echo "<p>Can't open ".$_GET["fn"]." for import</p>";
    echo "<p>You have to upload the ".$_GET["fn"]." to the server</p>";
    $error=true;
  }


  else if ((!$gzipmode && fseek($file, 0, SEEK_END)==0) || ($gzipmode && gzseek($file, 0, SEEK_SET)==0))
  { if (!$gzipmode) $filesize = ftell($file);
    else $filesize = gztell($file); 
  }
  else
  { echo "<p>I can't get the filesize of ".$_GET["fn"]."</p>";
    $error=true;
  }
}



if (!$error && isset($_GET["start"]) && isset($_GET["foffset"]))
{
  echo "<b>Processing file: ".$_GET["fn"]."</b><br><br>";
  if (!$gzipmode && $_GET["foffset"]>$filesize)
  { echo "<p>Error: Can't set file pointer behind the end of file</p>";
    $error=true;
  }


  if (!$error && ((!$gzipmode && fseek($file, $_GET["foffset"])!=0) || ($gzipmode && gzseek($file, $_GET["foffset"])!=0)))
  { echo "<p>Error: Can't set file pointer to offset: ".$_GET["foffset"]."</p>";
    $error=true;
  }


  if (!$error)
  { $query="";
    $totalqueries=$_GET["totalqueries"];
    $linenumber=$_GET["start"];
    $inparents=false;
    while (($linenumber<$_GET["start"]+1500 || $query!="") 
       && ((!$gzipmode && $dumpline=fgets($file, 65536)) || ($gzipmode && $dumpline=gzgets($file, 65536))))
    { 
      

      $dumpline=preg_replace("/\r\n$/", "\n", $dumpline);
      $dumpline=preg_replace("/\r$/", "\n", $dumpline);
      $dumpline=str_replace("\\\\'","'",$dumpline); 
      $dumpline=str_replace("\\\\'","'",$dumpline); 

      $parents=substr_count ($dumpline, "'")-substr_count ($dumpline, "\\'");
      if ($parents % 2 != 0)
        $inparents=!$inparents;


      if (!$inparents)
      { $skipline=false;
        reset($comment);
        foreach ($comment as $comment_value)
        { if (!$inparents && (trim($dumpline)=="" || strpos ($dumpline, $comment_value) === 0))
          { $skipline=true;
            break;
          }
        }
        if ($skipline)
        { $linenumber++;
          continue;
        }
      }

      

      $query .= $dumpline;


      if (preg_match("/;$/",trim($dumpline)) && !$inparents)
      {
	!mysql_query(trim($query));
        $totalqueries++;
        $query="";
      }
      $linenumber++;
    }
  }

  if (!$error)
  { if (!$gzipmode) 
      $foffset = ftell($file);
    else
      $foffset = gztell($file);
    if (!$foffset)
    { echo "<p>Error: Can't read the file pointer offset</p>";
      $error=true;
    }
  }

    echo "Total queries performed: $totalqueries<br>";
    echo "Total KB processed: ".number_format($foffset/1024,0)." KB<br>";
    if ($linenumber<$_GET["start"]+1500)
    { echo "<p>Restore Complete</p><a href=restore.php>Click here to delete your backup file from the mysql_restore directory</a>";
      $error=true;
    }
    else
    { 
      echo "<script language=\"JavaScript\">window.setTimeout('location.href=\"".scripts_url."admin/restore.php?start=$linenumber&fn=".$_GET["fn"]."&foffset=$foffset&totalqueries=$totalqueries\";',1000);</script>";
      echo "<noscript>";
      echo "<p><a href=restore.php?start=$linenumber&fn=".$_GET["fn"]."&foffset=$foffset&totalqueries=$totalqueries>Continue from the line $linenumber</a> (Enable JavaScript to do it automatically)</p>";
      echo "</noscript>";
    }
  
}

if ($file && !$gzipmode) fclose($file);
else if ($file && $gzipmode) gzclose($file);

footer();

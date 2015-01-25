<?php
require_once'../conf.inc.php';
require_once'../functions.inc.php';
class SMTPMAIL
	{
		var $host="";
		var $port=25;
		var $error;
		var $state;
		var $con=null;
		var $greets="";
		
		function SMTPMAIL()
		{
			$this->host=ini_get("SMTP");
			$this->port=25;
			$this->state="DISCONNECTED";
		}
		function set_host($host)
		{
			$this->host=$host;
		}
		function set_port($port=25)
		{
			$this->port=$port;
		}
		function error()
		{
			return $this->error;
		}
		function connect($host="",$port=25)
		{
			if(!empty($host)) {
				$this->set_host($host);
            }
			$this->port=$port;
			if($this->state!="DISCONNECTED")
			{
				$this->error="Error : connection already open.";
				return false;
			}
			
			$this->con=fsockopen($this->host,$this->port,$errno,$errstr);
			if(!$this->con)
			{
				$this->error="Error($errno):$errstr";
				return false;
			}
			$this->state="CONNECTED";
			$this->greets=$this->get_line();
			return true;
		}
		function send_smtp_mail($to,$subject,$data,$cc="",$from='')
		{

            //----------------------------
            // Connect to the SMTP server
            //----------------------------
			$parts=explode(':',sendmailpath);
			$ret=$this->connect($parts[0],$parts[1]);
			if(!$ret)
				return $ret;

            //----------------------------
            // Start session
            //----------------------------
            $this->put_line("HELO ". domain);
            $response=$this->get_line();
            if(intval(strtok($response," "))!=250)
            {
                $this->error = intval(strtok($response," "));
                return false;
            }

            //----------------------------
            // Sending from...
            //----------------------------
			$this->put_line("MAIL FROM: $from");
			$response=$this->get_line();
			if(intval(strtok($response," "))!=250)
			{
                $this->error = intval(strtok($response," "));
				return false;
			}

            //----------------------------
            // Sending to...
            //----------------------------
			$to_err = split('[,;]',$to);
			foreach($to_err as $mailto)
			{
                if(preg_match('/@/', $mailto)) {
                    $this->put_line("RCPT TO: $mailto");
                    $response=$this->get_line();
                    if(intval(strtok($response," "))!=250)
                    {
                        $this->error=strtok($response,"\r\n");
                        return false;
                    }
                } else {
                    $this->error= "Massmail sent as BCC, but without To: address. Add at Site Settings -> Site eMail Settings<br>\n";
                    return false;
                }
			}

            /* Does not seem to be used ever
			if(!empty($cc))
			{
				$to_err = split("[,;]",$cc);
				foreach($to_err as $mailto)
				{
                    if(preg_match('/@/', $mailto)) {
                        $this->put_line("RCPT TO: $mailto");
                        $response=$this->get_line();
                        if(intval(strtok($response," "))!=250)
                        {
                            $this->error=strtok($response,"\r\n");
                            return false;
                        }
                    }
				}
			}
            */
            //----------------------------
            // BCCs...
            //----------------------------
            $to_err = split("\n",$data);
            foreach($to_err as $mailto)
            {
                if(strtolower(substr($mailto, 0, 3)) == 'bcc') {
                    $mailto = substr( $mailto, 5 );
                    $bccs = split("[,;]",$mailto);
                    foreach($bccs as $bcc)
                    {
                        if(preg_match('/@/', $bcc)) {
                            $this->put_line("RCPT TO: $bcc");
                            $response=$this->get_line();
                            if(intval(strtok($response," "))!=250)
                            {
                                $this->error=strtok($response,"\r\n");
                                return false;
                            }
                        }
                    }
                    $data = preg_replace ("/bcc:\s.*?\n/si",'',$data);
                }
                //------------------
                // End of headers?
                //------------------
                if(trim($mailto) == '') {
                    break;
                }
            }

            //----------------------------
            // Email data / content
            //----------------------------
			$this->put_line("DATA");
			$response=$this->get_line();
			if(intval(strtok($response," "))!=354)
			{
				$this->error=strtok($response,"\r\n");
				return false;
			}
			$this->put_line("TO: $to");
			$this->put_line("SUBJECT: $subject");

            //----------------------------
            // The email message
            //----------------------------
            $lines = explode("\n", $data);
            foreach($lines as $line) {
                if($line == '.') {
                    $line = '..';
                }
                $this->put_line($line);
            }

            //----------------------------
            // DONE
            //----------------------------
			$this->put_line(".");
			$response=$this->get_line();
			if(intval(strtok($response," "))!=250)
			{
				$this->error=strtok($response,"\r\n");
				return false;
			}
			$this->close();
			return true;
		}
		// This function is used to get response line from server
		function get_line()
		{
			while(!feof($this->con))
			{  
                $line = '';
                while ( substr($line,3,1) != ' ' ) 
                { 
                    $line = fgets($this->con);
                    //echo date("H:i:s") . " SMTP << $line <br>";
                }
            
                return(substr($line,0,-2));

			}
		}
		////This functiuon is to retrive the full response message from server

		////This functiuon is to send the command to server
		function put_line($msg="")
		{
            //echo date("H:i:s") . " SMTP >> $msg\r\n <br>";
            fputs($this->con,"$msg\r\n");
			return;
		}
		
		function close()		
		{
			@fclose($this->con);
			$this->con=null;
			$this->state="DISCONNECTED";
		}
	}

function footer()
{
    require('footer.php');
    exit;
}

function postsysval()
{
    foreach($_POST['sysval'] as $key=>$value)
    {
        if(!$value)    
            $value=' ';

        $value=system_value($key,$value);
        if ($key=='accounting_db' or $key=='accounting_tbl')
        {
            if (@mysql_num_rows(@mysql_query("describe ".$_POST['sysval']['accounting_db'].".".$_POST['sysval']['accounting_tbl']))>0 and preg_match("/accounting/",$_POST['sysval']['accounting_tbl']))
            {
                @mysql_query("replace into ".mysql_prefix."system_values set name='$key',value='".trim($value)."'");
            }
        } else 
        {
            @mysql_query("replace into ".mysql_prefix."system_values set name='$key',value='".trim($value)."'");
        }
    }
}

function admin_login()
{
    global $adminipsecchecked,$title,$noheader, $mainindex;
    $adminipsecchecked='checked';
                
    if ($_POST['admin_password']) $_SESSION['admin_password']=$_POST['admin_password'];
        list ($adminpass)=@mysql_fetch_row(
            @mysql_query('select value from ' . mysql_prefix . 'system_values where name="admin password"'));
            $admin_crypt_password=password($_SESSION['admin_password']);

    $possessionpass=classc;
    if (!$_SESSION['startusername'] && $_COOKIE['computerid'])
    $_SESSION['startusername']=@mysql_result(@mysql_query('select username from '.mysql_prefix.'last_login where computerid="'.$_COOKIE['computerid'].'"'),0,0);
    if (!$_SESSION['startusername'])
    $_SESSION['startusername']='Unknown';
    if (!$_COOKIE['computerid'])
    $_COOKIE['computerid']='Unknown';
    if ($_POST['adminipsec'] && !$_SESSION['adminsessionpass'])
    $_SESSION['adminsessionpass']=$possessionpass;
    if ($_SESSION['adminsessionpass']!=$possessionpass && $_SESSION['adminsessionpass']){
    $_SESSION['admin_password']='Session Security IP check failed';
    $adminipsecchecked='';}
    $usingipsec='No';
    if ($_SESSION['adminsessionpass'])
    $usingipsec='Yes';
    if (($adminpass != $admin_crypt_password && $adminpass) || $_SESSION['admin_password']=='Session Security IP check failed' )
    {
        if ($_SESSION['admin_password'])
        {
            mysql_query ("insert into " . mysql_prefix . "access_log set value='DATE TIME: ".gmdate(timeformat,unixtime)."\nFAILED LOGIN: ".addslashes(safeentities($_SESSION['admin_password']))."\nREQUEST: $_SERVER[PHP_SELF]\nCOMPUTER ID: $_COOKIE[computerid]\nUSERNAME: $_SESSION[startusername]\nSESSION SECURITY: $usingipsec\nIP/PROXY: ".ipaddr."\n\n'");
            sendmail(support_email,'Unauthorized Admin Access Alert',"DATE TIME: ".gmdate(timeformat,unixtime)."\nFAILED PASSWD: ".addslashes(safeentities($_SESSION['admin_password']))."\nREQUEST: $_SERVER[PHP_SELF]\nCOMPUTER ID: $_COOKIE[computerid]\nUSERNAME: $_SESSION[startusername]\nSESSION SECURITY: $usingipsec\nIP/PROXY: ".ipaddr,support_email);
        }
        session_destroy();   
        include ("login.php");
    }

    if (!$noheader)
        include("header.php");
            

    if (!$mainindex && $_SESSION['lastaction']!=$_SERVER['PHP_SELF'])
    {
        $_SESSION['lastaction']=$_SERVER['PHP_SELF'];
        mysql_query ("insert into " . mysql_prefix . "access_log set value='DATE TIME: ".gmdate(timeformat,unixtime)."\nREQUEST: $_SERVER[PHP_SELF]\nCOMPUTER ID: $_COOKIE[computerid]\nUSERNAME: $_SESSION[startusername]\nSESSION SECURITY: $usingipsec\nIP/PROXY: ".ipaddr."\n\n'");
    }
}

function cronjob_query($query)
{
    global $value;
    $unixtime=time()+((int)timezone *3600);
    @mysql_query('replace into '.mysql_prefix.'cronjobs set name="'.$value->class_name.'",value='.$unixtime);
    return(@mysql_query($query));
} 

function cronjob($cronjob=''){

     global $cronjob_classes;
        if(class_exists($cronjob) && method_exists($cronjob_classes[$cronjob],'cronjob'))
        return(call_user_func(array($cronjob_classes[$cronjob], 'cronjob')));
     }


function load_cronjobs()
    {
       global $cronjob_classes;
       $cronjobs = array();
       $dir_cronjobs = dir('./cronjobs');
       while(($entry = $dir_cronjobs->read()) !== false)
       {
       if(substr($entry,-4)!='.php')
          continue;

          include_once('./cronjobs/'.$entry);
          $cc=count($cronjobs)-1;
          $cronjob_classes[$cronjobs[$cc][classname]] =  new $cronjobs[$cc][classname]();
       }

     }

function edit_sysval($desc,$name='',$tail='')
{
	if (!$name){
	echo '<tr><td colspan=2><b><hr>'.$desc.'</b></td></tr>';
	}
	else {
	if (!$tail)
	echo '<tr><td align="right">'.$desc.':</td><td><input type="text" name="sysval['.$name.']" size="30" value="'.system_value($name).'"></td></tr>';
        else
	echo '<tr><td colspan="2">'.$desc.'<input type="text" name="sysval['.$name.']" value="'.system_value($name).'">'.$tail.'</td></tr>';
	}
}	

function is_ascii($str){ 
    if (function_exists('mb_check_encoding')) {
        return (int)mb_check_encoding ($str, "ASCII");
    } else {
        //----------------------------------------
        // If we can't check, assume non-ASCII,
        // so that we don't break anything - CC222
        //----------------------------------------
        return 0;
    }
}

function sendmassmail($to='', $subject='', $message='', $headers='',$charset=emailcharset)
{
	if ($charset!=charset && function_exists('mb_internal_encoding')){
            mb_internal_encoding(charset);
            $subject = mb_convert_encoding($subject,$charset);
            $message = mb_convert_encoding($message,$charset);
        }

    //---------------------------------
    // Subject needing BASE64? -- CC223
    //---------------------------------
    if(!is_ascii($subject))
    {
        $subject = base64_encode($subject);
        $subject = '=?'.$charset.'?B?'.$subject.'?=';
    }
	
    //---------------------------------
    // Determine massmailing method
    //---------------------------------
    if ((!strpos('.'.sendmailpath,'/') && !strpos('.'.sendmailpath,':')) || ini_get('safe_mode')){
        if (ini_get('safe_mode') || force_return!='YES')
            mail($to,$subject,$message,$headers);    
        else
            mail($to,$subject,$message,$headers,'-f'.massmail_email);

    }
    else {
        if (strpos('.'.sendmailpath,':')){
            echo date("H:i:s") . " using SMTP...<br>\n";
            $smtp=new SMTPMAIL;
            if(!$smtp->send_smtp_mail($to,$subject,$headers."\n".str_replace("\r\n","\n",$message),"",massmail_email)) {
                echo date("H:i:s") . " SMTP server returned error: ". $smtp->error() ."<br>\n";
            }
        }
        else {
            echo date("H:i:s") . " using sendmail...<br>\n";
            $fd = @popen(sendmailpath, 'w');
            fputs($fd, "To: $to\n");
            fputs($fd, "Subject: $subject\n");
            fputs($fd, $headers);
            fputs($fd, "\n");
            fputs($fd, str_replace("\r\n","\n",$message));
            pclose($fd);    
        }
    }
}

?>
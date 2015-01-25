<?php
include("../conf.inc.php");
include("functions.inc.php");
$title='Helpdesk';
admin_login();

//------------------------
// Fetch categories if department is not defined
//------------------------
if($_GET['department'] == "")
{
    $fp = @fsockopen("cashcrusadersoftware.com", 80,$errno, $errstr, 20);
    if($fp)  
    {
      fputs($fp,"GET /support/embed.php HTTP/1.0\r\nHost: cashcrusadersoftware.com\r\n\r\n");
      socket_set_timeout($fp, 20);
      $res = fread($fp, 10000);
      fclose($fp);
      $res = explode("<!-- Embedded Helpdesk -->",$res);
    }
}
//------------------------
// Fetch submit form of wanted department
//------------------------
else
{
    $fp = @fsockopen("cashcrusadersoftware.com", 80,$errno, $errstr, 20);
    if($fp)
    {
      fputs($fp,"GET /support/embed.php?department=".(int)$_GET['department']." HTTP/1.0\r\nHost:cashcrusadersoftware.com\r\n\r\n");
      socket_set_timeout($fp, 20);
      $res = fread($fp, 10000);
      fclose($fp);
      $res = explode("<!-- Embedded Helpdesk -->",$res);
    }
}
if (!$res[1]){$res[1]='<b>Embedded helpdesk failure. Please use helpdesk/support available at <a target="_blank" href="http://cashcrusadersoftware.com">cashcrusadersoftware.com</a></b>';}
echo '<center><b>CashCrusader embedded helpdesk</b><br>';
echo 'Helpdesk is also available at <a target="_blank" href="http://cashcrusadersoftware.com">cashcrusadersoftware.com</a></center>';
echo '<div style="padding:10px;">'.$res[1].'</div>';

footer();

?>

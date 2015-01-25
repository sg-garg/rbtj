<?php
//LDF SCRIPTS
ini_set('display_errors', 'on');
error_reporting(E_ALL ^ E_NOTICE);
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header ('Last-Modified: ' . gmdate('D, d M Y H:i:s'). ' GMT');
header ('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header ('Pragma: no-cache');

if (!$mysql_database)
{
    if (!@include_once 'conf.inc.php') include_once '../conf.inc.php';
} 
 
if (get_magic_quotes_gpc()) 
{
    no_magic_quotes_gpc($_GET);
    no_magic_quotes_gpc($_POST);
    no_magic_quotes_gpc($_COOKIE);
}

//----------------------
// Prevent login bypass
//----------------------
$loginstarted = 0;

@mysql_connect($mysql_hostname, $mysql_user, $mysql_password);

if (!$no_db_error)
{
    if (!@mysql_select_db($mysql_database))
    {
        if (!@include 'server_error.php')
            if (!@include '../pages/server_error.php')
                echo 'MySQL Server is offline. Please try later.';  
                
        exit;
    }
}

if (!$_GET['mysql_prefix'] && !$_POST['mysql_prefix'] && $mysql_prefix) 
    define('mysql_prefix',$mysql_prefix);
else
    define('mysql_prefix','');

$constant = @mysql_query('select * from '. mysql_prefix .'system_values');
while ($row = @mysql_fetch_row($constant))
{ 
    if ($row[0]=='domain')    
        $row[1]=strtolower($row[1]);
    define($row[0],$row[1]);
}

define('admin_cash_factor',100);
date_default_timezone_set('UTC');
define('mysqldate',gmdate('YmdHis',time()+((int)timezone *3600)));
define('unixtime',time()+((int)timezone *3600));
mysql_query('SET time_zone = "'. str_replace('.', ':', timezone) .'"');

//----------------------
// Check offline state
//----------------------
if(!preg_match('#/admin/#i', $_SERVER['PHP_SELF']) && site_offline == '1')
{
    list($site_offline_message) = mysql_fetch_array(@mysql_query("SELECT value FROM ".mysql_prefix."system_values WHERE name = 'site_offline_message'"));
    list($site_offline_bypass) = mysql_fetch_array(@mysql_query("SELECT value FROM ".mysql_prefix."system_values WHERE name = 'site_offline_bypass'"));

    if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    if (strpos($site_offline_bypass, $ip) === false) 
    {
        echo $site_offline_message;
        exit;
    }
}

if ($_SERVER['REMOTE_ADDR']=='127.0.0.1')
    $_SERVER['REMOTE_ADDR']=$_SERVER['HTTP_X_FORWARDED_FOR'];                  
if ($_SERVER['HTTP_VIA'])
{
    $ipparts=explode('.',$_SERVER['HTTP_X_FORWARDED_FOR']);
    define('classc',$ipparts[0].'.'.$ipparts[1].'.'.$ipparts[2]);
    define('ipaddr',$_SERVER['REMOTE_ADDR'].'/'.$_SERVER['HTTP_X_FORWARDED_FOR']. ' - ' . $_SERVER['HTTP_VIA']);
}
else 
{
    $ipparts=explode('.',$_SERVER['REMOTE_ADDR']);
    define('classc',$ipparts[0].'.'.$ipparts[1].'.'.$ipparts[2]); 
    define('ipaddr',$_SERVER['REMOTE_ADDR']);  
}

if (!defined('accounting_tbl'))
   define('accounting_tbl',mysql_prefix.'accounting');

if ($mysql_database != accounting_db && defined('accounting_db'))
    $commissions_accounting_table = accounting_db. '.' . accounting_tbl;
else
    $commissions_accounting_table=accounting_tbl;
 
if ($_POST['refid'])
    $_GET['refid']=$_POST['refid'];

if ($_GET['pages'])
    setcookie('pages', $_GET['pages'], unixtime + 2592000, '/');

if ($_GET['refid'])
{
    if ($_SERVER['HTTP_REFERER'])
    {
        $parsedurl=explode('?',$_SERVER['HTTP_REFERER']);
        if (!strpos(strtolower($parsedurl[0]),strtolower(domain)))
        {
            $_COOKIE['refurl']=$parsedurl[0];
            setcookie('refurl',$_COOKIE['refurl'], unixtime + 2592000, '/');
        }
    }
    $_GET['refid']=strtolower(substr(preg_replace('([^a-zA-Z0-9])', '', $_GET['refid']), 0, 16));
    setcookie('refid', $_GET[refid], unixtime + 2592000, '/');
}

if ($_POST['username'] || $_GET['username']) 
    if (!@include scripts_dir.'includes/set_login_cookies.inc.php') include 'includes/set_login_cookies.inc.php';
        
if ((!$_COOKIE['computerid'] || $_COOKIE['computerid'] == 'deleted' || strpos('.'.$_COOKIE['computerid'],'d41d8cd98f00b204e9')) && $_POST['username'] != 'LOGOUT' && $_GET['username'] != 'LOGOUT' && $_COOKIE['autousername'])
{
    $_COOKIE['computerid']=uniqueid($_COOKIE['autousername']);
    setcookie('computerid', $_COOKIE['computerid'], unixtime + 2592000, '/');
}

make_session();
     
if ($_GET['pages'])
    $_SESSION['pages']=$_GET['pages'];
else
    $_GET['pages']=$_SESSION['pages'];

if (!trim($_GET['pages']))
    $_GET['pages']=$_COOKIE['pages'];
         
if (!strpos($_GET['pages'],'/'))
    define('pages','');
else
    define('pages',$_GET['pages']);

if ($_SESSION['username'])
    $_SESSION['username']=strtolower(substr(preg_replace('([^a-zA-Z0-9])', '', $_SESSION['username']), 0, 16));

if (!$_SESSION['browserok'])
{
    list ($badbrowser)=@mysql_fetch_row(@mysql_query('select agent from ' . mysql_prefix . 'browsers where "'. addslashes($_SERVER['HTTP_USER_AGENT']).'" like agent limit 1'));
    if ($badbrowser && !defined('ranfromcron'))
    {
        include (pages_dir .pages. 'bad_browser.php');
        exit;
    }
    $_SESSION['browserok']=1;
}

if (!$_SESSION['ipok'])
{
    list ($badip)=@mysql_fetch_row(@mysql_query('select ip from ' . mysql_prefix . 'ips where "' . addslashes(ipaddr) . '" like ip limit 1'));
    if ($badip)
    {
        include (pages_dir . pages.'bad_ip.php');
        exit;
    }

    $_SESSION[ipok]=1;
}

if (!$logout && (($_SESSION['lastaction'] && $_SESSION['username']) || !$_COOKIE['autousername']))
{
    $_COOKIE['autousername']=$_SESSION['username'];
}

if (($_SESSION[username] && $_SESSION[username] != $_COOKIE[autousername]) or $logout)
{
    if (!$_SESSION['lastaction'])
    {
        session_destroy();
        make_session(1);
    } 
    else
    {
        unset($_SESSION['username']);
        unset($_SESSION['sessionpass']); //CC204
    }
}

if($_COOKIE[autoipsec] || $_POST[ipsec] || $_SESSION['adminsessionpass'])
   $_SESSION[ipsec]=1; 
        
if($_SESSION['refurl']!=$_COOKIE['refurl'] && $_COOKIE['refurl'])
  $_SESSION['refurl']=$_COOKIE['refurl'];

if($_GET[refid])
    $_SESSION[refid]=$_GET[refid];
else
    $_GET[refid]=$_SESSION[refid];

if (!trim($_GET[refid]))
    $_GET[refid]=$_COOKIE[refid];

if ($_GET[CO] || $_POST[userform])
 if (!@include scripts_dir.'includes/process_user_form.inc.php') include 'includes/process_user_form.inc.php';

//----------------------------------------------
// Email address verification -- CC205
//----------------------------------------------
if ($_GET['verification'])
 if (!@include scripts_dir.'includes/email_update.inc.php') include 'includes/email_update.inc.php';


$_POST['ptsemail']=trim($_POST['ptsemail']);

if($_POST['ptsconfirm'] and md5($_POST['ptsemail']).strlen($_POST['ptsemail'])!=$_POST['pasteinstcode'])
{
    login();

    if ($_SESSION['ptslist'])
    {
        $_SESSION['ptslist']['ID'.$_POST['ptsconfirm']]=1;
    }
    @mysql_query ("insert into " . mysql_prefix . "paid_signups_".$_POST['ptsconfirm']." set username='".$_SESSION[username]."'");

    if (mysql_affected_rows()==1)
    {
        @mysql_query ("insert into " . mysql_prefix . "signups_to_process set id=".$_POST['ptsconfirm'].",username='".$_SESSION[username]."',email='" . addslashes(safeentities($_POST[ptsemail])). "'");
        @mysql_query ("update " . mysql_prefix . "pts_ads set signups=signups+1 where ptsid=".$_POST['ptsconfirm']);
    }
}

$_POST['review']=trim($_POST['review']);
if ($_POST['review'] and md5($_POST['review']).strlen($_POST['review'])!=$_POST['pasteinstcode'])
{
    login();

    if ($_SESSION['ptrlist'])
    {
        $_SESSION['ptrlist']['ID'.$_POST['ptrconfirm']]=1;
    }
    @mysql_query ("insert into " . mysql_prefix . "paid_reviews_".$_POST['ptrconfirm']." set username='".$_SESSION['username']."', rating='". $_POST['rating'] ."',review='". safeentities($_POST['review']) ."'");
    if (mysql_affected_rows()==1)
    {
        @mysql_query ("insert into " . mysql_prefix . "reviews_to_process set id=".$_POST['ptrconfirm'].",username='".$_SESSION[username]."',email='" . addslashes(safeentities($_POST[review])). "', rate='" .$_POST[rating] ."'");
        @mysql_query ("update " . mysql_prefix . "review_ads set rating=(rating+". $_POST['rating'] .")/2, reviews=reviews+1 where id=".$_POST['ptrconfirm']);
    }
}

if ($_POST[inbox_msg] and !$_POST[checkall])
{
    login();
    $results=@mysql_query('select mails from ' . mysql_prefix . 'user_inbox where username="'.$_SESSION[username].'"');
    if ($results)
    {
        $mails=mysql_result($results,0,0);
        mysql_free_result($results);
        foreach($_POST[inbox_msg] as $ibkey => $value) 
        {
            $mails=str_replace('|'.$ibkey,'',$mails);
            if ($_SESSION[ibcount]) $_SESSION[ibcount]--;
        }
        @mysql_query ('update ' . mysql_prefix . 'user_inbox set mails="'.$mails.'" where username="'.$_SESSION[username].'"');
    }
}

if ($_POST[asurl] and $_POST[ashow] and $_POST[asorder])
{
    login();
    $_POST['autosurfid']=intval($_POST['autosurfid']);
    if ($_POST['autosurfid']>4 || $_POST['autosurfid']<2)
    $_POST['autosurfid']='';

    list ($amount)=@mysql_fetch_row(@mysql_query('select sum(amount) from ' . mysql_prefix . 'accounting where type="' . $_POST[ashow] . '" and username="' . $_SESSION[username] . '"'));
    $type='autosurfdb'.$_POST['autosurfid'];
    $factor=1;

    if ($_POST[ashow] == 'cash')
    {
        $factor=admin_cash_factor;
        $type='autosurfcdb'.$_POST['autosurfid'];
    }

    $type=@constant($type);

    if ($amount and $type)
        $amount=$amount / $type / 100000 / $factor;
    else
        $amount=0;

    if ($amount <= $_POST[asorder])
        $_POST[asorder]=$amount;

    if ($_POST[asorder] > 0)
    {
        @mysql_query ('insert into ' . mysql_prefix . 'accounting set unixtime=' . unixtime . ',description="' . asddescription 
                . '",transid="' . maketransid(
                $_SESSION[username]). '",amount=-' . $_POST[asorder] * $type
                * 100000 * $factor . ',type="' . $_POST[ashow] . '",username="' . $_SESSION[username] . '"');
        @mysql_query ('update ' . mysql_prefix . 'autosurf'.$_POST['autosurfid'].' set quantity=quantity+' . $_POST[asorder] . ' where username="' . $_SESSION[username] . '" and url="' . addslashes(rawurldecode($_POST[asurl])) . '"');
     }
}

if ($_GET[delautosurf])
{
    login();
    $_GET['autosurfid']=intval($_GET['autosurfid']);
    if ($_GET['autosurfid']>4 || $_GET['autosurfid']<2)
    $_GET['autosurfid']='';

    @mysql_query ('delete from ' . mysql_prefix . 'autosurf'.$_GET['autosurfid'].' where username="' . $_SESSION[username] . '" and url="' . addslashes($_GET[delautosurf]) . '"');
}

if ($_GET[dactautosurf])
{
    login();
     $_GET['autosurfid']=intval($_GET['autosurfid']);
    if ($_GET['autosurfid']>4 || $_GET['autosurfid']<2)
    $_GET['autosurfid']=''; 
    @mysql_query ('update ' . mysql_prefix . 'autosurf'.$_GET['autosurfid'].' set active=0 where username="' . $_SESSION[username] . '" and url="' . addslashes($_GET[dactautosurf]) . '"');
}

if ($_GET[actautosurf])
{
    login();
    $_GET['autosurfid']=intval($_GET['autosurfid']);
    if ($_GET['autosurfid']>4 || $_GET['autosurfid']<2)
    $_GET['autosurfid']=''; 
    @mysql_query ('update ' . mysql_prefix . 'autosurf'.$_GET['autosurfid'].' set active=1 where username="' . $_SESSION[username] . '" and url="' . addslashes($_GET[actautosurf]) . '"');
}

function get_args($defaults,$args)
{
    $d=explode('|',$defaults);
    $a=explode('|',$args);
    if (trim($args)){   
    for ($i=0;$i<count($a);$i++){
    if (isset($a[$i]))
    $d[$i]=$a[$i];
    }}

  return($d);
} 

function action($action='', $args = '')
{
    $output=include scripts_dir.'includes/'.$action.'.inc.php';
    return($output);
}

function uniqueid($strtoencrypt){
    $uid=@include scripts_dir.'includes/uniqueid.inc.php';
    if(!$uid) $uid=include 'includes/uniqueid.inc.php';
    return $uid;
}   

function open_session($path,$name){
return(true);}

function close_session(){
return(true);}

function read_session($id){
    global $sessionhash;
    $result=@mysql_query('select datavalue,lastupdated from '.mysql_prefix.'sessions where sessionid = "'.addslashes($id).'"');
    if(@mysql_num_rows($result)==1)
    {
        if (mysql_result($result,0,1)>unixtime-1800)
        $sessionhash=md5(mysql_result($result,0,0));
        return(mysql_result($result,0,0));
    }
    else {
        @mysql_query('insert into '.mysql_prefix.'sessions set sessionid="'.addslashes($id).'",lastupdated='.unixtime);
        return "";
    }
}
function write_session($id,$sess_data){
    global $sessionhash;
    if (md5($sess_data)!=$sessionhash)
    @mysql_query('update '.mysql_prefix.'sessions set lastupdated='.unixtime.',datavalue="'.addslashes($sess_data).'" where sessionid="'.addslashes($id).'"');
    return(true);
}
function destroy_session($id){
    @mysql_query('delete from '.mysql_prefix.'sessions where sessionid="'.addslashes($id).'"');
    session_unset();
    return(true);
}

function gc_session($life){
return(true);}


function make_session($new=0){
    
    if((!$_COOKIE[session_name()] && !$_GET[session_name()] && !$_POST[session_name()]) || $new)
    {
        $rand_id="";
        for($i=1; $i<=26; $i++)
        {
            $rand_id .=chr(mt_rand(97,122));
        }
        session_id(md5(ipaddr.$_SERVER['HTTP_USER_AGENT']).$rand_id.substr(mysqldate,-6));
    }
    session_set_cookie_params(0,'/');
    session_set_save_handler('open_session', 'close_session', 'read_session', 'write_session', 'destroy_session', 'gc_session');
    session_start();
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header ('Last-Modified: ' . gmdate('D, d M Y H:i:s'). ' GMT');                 
    header ('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header ('Pragma: no-cache');
  return;
}

function domain(){
echo domain;}

function site_name(){
echo site_name;}

function referral_url(){
    echo pages_url.pages.'index.php?refid='.$_SESSION['username'];
}

function pages_url($full='')
{
    global $rel_pages_url;
    if (!$rel_pages_url){
    $url=parse_url(pages_url.pages);
    $rel_pages_url=$url['path'];}
    if ($full='complete')
    echo pages_url.pages;
    else
    echo $rel_pages_url;
}

function password($p){
return substr(md5($p),0,16);
}

function show_levels($l='Level:'){
if (!@include scripts_dir.'includes/show_levels.inc.php') include 'includes/show_levels.inc.php';
} 

function totaldebits($d = 2, $f = 100, $point = '.', $comma = ',',$start=0){
 $cash=(totaldebits / 100000 / $f * -1) + $start; 
        echo number_format($cash, $d, $point, $comma);

}

function totaldebits_points($d = 0, $f = 1, $point = '.', $comma = ',',$start=0){
 $points=(totaldebits_points / 100000 / $f * -1) + $start; 
        echo number_format($points, $d, $point, $comma);

}


function no_magic_quotes_gpc(&$var)
{
    if (is_string($var)) {
        $var = stripslashes($var);} 
    elseif (is_array($var)) {
      foreach($var AS $key => $value) {
        no_magic_quotes_gpc($var[$key]);
      }
    } elseif (is_object($var)) {
      foreach(get_object_vars($var) AS $key => $value) {
        no_magic_quotes_gpc($var->$key);
      }
    }
}

function create_turing($font = "<font size=2>"){
if (!@include scripts_dir.'includes/create_turing.inc.php') include 'includes/create_turing.inc.php';
}

function showturing($string){
include scripts_dir.'includes/showturing.inc.php';
}
  
function write_as_earnings(){
include scripts_dir.'includes/write_as_earnings.inc.php';
}
function show_pop3_info($title='<br>Access your inbox using your favorite POP3 client',$server='<br>Server: ',$port='| Port: ',$user='<br>Username: '){
    
if (pop3server=='1')
  echo $title.$server.domain.$port.'1110'.$user.$_SESSION['username'].'@'.domain.'<br>';
}

function inbox_days() { echo inboxexpire; }

function keyword_html($key, $html)
{
    if (interests($key, 'return'))
    {
        echo $html;
    }
}

function existing_member_email_setting($s0='Site Inbox',$s1='eMail Address',$s2='Site Inbox & eMail')
{
    if (!@include scripts_dir.'includes/existing_member_email_setting.inc.php') include 'includes/existing_member_email_setting.inc.php';
}

function existing_member_paytypes()
{ 
    if (!@include scripts_dir.'includes/existing_member_paytypes.inc.php') include 'includes/existing_member_paytypes.inc.php';
}

function new_member_email_setting($s0='Site Inbox',$s1='eMail Address',$s2='Site Inbox & eMail')
{
    include  scripts_dir.'includes/new_member_email_setting.inc.php';
}   

function new_member_paytypes() 
{ 
    include  scripts_dir.'includes/new_member_paytypes.inc.php';
}    

function new_member_countries()
{
    include  scripts_dir.'includes/new_member_countries.inc.php';
}


function existing_member_countries()
{
    if (!@include scripts_dir.'includes/existing_member_countries.inc.php') include 'includes/existing_member_countries.inc.php';
}


function new_member_states()      
{
    include  scripts_dir.'includes/new_member_states.inc.php'; 
}    
             
function existing_member_states() 
{ 
    if (!@include scripts_dir.'includes/existing_member_states.inc.php') include 'includes/existing_member_states.inc.php';
}
 
function new_member_keywords($l = '<tr><td><font face=arial size=2>', $m =
                                     '</font></td><td><font face=arial size=2>',
                                 $r = '</font></td></tr>')
{
    include  scripts_dir.'includes/new_member_keywords.inc.php';
}

function existing_member_keywords($l = '<tr><td><font face=arial size=2>',
                                      $m =
                                          '</font></td><td><font face=arial size=2>',
                                      $r = '</font></td></tr>')
{
    if (!@include scripts_dir.'includes/existing_member_keywords.inc.php') include 'includes/existing_member_keywords.inc.php';
}

function leading_zero($length, $number)
{
    $length=$length - strlen($number);
    for ($i=0; $i < $length; $i++)
    {
        $number="0" . $number;
    }
  return ($number);
}

function maketransid($id = '')
{
    if (!$id)
    {
        $newid=mysqldate . leading_zero(4, rand(0, 9999));
    }
    else
    {
        $rand=substr(md5($id), 0, 8). leading_zero(4,rand(0, 9999));
        $newid=mysqldate . $rand;
    }
 return ($newid);
}

// Start John's revision 
function autosurf_links($stop = '<font face=arial>||</font>',
                            $start = '<font face=arial>&gt;</font>',
                            $next = '<font face=arial>&gt;&gt;</font>',
                            $nw = '<font face=arial>New Window</font>',
                            $abuse = '<font face=arial size=1>Report Abuse',
                            $number = '<font face=arial font size=1>Click on the<br>matching number</font>',
                $logoff = '<font face=arial size=1>Save Earnings & Exit</font>',$countedcolor='GREEN',$notcountedcolor='RED')
{
   include scripts_dir.'includes/autosurf_links.inc.php';
}
// End John's revision
function delete_user($username, $upline)
{
    if (!@include scripts_dir.'includes/delete_user.inc.php') include 'includes/delete_user.inc.php';
}

function show_http_referer($keys = '',                   $f = '<tr><td>',
                            $m = '</td><td align=right>', $e = '</td></tr>')
{
    if (!@include scripts_dir.'includes/show_http_referer.inc.php') include 'includes/show_http_referer.inc.php';
}

function keyword_totals($keys = '',                   $f = '<tr><td>',
                        $m = '</td><td align=right>', $e = '</td></tr>')
{
    if (!@include scripts_dir.'includes/keyword_totals.inc.php') include 'includes/keyword_totals.inc.php';
}

function gcd($num1, $num2)
{
    if($num1 < $num2)
    {
        $t=$num1;
        $num1=$num2;
        $num2=$t;
    }
    while ($t=($num1 % $num2) != 0)
    {
        $num1=$num2;
        $num2=$t;
    }
    return $num2;
}

function show_autosurf_cash_exposure_count($ID=1,$R=0)
{
    $ID=intval($ID);
    if ($ID>4 || $ID<2)
    $ID='';

    list ($amount)=@mysql_fetch_row(@mysql_query('select sum(amount) from ' . mysql_prefix . 'accounting where type="cash" and username="' . $_SESSION[username] . '"'));

    if($amount and @constant('autosurfcdb'.$ID)!=0)
    {
        $total=$amount / @constant('autosurfcdb'.$ID) / 100000 / admin_cash_factor;
    }
    if ($total < 1)
    {
        $total=0;
    }
    if (!$R){
        echo number_format($total, 0, '', '');}
    else { return number_format($total, 0, '', '');}
}

function show_autosurf_point_exposure_count($ID=1,$R=0)
{
    $ID=intval($ID);
    if ($ID>4 || $ID<2)
    $ID='';

    list ($amount)=@mysql_fetch_row(@mysql_query('select sum(amount) from ' . mysql_prefix . 'accounting where type="points" and username="' . $_SESSION[username] . '"'));

    if ($amount and @constant('autosurfdb'.$ID)!=0)
    {
        $total=$amount / @constant('autosurfdb'.$ID) / 100000;
    }

    if ($total < 1)
    {
        $total=0;
    }
    if (!$R){
        echo number_format($total, 0, '', '');}
    else { return number_format($total, 0, '', '');}
}

function show_autosurf_ratio($ID=1,$D = ':')
{
    $ID=intval($ID);
    if ($ID>4 || $ID<2)
    $ID='';

    list($junkcr, $endcr)=explode('.', @constant('autosurfcr'.$ID));
    list($junkdb, $enddb)=explode('.', @constant('autosurfdb'.$ID));
    if ((!$endcr and !$junkcr) or (!$enddb and !$junkdb))
    {
        $factoroncash=1;
        list($junkcr, $endcr)=explode('.', @constant('autosurfcash'.$ID));
        list($junkdb, $enddb)=explode('.', @constant('autosurfcdb'.$ID)); 
    }
        
    if ((!$endcr and !$junkcr) or (!$enddb and !$junkdb))
    {
        return;
    }
        
    $factorit=strlen($endcr);

    if (strlen($enddb) > strlen($endcr))
    {
        $factorit=strlen($enddb);
    }

    $upfactor='1';

    if ($factorit)
    {
        for ($i=0; $i < $factorit; $i++)
        {
            $upfactor=$upfactor . '0';
        }
    }

    if (!$factoroncash)
        $gcd=gcd(@constant('autosurfcr'.$ID) * $upfactor,
                 @constant('autosurfdb'.$ID) * $upfactor);
    else
        $gcd=gcd(@constant('autosurfcash'.$ID) * $upfactor,
                 @constant('autosurfcdb'.$ID) * $upfactor);

    if ($gcd)
    {
        if (!$factoroncash){
            echo @constant('autosurfdb'.$ID) * $upfactor / $gcd . $D . @constant('autosurfcr'.$ID) * $upfactor / $gcd;
        }
        else  {
            echo @constant('autosurfcdb'.$ID) * $upfactor / $gcd . $D . @constant('autosurfcash'.$ID) * $upfactor / $gcd;
        }
    } 
    else { 
           echo '0'.$D.'0';
    }
}

function show_autosurf_credit($ID=1) { 
    $ID=intval($ID);
    if ($ID>4 || $ID<2)
    $ID='';

    if ((!defined('autosurfcr'.$ID) || !@constant('autosurfcr'.$ID)) && (!defined('autosurfdb'.$ID) || !@constant('autosurfdb'.$ID)))  
        echo @constant('autosurfcash'.$ID); 
    else 
        echo @constant('autosurfcr'.$ID);
}

function show_autosurf_debit($ID=1) 
{ 
    $ID=intval($ID);
    if ($ID>4 || $ID<2)
    $ID='';
    if ((!defined('autosurfcr'.$ID) || !@constant('autosurfcr'.$ID)) && (!defined('autosurfdb'.$ID) || !@constant('autosurfdb'.$ID)))
        echo @constant('autosurfcdb'.$ID); 
    else
        echo @constant('autosurfdb'.$ID);
}

function system_value($key,$value='')
{
    if (defined('version'))
        $retval=@include scripts_dir.'includes/system_value.inc.php';
    else 
        define('version',0);                                                           
    if(!$retval) $retval=include 'includes/system_value.inc.php';
    if ($retval=='no_value_set')
        $retval='';
   return $retval; 
}

function show_current_ad_url()
{
    global $expireurl;
    echo $expireurl;
}

function show_start_page_url()
{
    echo runner_url . '?SP=' . substr(md5($_SESSION[username] . key), 0, 8). $_SESSION[username];
}
// Start John's revision
function users_autosurf_url($ID=1)
{
    $ID=intval($ID);
    if ($ID>4 || $ID<2)
    $ID=1;
    echo runner_url . '?AS=' . substr(md5($_SESSION[username] . key), 0, 8). $_SESSION[username]. '&ID='.$ID.'&SF=1';
}
// End John's revision
function referrer(){echo $_GET[refid];}

function get_ptr_ad($get, $message = 'Review the site above to earn',
                        $notfound =
                            'Sorry, no sites are available for you to review for at this time',
                        $points = 'point(s)', $cash = 'cent(s)', $factor = 1,
                        $forward = '<b>Next Page</b>',
                        $back = "<b>Previous Page</b>", $pasteinst = 
                            'Type your review for the above site here',$rate='How do you rate this site. 1 is worst, 5 is best',
                        $submitbutton = 'Submit your review')
{
    include scripts_dir.'includes/get_ptr_ad.inc.php';
}

function get_pts_ad($get, $message = 'Sign-up for the site above to earn',
                        $notfound =
                            'Sorry, no sites are available for you to sign-up for at this time',
                        $points = 'point(s)', $cash = 'cent(s)', $factor = 1,
                        $forward = '<b>Next Page</b>',
                        $back = "<b>Previous Page</b>", $pasteinst =
                            'Paste your confirmation email message for the above site here',
                        $submitbutton = 'Submit confirmation email',$closing='<hr><br>')
{
    include scripts_dir.'includes/get_pts_ad.inc.php';
}

function get_ptc_ad($get, $message = 'The ad above is worth',
                        $notfound = 'Sorry, no ads are available for you to click on this page at this time',
                        $points = 'point(s)', $cash = 'cent(s)', $factor = 1,
                        $forward = '<b>Next Page</b>',
                        $back = "<b>Previous Page</b>")
{
    include scripts_dir.'includes/get_ptc_ad.inc.php';
}

function sendmail($to,$subject,$message,$from,$name='')
{
    if (!$name)
    $name=site_name;
    
    if (charset!=emailcharset && function_exists('mb_internal_encoding'))
    {
      mb_internal_encoding(charset);
      $name    = mb_convert_encoding($name,emailcharset);
      $subject = mb_convert_encoding($subject,emailcharset);
      $message = mb_convert_encoding($message,emailcharset);
    }

      $subject = base64_encode($subject);
      $subject = '=?'.emailcharset.'?B?'.$subject.'?=';
        $name  = base64_encode($name);
        $name  = '=?'.emailcharset.'?B?'.$name.'?=';
    $bit=7;
            if (strtolower(emailcharset)=='utf-8')
        $bit=8;
    $header.='Return-Path:'.$from."\n".'From: "'.$name.'" <'.$from.'>'."\n".'Reply-To: '.$from."\n".'X-Mailer: Cash Crusader'."\n".'X-Priority: 3'."\nMIME-Version: 1.0\nContent-Type: text/plain; charset=".emailcharset."\nContent-Transfer-Encoding: ".$bit."bit\n";

    if (ini_get('safe_mode') || force_return!='YES')
     return mail($to,$subject,$message,$header);
    else
     return mail($to,$subject,$message,$header,'-f'.$from);
}

function login($L='',$AT='')
{
    global $logout, 
            $loginstarted;

    if ($loginstarted)
         {return;}

   $loginstarted=1;

    if ($logout or !$_COOKIE['autousername'] or ($_COOKIE['autousername'] and !$_COOKIE['autopassword'] and !$_SESSION['sessionpass']))
    {
        if ($_POST['admin_form'])
            echo 'Admin access error';
        else
            include (pages_dir .pages. 'login.php');

      exit;
    }

    $possessionpass=$_COOKIE['autousername'];

    if ($_SESSION['ipsec'])
        $possessionpass=$_COOKIE['autousername'].classc;
        
    if (!$_SESSION['username'] or $possessionpass != $_SESSION['sessionpass'])
    {
        $userinfo=@mysql_fetch_array(@mysql_query('select *,signup_date+0 as ts_signup_date from ' . mysql_prefix . 'users where username="' . $_COOKIE['autousername'] . '" limit 1'));
        $_SESSION['username']=$userinfo['username'];
    }
    //------------------------------------------------------------------
    // Fetch user info in any case to get updated account status - CC205
    //------------------------------------------------------------------
    else
    {
        if(empty($_SESSION['username']))
        {
            $userinfo = @mysql_fetch_array(@mysql_query('select *,signup_date+0 as ts_signup_date from ' . mysql_prefix . 'users where username="' . $_COOKIE['autousername'] . '" limit 1'));
            $_SESSION['username'] = $userinfo['username'];
        }
        else
        {
            $userinfo = @mysql_fetch_array(@mysql_query('select *,signup_date+0 as ts_signup_date from ' . mysql_prefix . 'users where username="' . $_SESSION['username'] . '" limit 1'));
        }
    }

    //---------------------------------------------
    // Show account canceled page if exists - CC218  
    //---------------------------------------------
    if ($userinfo['account_type'] == 'canceled' AND empty($_SESSION['admin_password']))
    {
        if(is_readable(pages_dir .pages. 'account_canceled.php'))
        {
            include pages_dir .pages. 'account_canceled.php';
        }else{
         echo "sandeep";    
        include pages_dir .pages. 'invalid_login.php';
        }
        session_destroy();
        exit;
    }


    $accountok=1;

    $account_type=user('account_type','return');
    if (!$account_type)
        $account_type='custom'; 

    $result = strpos($AT, $account_type);
    if ($L=='allow')
    {
        if ($result === false)
         $accountok=0;
    }
   elseif ($L=='deny') 
    {
        if ($result !== false)
         $accountok=0;
    }
    if (!$accountok)
    {
         include (pages_dir .pages. 'not_allowed_access.php');
         exit;
    }

    list ($adminpass)=@mysql_fetch_row(@mysql_query('select value from ' . mysql_prefix . 'system_values where name="admin password"'));

    //------------------------------------------
    // Login with password hash disabled - CC204
    //------------------------------------------
    if ($_SESSION['username'] != $_COOKIE['autousername'] || 
            (md5($userinfo['password'].key) != $_COOKIE['autopassword'] && $adminpass != password($_POST['password']) && $possessionpass != $_SESSION['sessionpass']))
    {
        if ($_POST['admin_form'])
        {
             echo 'Admin access error';
        }
        else
        {
            include (pages_dir .pages. 'invalid_login.php');
            session_destroy();
        }

        exit;
    }

    if ($_SESSION['admin_password'])
    {
        $admin_crypt_password=password($_SESSION['admin_password']);
        if ($adminpass != $admin_crypt_password && $adminpass)
        {
            echo 'Admin access error';
            exit;
        }
        $_SESSION['sessionpass']=$possessionpass;
    } else 
    {
        if ($userinfo['account_type']=='suspended')
        {
            include pages_dir.pages.'suspended.php';
            session_destroy();
            exit;
        }

        $_SESSION['sessionpass']=$possessionpass;
        if ($_SESSION['ascount']>0)
        {
            write_as_earnings($_SESSION['username']);
        }
        if ($L=='turing' && checkturing=='YES' && !user('disable_turing','return'))
        {
            if (!$_SESSION['turing_time']) 
            list($_SESSION['turing_time'])=@mysql_fetch_row(@mysql_query('select turing_time from '.mysql_prefix.'last_login where username="'.$_SESSION['username'].'"'));
            if ($_SESSION['turing_time']+900<unixtime)
            {
                $turingchkran=1;
                if ($_GET['PI'] && $_GET['PI'] != $_SESSION['img1'][$_GET['KE']] . $_SESSION['img2'][$_GET['KE']] && $_SESSION['img1'][$_GET['KE']])
                    {@mysql_query ('update  ' . mysql_prefix . 'last_login set bad_turing=bad_turing+1 where username="'. $_SESSION['username'] .'"');}
                if (!$_GET['PI'] || $_GET['PI']!=$_SESSION['img1'][$_GET['KE']].$_SESSION['img2'][$_GET['KE']])
                {
                    include (pages_dir . pages.'check_turing.php');
                    exit;
                }
            }
        }
        if ($turingchkran)
        {
            $_SESSION['img1'][$_GET['KE']]='';
            $_SESSION['img2'][$_GET['KE']]=''; 
            $_SESSION['pick'][$_GET['KE']]='';
            $_SESSION['turing_time']=unixtime;
            @mysql_query ('update  ' . mysql_prefix . 'last_login set turing_time='.$_SESSION['turing_time'].' where username="'.$_SESSION['username'].'"');
        }

        //------------------------------------------
        // Login with password hash disabled - CC204
        //------------------------------------------
        /*
        if ($userinfo['password']==$_POST['password'] && $_POST['password'])
        @mysql_query('update '.mysql_prefix .'users set password="'.password($userinfo['password']).'" where username="'.$userinfo['username'].'"');
        */

        if ($L!='norecord' && $_SESSION['lastlogin']!=substr(mysqldate,0,10))
        {
            $_SESSION['lastlogin']=substr(mysqldate,0,10);
            @mysql_query ('update  ' . mysql_prefix . 'last_login set time="' . substr(mysqldate,0,10) . '",computerid="' . $_COOKIE[computerid] . '",browser="' . addslashes($_SERVER['HTTP_USER_AGENT']) . '",ip_host="' . addslashes(ipaddr) . '-" where  username="'.$_SESSION[username].'"');
             if (!mysql_affected_rows())
                {@mysql_query ('insert into ' . mysql_prefix . 'last_login set time="' . substr(mysqldate,0,10) . '",username="' . $_SESSION[username] . '",computerid="' . $_COOKIE[computerid] . '",browser="' . addslashes($_SERVER['HTTP_USER_AGENT']) . '",ip_host="' . addslashes(ipaddr) . '"');}
        }
    }       
}

function usercount($type='') 
{ 
    if ($type=='active')
        echo number_format(activeusercount,0);
    else
        echo number_format(usercount, 0); 
}

function ptc_ads()
{
    if (defined('ptcads'))
    echo ptcads;
    else 
    echo '0';
}

function rotating_ads()
{
    if (defined('rotatingads'))
    echo rotatingads;
    else
    echo '0';
}

function mail_queue()
{
    if (defined('mailqueue'))
    echo mailqueue;
    else 
    echo '0';
}


// call cash_earnings('return') to return self cash earnings
function cash_earnings($d = 4, $f = 100, $point = '.', $comma = ',')
{
    list ($cash)=@mysql_fetch_row(@mysql_query("select sum(amount) from " . mysql_prefix . "accounting where username='$_SESSION[username]' and type='cash' and amount>0 and unixtime=0 and description!='".dldescription."' limit 1"));
    $cash=$cash / 100000 / $f;
    if ($d=='return' && $d)
        return $cash;
    else
        echo number_format($cash, $d, $point, $comma);
}

// call points_earnings('return') to return self points earnings
function points_earnings($d = 0, $f = 1, $point = ".", $comma = ",")
{
    list ($points)=@mysql_fetch_row(@mysql_query("select sum(amount) from " . mysql_prefix . "accounting where type='points' and username='$_SESSION[username]' and unixtime=0 and amount>0 and description!='".dldescription."' limit 1"));
    $points=$points / 100000 / $f;
    if ($d=='return' && $d)
        return $points;
    else
        echo number_format($points, $d, $point, $comma); 
}

// call dlcash_earnings('return') to return downline cash earnings
function dlcash_earnings($d = 4, $f = 100, $point = ".", $comma = ",")
{
    list ($cash)=@mysql_fetch_row(@mysql_query("select amount from " . mysql_prefix . "accounting where type='cash' and username='$_SESSION[username]' and description='".dldescription."' limit 1"));
    $cash=$cash / 100000 / $f;
    if ($d=='return' && $d)
        return $cash;
    else
        echo number_format($cash, $d, $point, $comma); 
}

// call dlpoints_earnings('return') to return downline points earnings
function dlpoints_earnings($d = 0, $f = 1, $point = ".", $comma = ",")
{
    list ($points)=@mysql_fetch_row( @mysql_query("select amount from " . mysql_prefix . "accounting where type='points' and username='$_SESSION[username]' and description='".dldescription."' limit 1"));
    $points=$points / 100000 / $f;
    if ($d=='return' && $d)
        return $points;
    else
        echo number_format($points, $d, $point, $comma); 
}

function cash_transactions($t = 'credits',   $L = "<tr><td>",
                               $M = "</td><td>", $R = "</td></tr>", $o = 'desc',
                               $date = "no",     $ds = "</td><td>", $d = 4,
                               $f = 100,$limit=10,$forward='Next',$back='Previous')
        {
         if (!@include scripts_dir.'includes/cash_transactions.inc.php') include 'includes/cash_transactions.inc.php';
        }
// Call cash_totals() to calculate credits, debits or all transactions
// ie: cash_totals('credits','return');
// will return (not echo) the total cash credits  

function cash_totals($t = 'all', $d = 4, $f = 100)
{


    if ($t == 'credits')
        $ttype="and amount>0";

    if ($t == 'debits')
        $ttype="and amount<0";


    list ($cash)=@mysql_fetch_row(
        @mysql_query("select sum(amount) from " . mysql_prefix . "accounting where username='{$_SESSION['username']}' $ttype and type='cash'"));
    $cash=$cash / 100000 / $f;

    if ($t == "owed" or $t == 'debits')
    {
    if ($cash < 0)
        {
        $cash=$cash * -1;
        }
    else
        {
        $cash=0;
        }
    }

    if ($t == 'all')
    {
        $_SESSION['quick_cash_total']=$cash;
    }

    if ($d == 'return' && $d)
        return $cash;
    else
        echo number_format($cash, $d);
}

function point_transactions($t = 'credits',    $L = "<tr><td>",
                            $M = "</td><td>",  $R = "</td></tr>",
                            $o = 'desc',       $date = "no",
                            $ds = "</td><td>", $d = 0, $f = 1,$limit=10,$forward='Next',$back='Previous')
{
    if (!@include scripts_dir.'includes/point_transactions.inc.php') include 'includes/point_transactions.inc.php';
}

// Call points_totals() to calculate credits, debits or all transactions
// ie: points_totals('credits','return');
// will return (not echo) the total points credits
     
function points_totals($t = 'all', $d = 0, $f = 1)
{
    if ($t == 'credits')
    $ttype="and amount>0";
    
    if ($t == 'debits')
    $ttype="and amount<0";


    list ($points)=@mysql_fetch_row(
    @mysql_query("select sum(amount) from " . mysql_prefix . "accounting where username='{$_SESSION['username']}' $ttype and type='points'"));
    $points=$points / 100000 / $f;

    if ($t == 'debits')
    {
        $points=$points * -1;
    }

    if ($t == 'all')
    {
        $_SESSION[quick_points_total]=$points;
    }
    if ($d == 'return' && $d)
        return $points;                     
    else
        echo number_format($points, $d);
}

function latest_visits($l = 15, $w = 175, $s = "<hr>", $o = "desc", $nf =
   'This ad has been deleted from the ad database')
{

    list($id, $type, $time)=@mysql_fetch_array(
    @mysql_query("select id,type,time from " . mysql_prefix . "latest_stats where username='{$_SESSION['username']}' limit 1"));
    $idlist=explode(",", $id);
    $typelist=explode(",", $type);
    $timelist=explode(",", $time);

    for ($idx=0; $idx <= $l and $idx < count($idlist); $idx++)
    {
        $adfound=0;

        if ($doline)
        echo $s;

        if ($typelist[$idx] == 'paidmail')
        {
            list($text, $url)=@mysql_fetch_row(
            @mysql_query("select ad_text,site_url from " . mysql_prefix . "email_ads where emailid=$idlist[$idx]"));

            $text=substr(
            str_replace("**", " ",
            str_replace("==", " ",
            str_replace("\n",
            " ", trim($text)))),
            0, $w);

            if ($text)
                $text=$text . "<br>";

            if (!$text and !$url){
                $text=$nf;
                $url=pages_url;
            }
            echo mytimeread(substr($timelist[$idx],0,4).'-'.substr($timelist[$idx],4,2).'-'.substr($timelist[$idx],6,2).' '.substr($timelist[$idx],8,2).':'.substr($timelist[$idx],10,2).':'.substr($timelist[$idx],12,2)). ' - <a href=' . runner_url . '?REDIRECT=' . rawurlencode($url). '&hash='.md5($url.key).' target=_blank>' . $text . '</a>';
        }

        if ($typelist[$idx] == 'signup')
        {
            echo mytimeread($timelist[$idx]). "<br>";
            $ptsrow=@mysql_fetch_array(
            @mysql_query("select * from " . mysql_prefix . "pts_ads where ptsid=$idlist[$idx]"));

            if ($ptsrow[image_url])
            {
                $width='';
                $height='';

                if ($ptsrow['img_width'])
                $width="width=$ptsrow[img_width]";

                if ($ptsrow['img_height'])
                $height="height=$ptsrow[img_height]";

                echo
                '<a href=' . runner_url . '?REDIRECT=' . rawurlencode(
                $ptsrow['site_url']). '&hash='.md5($ptsrow['site_url'].key).' target=_blank><img src=' . runner_url . '?REDIRECT=' . rawurlencode($ptsrow[image_url]). '&hash='.md5($ptsrow['image_url'].key).' alt="' . $alt_text . '" ' . $width . ' ' . $height . ' border=0></a>';
            }
            else
            {
                if (!$ptsrow['html'])
                $ptsrow['html']=$nf;

                echo "<table border=0 cellpadding=0 cellspacing=0><tr><td>{$ptsrow['html']}</td></tr></table>";
            }
        }

        if ($typelist[$idx] == 'ptc')
        {
            echo mytimeread(substr($timelist[$idx],0,4).'-'.substr($timelist[$idx],4,2).'-'.substr($timelist[$idx],6,2).' '.substr($timelist[$idx],8,2).':'.substr($timelist[$idx],10,2).':'.substr($timelist[$idx],12,2)) . "<br>";

            $ptcrow=@mysql_fetch_array(
            @mysql_query("select * from " . mysql_prefix . "ptc_ads where ptcid=$idlist[$idx]"));

            if ($ptcrow['image_url'])
            {
                $width='';
                $height='';

                if ($ptcrow['img_width'])
                    $width="width=$ptcrow[img_width]";

                if ($ptcrow['img_height'])
                    $height="height=$ptcrow[img_height]";

                echo '<a href=' . runner_url . '?REDIRECT=' . rawurlencode($ptcrow['site_url']). '&hash='.md5($ptcrow['site_url'].key).' target=_blank><img src=' . runner_url . '?REDIRECT=' . rawurlencode($ptcrow['image_url']). '&hash='.md5($ptcrow['image_url'].key).' alt="' . $alt_text . '" ' . $width . ' ' . $height . ' border=0></a>';
            }
            else if($ptcrow['text_ad'])
            {
                echo '<a href="' . runner_url . '?REDIRECT=' . rawurlencode($ptcrow['site_url']). '&amp;hash='.md5($ptcrow['site_url'].key).'" target="_blank">'
                        . htmlentities($ptcrow['text_ad'], ENT_QUOTES)
                        . '</a>';
            }
            else
            {
                if (!$ptcrow['html'])
                    $ptcrow['html']=$nf;

                echo "<table border=0 cellpadding=0 cellspacing=0><tr><td>{$ptcrow['html']}</td></tr></table>";
            }
        }

        $doline=1;
    }
}

function mytimeread($t)
{
    if($t == '00000000000000' or $t == '0000-00-00 00:00:00' or empty($t))
    {
        return '';
    }
    //------------------------------------------------------------------
    // Format MySQL timestamps according to user's timeformat- CC211
    //------------------------------------------------------------------
    $unixtime = strtotime($t);
    if ($unixtime == false OR $unixtime == -1)
    {
        // Support for old MySQL4 style timestamps - CC212
        return date(timeformat, strtotime(substr($t,0,4).'-'.substr($t,4,2).'-'.substr($t,6,2).' '.substr($t,8,2).':'.substr($t,10,2).':'.substr($t,12,2)));
    }else{
        return date(timeformat, $unixtime);
    }
}


function creditulclicks($user, $v, $t)
{
    @mysql_query ("insert into " . mysql_prefix . "clicks_to_process set username='$user',amount=$v,type='$t'");
}

function creditul($upline, $v, $t, $comm = "", $desc = "")
{
    if (!@include scripts_dir.'includes/creditul.inc.php') include 'includes/creditul.inc.php'; 
}

function level_total($l = 'all')
{
    if (!@include scripts_dir.'includes/level_total.inc.php') include 'includes/level_total.inc.php';
}

function interestsform($what = 'blank', $d = 'word', $preselected = 0)
{
    if ($_POST['keyword'])
    {
        foreach($_POST['keyword'] as $key => $value)
        {
            $interestsform[strtolower($value)]=1;
        }

        if ($d == 'checked' and $interestsform[strtolower($what)])
        {
            echo "checked";
            return;
        }

        if ($d == 'selected' and $interestsform[strtolower($what)])
        {
            echo "selected";
            return;
        }
        if ($d == 'word' and $interestsform[strtolower($what)])
        {
            echo $what;
        }
    }else
    //-----------------------------------------------
    // Interest preselection for new members - CC218
    //-----------------------------------------------
    {
        if($preselected AND $d != 'word')
        {
            echo $d;
        }
        if($preselected AND $d == 'word')
        {
            echo $what;
        }
    }
}

function interests($what = 'blank', $d = 'word')
{
    global $interests;

    if (!$interests)
    {
        list ($interests)=@mysql_fetch_row(
        @mysql_query("select keywords from " . mysql_prefix . "interests where username='{$_SESSION['username']}'"));
    }

    if ($interests)
    {
        if (strpos('.'.strtolower($interests),
        '||' . strtolower($what). '||'))
        {
            if ($d == 'checked')
            {
                echo "checked";
                return;
            }
            
            if ($d == 'selected')
            {
                echo "selected";
                return;
            }

            if ($d == 'word')
            {
                echo $what;
            }

            if ($d == 'return')
            {
                return (1);
            }
        }
    }

    return (0);
}

function userform($what = 'blank')
{
    if ($what=='account_type' && $_POST['userform'][$what]=='advertiser')
    echo 'checked';
    else
    echo safeentities($_POST['userform'][$what]);
}

function safeentities($text){
return str_replace(array('>', '<', '"', '\''),array('&gt;', '&lt;', '&quot;', '&#039;'), $text); 
}


// Call user() in order to set the $userinfo array 
// ie: user('email','return');  
// This will populate the $userinfo array 
// and also return the users email address 

function user($what = 'blank',$r='')
{
    global $userinfo;
    if (!$userinfo['username'])
        $userinfo=@mysql_fetch_array(

    @mysql_query('select *,signup_date+0 as ts_signup_date from ' . mysql_prefix . 'users where username="' . $_SESSION['username'] . '" limit 1'));

    if ($what == 'vacation')
    {
        list($y, $m, $d)=explode('-', $userinfo['vacation']);

        $vacation=$m . '/' . $d . '/' . $y;

        if ($vacation == '00/00/0000' or $vacation == '//')
        {
            $vacation='';
        }
        if ($r)
            return $vacation;
        else
            echo $vacation;
    }
    else
    {
        if ($r)
        return $userinfo[$what];
        else 
        echo $userinfo[$what];
    }
}

function inboxcount()
{
include scripts_dir.'includes/inboxcount.inc.php'; 
}

function show_autosurf_urls($ID=1,$L = '<tr><td>',            $M = '</td><td>',
$R = '</td></td>', $active = 'Active<hr>',
$inactive = 'Inactive<hr>',
$approved = 'Approved',
$noapproved = 'Not Yet Approved',
$deactivate = 'Deactivate<br>',
$activate = 'Activate<br>', $D = 'Delete',
$B = '<hr>Buy Exposures',   $P = 'use points',
$C = 'use cash',            $S = 'Buy Now',$E='<table border=0 bgcolor=red width=100%><tr><th align=center><font face=arial color=white>The URL you entered was invalid. No changes saved</font></th></tr></table>')
{
   @include scripts_dir.'includes/show_autosurf_urls.inc.php'; 
}

function showinbox($L = "<tr><td>", $M = "</td><td>", $R = "</td></td>",
   $D = "Delete",   $C = 'Check All', $U = 'Uncheck All')
{
@include scripts_dir.'includes/showinbox.inc.php';        
}

function email_ad_stats($L = '<tr><td>',   $M = '</td><td>',
$R = '</td></tr>', $S = 'dont_show',$cols=30,$rows=3)
{
@include scripts_dir.'includes/email_ad_stats.inc.php'; 
}

function html_ad_stats($L = "<tr><td>", $M = "</td><td>", $R = "</td></tr>",
   $S = 'dont_show')
{
 @include scripts_dir.'includes/html_ad_stats.inc.php'; 
}

function banner_ad_stats($L = "<tr><td>",   $M = "</td><td>",
 $R = "</td></tr>", $S = 'dont_show', $C = 'Clicks',
 $V = 'Views')
{
 @include scripts_dir.'includes/banner_ad_stats.inc.php';
}

function ptc_ad_stats($L = "<tr><td>",  $M = "</td><td>", $R = "</td></tr>",
  $S = 'dont_show', $C = 'Clicks',    $V = 'Views')
{
@include scripts_dir.'includes/ptc_ad_stats.inc.php';
}

function pts_ad_stats($L = "<tr><td>",  $M = "</td><td>", $R = "</td></tr>",
  $S = 'dont_show', $C = 'Sign-Ups',  $V = 'Views')
{
 @include scripts_dir.'includes/pts_ad_stats.inc.php';
}

function review_ad_stats($L = "<tr><td>",  $M = "</td><td>", 
  $R = "</td></tr>",$S = 'dont_show', $C = 'Reviews',
  $V = 'Views')
{
 @include scripts_dir.'includes/review_ad_stats.inc.php';
}



function form_errors($f, $r, $u, $b, $e)
{
 @include scripts_dir.'includes/form_errors.inc.php';
}

function popup_ad_stats($L = "<tr><td>",   $M = "</td><td>",
$R = "</td></tr>", $T = 'popunder')
{
 @include scripts_dir.'includes/popup_ad_stats.inc.php';     
}

function stri_replace($find, $replace, $string)
{
$value=@include scripts_dir.'includes/stri_replace.inc.php'; 
return $value;
}

function add_session($sym='&')
{
  if ($_COOKIE[session_name()]!=session_id())
        return $sym.session_name().'='.session_id();
}

function append_note($text, $username)
{
    //Fetch current note
    list($old_note) = mysql_fetch_array(mysql_query("SELECT notes FROM `".mysql_prefix."notes` WHERE username='$username'"));
    $note = $text ."\n". $old_note . "\n";
    //Update note
    mysql_query("REPLACE INTO ".mysql_prefix."notes (username, notes) VALUES ('$username','".mysql_real_escape_string($note)."')"); 
}

function plugin($plugin='',$function='public_page', $arg='')
{
    global $plugin_classes;
    if(!class_exists($plugin))
    {
        $entry=@mysql_result(@mysql_query('select directory from '.mysql_prefix.'plugins where classname="'.$plugin.'"'),0,0);     
        $plugin_dir=scripts_dir.'plugins/';
        if (!@file_exists($plugin_dir.'/plugin.php'))
        {
            $plugin_dir='../plugins/';
            if (!@file_exists($plugin_dir.'/plugin.php'))
                {return;}
        }
        include_once($plugin_dir . "plugin.php");
        $plugins = array();
        $filename =$plugin_dir . $entry.'/plugin.php';
        if(@file_exists($filename))
        {
            include_once($filename);
            if (count($plugins))
            $plugin_classes[$plugins[0]['classname']] =  new $plugins[0]['classname']();
        }
    }    
    
    if(class_exists($plugin) && method_exists($plugin_classes[$plugin], 
    $function))
    return(call_user_func(array($plugin_classes[$plugin], $function), 
    $arg));
}


function load_plugins()
{
    global $plugin_classes;
    $plugin_dir=scripts_dir.'plugins/';
    if (!@file_exists($plugin_dir.'/plugin.php'))
    {
        $plugin_dir='../plugins/';
        if (!@file_exists($plugin_dir.'/plugin.php'))
            {return;}
    }
    include_once($plugin_dir . "plugin.php");
    $plugins = array();
    $dir_plugins = dir($plugin_dir);
    while(($entry = $dir_plugins->read()) !== false)
    {
        if($entry=='.' || $entry=='..' )
        continue;

        $filename =$plugin_dir . $entry.'/plugin.php';

        if(!@file_exists($filename))
        continue;

        include_once($filename);
        $runner=0;
        $pc=count($plugins)-1;
        $plugin_classes[$plugins[$pc]['classname']] =  new $plugins[$pc]['classname']();

        if (!plugin($plugins[$pc]['classname'],'runner'))
            $runner=1;
        @mysql_query('replace into '.mysql_prefix.'plugins set classname="'.$plugins[$pc]['classname'].'",directory="'.$entry.'",runner='.$runner);
    }
}

// the  functions listed here are obsolete. 
// please no longer use them as they will be removed overtime

// look in the scripts/templates for new pages that replace
// the old pages that used these obsolete functions.

// the functions are being replace with the single function action()
// this is to reduce the file size of the the main functions.inc.php 
// and incress performance of the cc scripts

function redeem_list(){
action('show_redemptions');
}

function getad($get, $js = "")
{
action('show_rotating_ad',$get.'|'.$js);
}

function imageCompression($imgfile="",$thumbsize=0,$savePath=NULL) {


    list($width,$height)=getimagesize($imgfile);
    /* The width and the height of the image also the getimagesize retrieve other information as well   */
    $imgratio=$width/$height; 
    /*
    To compress the image we will calculate the ration 
    For eg. if the image width=700 and the height = 921 then the ration is 0.77...
    if means the image must be compression from its height and the width is based on its height
    so the newheight = thumbsize and the newwidth is thumbsize*0.77...
    */
   
   // if($imgratio>1) {
        $newwidth=$thumbsize;
        @$newheight=$thumbsize/$imgratio;
   /* } else {
        $newheight=$thumbsize;       
        $newwidth=$thumbsize*$imgratio;
    }
   */
    $thumb=imagecreatetruecolor($newwidth,$newheight); // Making a new true color image
    $source=imagecreatefromjpeg($imgfile); // Now it will create a new image from the source
    @imagecopyresampled($thumb,$source,0,0,0,0,$newwidth,$newheight,$width,$height);  // Copy and resize the image
    @imagejpeg($thumb,$savePath,70);
    /*
    Out put of image 
    if the $savePath is null then it will display the image in the browser
    */
    @imagedestroy($thumb);
    /*
        Destroy the image
    */
   
}
?>

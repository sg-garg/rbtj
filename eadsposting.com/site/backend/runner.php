<?php
    /*
    ##########################################################
    ## This script (CashCrusader) is copyrighted to CashCrusader Software 
    ## selling duplications of this script
    ## is a violation of the copyright and purchase agreement
    ## unless you have received approval from CashCrusader Software 
    ## before doing so.
    ##
    ## LICENSES CAN NOT BE TRANSFERRED TO A NEW DOMAIN NAME
    ## ONCE THE SITE HAS AN ESTABLISHED INTERNET  
    ##
    ## Alteration of this script in any way voids any
    ## responsibility CashCrusader Software has towards the
    ## functioning of the script.
    ##########################################################
    */ 
  
    require_once 'functions.inc.php';
 
    if ($_GET['REDIRECT'] && $_GET['hash']==md5($_GET['REDIRECT'].key))
    {
        header ('Location: ' . $_GET['REDIRECT']);
        exit;
    }
    chdir(pages_dir);
 
    if ($_GET['CH']=='start')
        include scripts_dir.'includes/start_chat.php';

    if ($_GET['CH']=='history')
        include scripts_dir.'includes/chat_history.php';

    if ($_GET['CH']=='javascript')
        include scripts_dir.'includes/chat_javascript.php';

    if ($_GET['CH']=='users')
        include scripts_dir.'includes/chat_users.php';

    if ($_GET['CH']=='messages')
        include scripts_dir.'includes/chat_messages.php';

    if ($_GET['showearnings'] || $_GET['showstats'])
        include scripts_dir.'includes/showstats.php'; 

    if ($_GET['TN'] == 1)
    {
        showturing($_SESSION['img1'][$_GET['KE']]);
        exit;
    }

    if ($_GET['TN'] == 2)
    {
        showturing($_SESSION['img2'][$_GET['KE']]);
        exit;
    }
      
    if ($_GET['BA'] && $_GET['hash']==md5($_GET['url'].key))
    {
        @mysql_query ('update ' . mysql_prefix . 'rotating_ads set clicks=clicks+1 where bannerid='.$_GET['BA']);
         
        header ('Location: ' . $_GET['url']);
        exit;                
    }
    $pluginresult=@mysql_query('select classname from '.mysql_prefix.'plugins where runner=1');
    while($key=@mysql_fetch_row($pluginresult))
    {
        plugin($key[0],'runner');
    }

if ($_GET['EA'])
{
    $_GET['EA']=preg_replace('([^0-9])', '', $_GET['EA']);
    if ($_SESSION['EA'.$_GET['EA']]['CRTI'] && $_SESSION['EA'.$_GET['EA']]['CRTI'] < unixtime && $_GET['FR'])
    {
        include scripts_dir.'includes/ea_credit.php'; 
    }
    if ($_GET['FR'] && $_SESSION['EA'.$_GET['EA']]['VT'] && $_SESSION['EA'.$_GET['EA']]['TI'])
    {
        include scripts_dir.'includes/ea_timer.php'; 
    }
    include scripts_dir.'includes/ea_start.php';
}

if ($_GET['PA'])
{
    
    $_GET['PA']=preg_replace('([^0-9])', '', $_GET['PA']);
    if ($_SESSION['PA'.$_GET['PA']]['CRTI'] && $_SESSION['PA'.$_GET['PA']]['CRTI'] < unixtime && $_GET['FR'])
    {
       include scripts_dir.'includes/pa_credit.php';
    }
    if ($_GET['FR'] && $_SESSION['PA'.$_GET['PA']]['VT'] && $_SESSION['PA'.$_GET['PA']]['TI'])
    {
       include scripts_dir.'includes/pa_timer.php';
    }
    include scripts_dir.'includes/pa_start.php';
}

if ($_GET['GA'])
{
    if ($_GET['IFRAME'] || !$_GET['JS'])
    {
	    getad($_GET['GA']);
    }
    else 
    {
        echo 'document.write(\'';
        getad($_GET['GA'], 'js');
        echo '\');';
    }
    exit;
}

    if ($_GET['IM'])
        include scripts_dir.'includes/show_im.php';

    if ($_GET['AS'])
        include scripts_dir.'includes/autosurf.php'; 
    
    if ($_GET['SP'])
        include scripts_dir.'includes/startpage.php';   

   if ($_POST['txn_type'])
        include scripts_dir.'includes/ipn.php';
        

        echo 'CashCrusader Version:' . version;

        if (!ini_get('safe_mode'))
        {
            $days=number_format((unixtime - last_backup) / 86400, 2);
            echo "<br>Last MySQL backup from admin: $days days";
        }
echo '<br><Br>Commissions settings:<br>Point ads: '.pointclicks.' %<br>Cash ads: '.cashclicks.' %<br>Must login every '.nocreditdays.' days and<br> be at least '.nocreditclicks.'% as active as your downline to get commission from downline clicks<br><br>Sales Commission: '.sales_comm.' %<br><br>Only support registered sites. <a href=http://cashcrusadersoftware.com/customers.php?getdomain='.domain.'>Click here to verify registration</a>'; 
?>

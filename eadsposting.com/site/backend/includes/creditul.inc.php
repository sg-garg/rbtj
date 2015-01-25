<?php
if (!defined('version'))
{exit;}
        
global $commissions_accounting_table;

$commissions_table =str_replace("accounting", "", $commissions_accounting_table);
$usertable=mysql_prefix;
$levels=array();

if (!$comm)
{
    if ($t == 'points')
        $levels=explode(",", pointclicks);
    else
        $levels=explode(",", cashclicks);
}
else
{
    $levels=explode(",", str_replace(" ", "", $comm));
}

if (defined('nocreditclicks') && nocreditclicks>0)
{
    list ($usercounter)=@mysql_fetch_row(@mysql_query('select '.$t.'_clicks from ' . mysql_prefix . 'last_login where username="'.$upline.'"'));
}        

for ($idx=0; $idx < count($levels); $idx++)
{
    list ($upline)=@mysql_fetch_row(@mysql_query("select upline from " . $usertable . "users where username='$upline'"));
    $usertable=$commissions_table;
    if (!$upline)
    {
        break;
    }
    list ($commission_amount,$account_type)=@mysql_fetch_row(@mysql_query("select commission_amount,account_type from " . $usertable . "users where username='$upline'"));
    $amount=$v * (($levels[$idx]+($commission_amount/100000)) / 100);
    $goforit=1;

    if ($account_type=='suspended' || $account_type=='canceled' || $account_type=='advertiser')
    {
	    $goforit=0;
    }

    if ((defined('nocreditclicks') && nocreditclicks>0) || (defined('nocreditdays') && nocreditdays>0))
    {
        list($uplinecounter, $uplinetime)=@mysql_fetch_row(@mysql_query('select '.$t.'_clicks,time from ' . mysql_prefix . 'last_login where username="'.$upline.'"'));

        if (defined('nocreditdays') && nocreditdays>0)
        {
            if (strtotime(substr($uplinetime,0,4).'-'.substr($uplinetime,4,2).'-'.substr($uplinetime,6,2).' '.substr($uplinetime,8,2).':00:00') < unixtime - (60 * 60 * 24 * nocreditdays))
                $goforit=0;
        }
                     
        if ($goforit == 1 && (defined('nocreditclicks') && nocreditclicks>0))
        {
            if ($usercounter * (nocreditclicks / 100) >= $uplinecounter)
                $goforit=0;
        }
    }

    $description=dldescription;
    $uts=0;

    if ($desc)
    {
        $description=$desc;
        $uts=unixtime;
    }

    if ($goforit)
    {
        $amount = number_format($amount,0,'',''); // Circumvent PHP5 bug http://bugs.php.net/bug.php?id=43053
        $update = @mysql_query('UPDATE '.$commissions_accounting_table.' SET amount=amount+'.$amount.' WHERE unixtime='.$uts.' and type="'.$t.'" and username= "'.$upline.'" and description="'.$description.'" limit 1');

        if (!mysql_affected_rows())
            $update=@mysql_query('INSERT INTO '.$commissions_accounting_table.' set transid="'.maketransid($upline).'",username ="'.$upline.'",unixtime='.$uts.',description="'.$description.'",amount='.$amount.',type="'.$t.'"');
                    
    }
    if ($account_type=='canceled')
          $idx--;

}
return 1;
?>

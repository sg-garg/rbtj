<?php
if (!defined('version')){exit;} 

$ad='';
$get=str_replace('%', '', $_GET['ADGROUP']);
$ad='';
if ($get)
    $get='category="' . $get. '" and ';

$row=@mysql_fetch_array(@mysql_query('select * from ' . mysql_prefix . 'rotating_ads where '.$get.' (text_ad!="" or alt_text!="") and site_url!="" and runad>0 order by runad limit 1'));
$setviews='views+1';

$setrunad=mysqldate;
if ($row['run_type'] == 'views' and $row['run_quantity'] <= $row['views'] - 1)
{
    $setviews='run_quantity,time=runad';
    $setrunad=0;
}

@mysql_query ('update LOW_PRIORITY ' . mysql_prefix . 'rotating_ads set views=' . $setviews . ',runad="' . $setrunad . '" where bannerid="' . $row['bannerid'] . '"');

$row['site_url'] = str_replace("#USERNAME#", $_SESSION['username'], $row['site_url']);

if (!$row['text_ad'])
    $row['text_ad']=$row['alt_text'];

$ad.= '<a href="' . runner_url . '?BA=' . $row['bannerid'] .'&hash='.md5($row['site_url'].key).'&url='. rawurlencode($row['site_url']). '" target=_blank">' . $row['text_ad'] . '</a>';

return($ad);
?>
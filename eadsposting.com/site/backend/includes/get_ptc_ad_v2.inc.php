<?php
if (!defined('version'))
{
    exit;
}

if (!$_GET['startpos'])
{
    $_GET['startpos']=0;
}
$_GET['startpos'] = (int)$_GET['startpos'];

//----------------------------------------
// Process arguments for the PTC include
//----------------------------------------
$args = explode(',', $args);
$get = $args[0];
if(empty($args[1]))
{
    $count = 5;
}else
{
    $count = $args[1];
}
$left = (int)$args[2];
$received = (int)$args[3];
if(!isset($args[4]))
{
    $worth = 1;
}else
{
    $worth = (int)$args[4];
}
$clicks_msg = '';

//----------------------------------
// Translation support - CC220
//----------------------------------
global $ptc_customization;
if(!$ptc_customization['translated'])
{
    $ptc_customization['message'] = 'The ad above is worth';
    $ptc_customization['notfound'] = 'Sorry, no ads are available for you to click on this page at this time';
    $ptc_customization['points'] = 'point(s)';
    $ptc_customization['cash'] = 'cent(s)';
    $ptc_customization['factor'] = 1;
    $ptc_customization['forward'] = 'Next Page';
    $ptc_customization['back'] = 'Previous Page';
}
//----------------------------------
// CSS / target support - CC220
//----------------------------------
if(!empty($ptc_customization['link_class']))
{
    $link_style = ' class="'. htmlentities($ptc_customization['link_class'], ENT_QUOTES).'" ';
}else{
    $link_style = '';
}
if(!empty($ptc_customization['target']))
{
    $link_target = htmlentities($ptc_customization['target'], ENT_QUOTES);
}else{
    $link_target = '_ptc';
}

//-----------------------------------------

if (!$reloadpage)
{
    echo '
    <script type="text/javascript">
        function creditpop(ptcid)
        { 
            var url=\'' . runner_url . '?PA=\' + ptcid +\''.add_session().'\'; 
            ptccredit=window.open(url,\'ptccredit\',\'toolbar=no,location=no,scrollbars=no,resizable=no,width='.popup_width.',height='.popup_height.'\'); 
            setTimeout("window.location.replace( \''.$_SERVER['PHP_SELF'].'?blur=1&startpos='.$_GET['startpos'].add_session().'\' );", 2000 ); 
        } 
        function reloadpage(waittime)
        { 
            setTimeout("window.location.replace( \''.$_SERVER['PHP_SELF'].'?blur=1&startpos='.$_GET['startpos'].add_session().'\' );", waittime*900 ); 
        } 
    </script>';
    $reloadpage=1;
}

if ($_SESSION['lastptc'])
{
    $clicklist='ptcid!="'.$_SESSION['lastptc'].'" and ';
}
//----------------------------------
// Get clicked ads
//----------------------------------
$getclicks = mysql_query('select id from ' . mysql_prefix . 'paid_clicks where username="'.$_SESSION['username'].'"');
while ($setclicks = mysql_fetch_row($getclicks))
{
    $clicklist = $clicklist . 'ptcid!="'.$setclicks[0].'" and '; 
}   

//----------------------------------
// Get clickable ads, 
// * country targeting      -- CC211
// * account type targeting -- CC222
//----------------------------------
$user_country = mysql_real_escape_string(user('country','return'));
$account_type = mysql_real_escape_string(user('account_type','return'));
if(empty($account_type)) $account_type = 'free';

$getrow = mysql_query('
    SELECT * FROM ' . mysql_prefix . 'ptc_ads 
    WHERE (targeting LIKE "%|c:'. $user_country .'|%" OR targeting = "") 
        AND (targeting_mtype LIKE "%|g:'. $account_type .'|%" OR targeting_mtype = "") 
        AND category LIKE "'.$get.'" AND description!="#PAID-START-PAGE#" 
        AND '.$clicklist.' 
        (
            (run_quantity>clicks AND run_type="clicks") OR 
            (run_quantity>views AND run_type="views") OR 
            run_type="ongoing" OR 
            (run_type="date" AND run_quantity>='.mysqldate.')
        ) 
    ORDER BY vtype ASC,value DESC,ptcid ASC
    LIMIT '.$_GET['startpos'].','.$count);


$backpos=$_GET['startpos'];

while ($row = mysql_fetch_array($getrow))
{
    $clicks_msg = '';
    $_GET['startpos']++;

    $width='';
    $height='';

    if ($row['img_width'])
    {
        $width='width="'.$row['img_width'].'"';
    }

    if ($row['img_height'])
    {
        $height='height="'.$row['img_height'].'"';
    }

    if($row['run_type'] == 'clicks' AND $left)
    {
        $clicks_msg = $row['run_quantity'] - $row['clicks'];
        $clicks_msg = ' Clicks left <b>'.$clicks_msg.'</b>';
    }

    if($row['run_type'] == 'clicks' AND $left AND $received)
    {
        $clicks_msg .= ' / ';
    }

    if($received)
    {
        $clicks_msg .= ' Clicks received <b>'.$row['clicks'].'</b>';
    }

    if($row['image_url'])
    {
        echo '
            <table border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
            <tr>
            <td>
                <a href="' . runner_url . '?PA=' . $row['ptcid'] . add_session().'" target="'.$link_target.'" onclick="javascript:reloadpage(' . $row['timer'] . ')">
                    <img src="' . runner_url . '?REDIRECT=' . rawurlencode($row['image_url']). '&amp;hash='.md5($row['image_url'].key).'" alt="' . $row['alt_text'] . '" ' . $width . ' ' . $height . ' border="0">
                </a>
            </td>
            </tr>
            </table>
               ';
    }
    elseif ($row['text_ad'])
    {
        echo '
            <table '. $width .' '.$height.' border="0" cellpadding="0 cellspacing="0">
            <tr>
            <td>
                <a '.$link_style.' href="' . runner_url . '?PA=' . $row['ptcid'] . add_session().'" target="'.$link_target.'" onclick="javascript:reloadpage(' . $row['timer'] . ')">' . $row['text_ad'] . '</a>
            </td>
            </tr>
            </table>
              ';
    } 
    else 
    {
        $row['html']=str_replace("#USERNAME#", $_SESSION['username'], $row['html']);
        $row['html']=stri_replace("<a\r\n", "<a target='$link_target' onClick=\"javascript:creditpop('" . $row['ptcid'] . "')\" ", $row['html']);
        $row['html']=stri_replace("<form\r\n", "<form target='$link_target' onSubmit=\"javascript:creditpop('" . $row['ptcid'] . "')\" ", $row['html']);
        $row['html']=stri_replace("<a\n", "<a target='$link_target' onClick=\"javascript:creditpop('" . $row['ptcid'] . "')\" ", $row['html']);
        $row['html']=stri_replace("<form\n","<form target='$link_target' onSubmit=\"javascript:creditpop('" . $row['ptcid'] . "')\" ", $row['html']);
        $row['html']=stri_replace("<a ","<a target='$link_target' onClick=\"javascript:creditpop('" . $row['ptcid'] . "')\" ", $row['html']);
        $row['html']=stri_replace("<form ","<form target='$link_target' onSubmit=\"javascript:creditpop('" . $row['ptcid'] . "')\" ", $row['html']);
        echo '
            <table border="0" cellpadding="0" cellspacing="0">
            <tr>
            <td>
                '.$row['html'].'
            </td>
            </tr>
            </table>';
    }
    $mdgroup = "#" . substr(md5($row['category']), 0, 8). "#";
    $typemsg = htmlentities($ptc_customization['points'], ENT_QUOTES);
    $amount = $row['value'] / 100000;

    if ($row['vtype'] == 'cash')
    {
        $typemsg = htmlentities($ptc_customization['cash'], ENT_QUOTES);
        $amount=$amount / $ptc_customization['factor'];
    }

    if($worth)
    {
        echo htmlentities($ptc_customization['message'], ENT_QUOTES) . ' <b>' . $amount . '</b> ' . $typemsg . '<br>';
    }
    echo $clicks_msg .'<br><br>';
    mysql_query('update ' . mysql_prefix . 'ptc_ads set views=views+1 where ptcid="'.$row['ptcid'].'"');
}

if (!$typemsg)
{
    echo htmlentities($ptc_customization['notfound'], ENT_QUOTES) . '<br>';
}
else
{
    echo '<br>';
    $page = $backpos / $count + 1;
    if($backpos - $count >= 0)
    {
        $backpos=$backpos - $count;
        echo '<a '.$link_style.' href="'.$_SERVER['PHP_SELF'].'?startpos='.$backpos.'">'.htmlentities($ptc_customization['back'], ENT_QUOTES).'</a>';
    }
    if ($_GET['startpos'] / $count == (int)($_GET['startpos'] / $count))
    {
        echo ' <a '.$link_style.' href="'.$_SERVER['PHP_SELF'].'?startpos='.$_GET['startpos'].'">'.htmlentities($ptc_customization['forward'],ENT_QUOTES).'</a>';
    }
    echo '<br><br><b>'.$page.'</b><br> ';
}
if ($_GET['blur'])
{
    echo '
        <script type="text/javascript">
            setTimeout("window.blur();",1000);
        </script>';
}
return 1;
?>

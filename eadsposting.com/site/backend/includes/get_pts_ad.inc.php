<?php
if (!defined('version')){exit;}
        
$pasteinst=trim($pasteinst);

if (!$_GET['startpos'])
{
    $_GET['startpos']=0;
}

if (!$_SESSION['ptslist'])
{
    $getclicks = @mysql_query('select * from ' . mysql_prefix . 'pts_ads where category like "'.$get.'" and ((run_quantity>signups  and run_type="signups") or (run_quantity>views and run_type="views") or run_type="ongoing" or (run_type="date" and run_quantity>='.mysqldate.'))');

    while ($setclicks=@mysql_fetch_array($getclicks))
    {
        list ($userchk)=@mysql_fetch_row(@mysql_query('select username from ' . mysql_prefix . 'paid_signups_'.$setclicks['ptsid'].' where username="'.$_SESSION['username'].'"'));

        if ($userchk == $_SESSION['username'])
        {
            $_SESSION['ptslist']['ID'.$setclicks['ptsid']]=1;
        }
    }

}
if ($_SESSION['ptslist'])
{
    foreach($_SESSION['ptslist'] as $keyvalue => $value) 
    {
        $keyvalue=substr($keyvalue,2,strlen($keyvalue)-2);
        $clicklist=$clicklist . 'ptsid!="'.$keyvalue.'" and ';
    } 
} 
$getrow = @mysql_query('select * from ' . mysql_prefix . 'pts_ads where category like "'.$get.'" and '.$clicklist.' ((run_quantity>signups  and run_type="signups") or (run_quantity>views and run_type="views") or run_type="ongoing" or (run_type="date" and run_quantity>='.mysqldate.')) order by vtype,value desc limit '.$_GET['startpos'].',5');
$backpos=$_GET['startpos'];

while ($row=@mysql_fetch_array($getrow))
{
    $_GET['startpos']++;


    if ($row['image_url'])
    {
        $width='';
        $height='';

        if ($row['img_width'])
        {
            $width="width=$row[img_width]";
        }

        if ($row['img_height'])
        {
            $height="height=$row[img_height]";
        }

        $row['site_url'] = str_replace("#USERNAME#", $_SESSION['username'], $row['site_url']);
        $row['site_url'] = str_replace("#PTSID#", $row['ptsid'], $row['site_url']);

        echo '<table border=0 cellpadding=0 cellspacing=0 bgcolor=ffffff><form method=post><tr><td><a href=' . runner_url . '?REDIRECT=' . rawurlencode(
        $row['site_url']). '&hash='.md5($row['site_url'].key).' target=_pts><img src=' . runner_url . '?REDIRECT=' . rawurlencode($row['image_url']). '&hash='.md5($row['image_url'].key).' alt="' . $row['alt_text'] . '" ' . $width . ' ' . $height . ' border=0></a></td></tr><tr><td align=center><input type=hidden value=' . $row['ptsid'] . ' name=ptsconfirm><input type=hidden name=pasteinstcode value='.md5($pasteinst).strlen($pasteinst).'><textarea name=ptsemail rows=4 cols=30>' . $pasteinst . '</textarea><br><input type=submit value="' . $submitbutton . '"></td></tr></form></table>';
    }
    else
    {
        $row['html' ] =str_replace("#USERNAME#", $_SESSION['username'], $row['html']);
        $row['html'] = str_replace("#PTSID#", $row['ptsid'], $row['html']);

        echo "<table border=0 cellpadding=0 cellspacing=0><tr><td>$row[html]</td></tr><form method=post><tr><td align=center><input type=hidden name=pasteinstcode value=".md5($pasteinst).strlen($pasteinst)."><input type=hidden value=$row[ptsid] name=ptsconfirm><textarea name=ptsemail rows=4 cols=30>$pasteinst</textarea><br><input type=submit value='$submitbutton'></td></tr></form></table>";
    }

    $mdgroup="#" . substr(md5($row['category']), 0, 8). "#";
    $typemsg=$points;
    $amount=$row['value'] / 100000;

    if ($row['vtype'] == 'cash')
    {
        $typemsg=$cash;
        $amount=$amount / $factor;
    }

    echo $message . " " . $amount . " " . $typemsg . $closing;
    @mysql_query ("update " . mysql_prefix . "pts_ads set views=views+1 where ptsid='$row[ptsid]'");
}

if (!$typemsg)
{
    echo $notfound . "<br>";
}
else
{
    echo "<br>";
    $page=$backpos / 5 + 1;

    if ($backpos - 5 >= 0)
    {
        $backpos=$backpos - 5;
        echo '<a href='.$_SERVER['PHP_SELF'].'?startpos='.$backpos.'>'.$back.'</a>';
    }

    if ($_GET['startpos'] / 5 == intval($_GET['startpos'] / 5))
    {
        echo ' <a href='.$_SERVER['PHP_SELF'].'?startpos='.$_GET['startpos'].'>'.$forward.'</a>';
    }

    echo "<br><br><b>$page</b><br> ";
}

return 1;
?>

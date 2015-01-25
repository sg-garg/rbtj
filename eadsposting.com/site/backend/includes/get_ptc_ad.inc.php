<?php
if (!defined('version')){
exit;}

    if (!$_GET['startpos'])
    {
            $_GET['startpos']=0;
    }
    $_GET['startpos'] = (int)$_GET['startpos'];

if (!$reloadpage){
        echo '<SCRIPT>
function creditpop(ptcid){ 
var url=\'' . runner_url . '?PA=\' + ptcid +\''.add_session().'\'; 
ptccredit=window.open(url,\'ptccredit\',\'toolbar=no,location=no,scrollbars=no,resizable=no,width='.popup_width.',height='.popup_height.'\'); 
setTimeout( "window.location.replace( \''.$_SERVER[PHP_SELF].'?blur=1&startpos='.$_GET[startpos].add_session().'\' );", 2000 ); 
} 
function reloadpage(waittime){ 
setTimeout( "window.location.replace( \''.$_SERVER[PHP_SELF].'?blur=1&startpos='.$_GET[startpos].add_session().'\' );", waittime*900 ); 
} 
</script> 
';
$reloadpage=1;
}

        if ($_SESSION['lastptc'])
            $clicklist="ptcid!='$_SESSION[lastptc]' and ";


            $getclicks
                =@mysql_query('select id from ' . mysql_prefix . 'paid_clicks where username="'.$_SESSION['username'].'"');

            while ($setclicks=@mysql_fetch_row($getclicks))
             $clicklist=$clicklist . 'ptcid!="'.$setclicks[0].'" and ';    

        $getrow
            =@mysql_query('select * from ' . mysql_prefix . 'ptc_ads where category like "'.$get.'" and description!="#PAID-START-PAGE#" and '.$clicklist.' ((run_quantity>clicks  and run_type="clicks") or (run_quantity>views and run_type="views") or run_type="ongoing" or (run_type="date" and run_quantity>='.mysqldate.')) order by vtype,value desc limit '.$_GET[startpos].',5');
        $backpos=$_GET[startpos];

        while ($row=@mysql_fetch_array($getrow))
            {
            $_GET[startpos]++;


                    $width='';

                    $height='';

                    if ($row[img_width])
                        $width="width=$row[img_width]";

                    if ($row[img_height])
                        $height="height=$row[img_height]";

      if ($row[image_url])
                    {
                    echo
                        '<table border=0 cellpadding=0 cellspacing=0 bgcolor=ffffff><tr><td><a href="' . runner_url . '?PA=' . $row[ptcid] . add_session().'" target=_ptc onclick="javascript:reloadpage(' . $row[timer] . ')"><img src=' . runner_url . '?REDIRECT=' . rawurlencode($row[image_url]). '&hash='.md5($row['image_url'].key).' alt="' . $row[alt_text] . '" ' . $width . ' ' . $height . ' border=0></a></td></tr></table>';
                    }
                elseif ($row[text_ad]){
                         echo     
                        '<table $width $height border=0 cellpadding=0 cellspacing=0><tr><td><a href="' . runner_url . '?PA=' . $row[ptcid] . add_session().'" target=_ptc onclick="javascript:reloadpage(' . $row[timer] . ')">' . $row[text_ad] . '</a></td></tr></table>';
                    } 

                else {
                      $row[html]=str_replace("#USERNAME#", $_SESSION[username], $row[html]);
                    $row[html]=stri_replace("<a\r\n",
                                            "<a target=_ptc onClick=\"javascript:creditpop('" . $row[ptcid] . "')\" ",
                                            $row[html]);

                    $row[html]=stri_replace("<form\r\n",
                                            "<form target=_ptc onSubmit=\"javascript:creditpop('" . $row[ptcid] . "')\" ",
                                            $row[html]);
                    $row[html]=stri_replace("<a\n",
                                            "<a target=_ptc onClick=\"javascript:creditpop('" . $row[ptcid] . "')\" ",
                                            $row[html]);
                    $row[html]=stri_replace("<form\n",
                                            "<form target=_ptc onSubmit=\"javascript:creditpop('" . $row[ptcid] . "')\" ",
                                            $row[html]);
                    $row[html]=stri_replace("<a ",
                                            "<a target=_ptc onClick=\"javascript:creditpop('" . $row[ptcid] . "')\" ",
                                            $row[html]);
                    $row[html]=stri_replace("<form ",
                                            "<form target=_ptc onSubmit=\"javascript:creditpop('" . $row[ptcid] . "')\" ",
                                            $row[html]);
                    echo "<table border=0 cellpadding=0 cellspacing=0><tr><td>$row[html]</td></tr></table>";
                    }

                $mdgroup="#" . substr(md5($row[category]), 0, 8). "#";
                $typemsg=$points;
                $amount=$row[value] / 100000;

                if ($row[vtype] == 'cash')
                    {
                    $typemsg=$cash;

                    $amount=$amount / $factor;
                    }

                echo $message . " " . $amount . " " . $typemsg . "<br><br>";
                @mysql_query ("update " . mysql_prefix . "ptc_ads set views=views+1 where ptcid='$row[ptcid]'");
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

                echo '<a href='.$_SERVER[PHP_SELF].'?startpos='.$backpos.'>'.$back.'</a>';
                }

            if ($_GET[startpos] / 5 == intval($_GET[startpos] / 5))
                {
                echo ' <a href='.$_SERVER[PHP_SELF].'?startpos='.$_GET[startpos].'>'.$forward.'</a>';
                }

            echo "<br><br><b>$page</b><br> ";
            }
if ($_GET[blur]){
echo '<script>setTimeout("window.blur();",1000);</script>';
}
return 1;
?>

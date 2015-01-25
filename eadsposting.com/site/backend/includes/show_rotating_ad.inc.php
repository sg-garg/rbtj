<?php
if (!defined('version'))
{exit;} 

list($get,$js,$return)=get_args('||',$args);

$get=str_replace('%', '', $get);
$ad='';
if($get)
    $get='category="' . $get. '" and ';


$row=mysql_fetch_array(mysql_query('select * from ' . mysql_prefix . 'rotating_ads where ' . $get . ' runad>0 order by runad limit 1'));
$setviews='views+1';
$setrunad=mysqldate;

if($row['run_type'] == 'views' and $row['run_quantity'] <= $row['views'] - 1)
{
    $setviews='run_quantity';
    $setrunad=0;
}

mysql_query('update LOW_PRIORITY ' . mysql_prefix . 'rotating_ads set views=' . $setviews . ',runad="' . $setrunad . '",time=NOW() where bannerid="' . $row['bannerid'] . '"');

$width='';
$height='';

if ($row['img_width'])
    $width='width="'.$row['img_width'].'"';

if ($row['img_height'])
    $height='height="'.$row['img_height'].'"';

$row['site_url'] = str_replace("#USERNAME#", $_SESSION['username'], $row['site_url']);

if ($row['image_url'])
{           
    $ad.='<a href="' . htmlspecialchars( runner_url . '?BA=' . $row['bannerid'] . '&hash='.md5($row['site_url'].key).'&url=' . rawurlencode(
    $row['site_url'])) . '" target="_blank"><img src="' . htmlspecialchars(runner_url . '?REDIRECT=' . rawurlencode($row['image_url']). '&hash='.md5($row['image_url'].key)).'" alt="' . $row['alt_text'] . '" ' . $width . ' ' . $height . ' border="0"></a><br>';
}
elseif ($row['text_ad'])
{
    $ad.= '<a href="' . htmlspecialchars(runner_url . '?BA=' . $row['bannerid'] .'&hash='.md5($row['site_url'].key).'&url='. rawurlencode($row['site_url'])). '" target="_blank">' . $row['text_ad'] . '</a><br>';                                                      
                    }
if ($row['html'])
{
    $row['html']=str_replace("#USERNAME#", $_SESSION['username'], $row['html']);
    $row['html']=stri_replace('<a' . "\r\n", '<a target=_blank ', $row['html']);
    $row['html']=stri_replace('<form' . "\r\n",'<form target=_blank ', $row['html']);
    $row['html']=stri_replace('<a' . "\n", '<a target=_blank ', $row['html']);
    $row['html']=stri_replace('<form' . "\n",'<form target=_blank ', $row['html']);
    $row['html']=stri_replace('<a ', '<a target=_blank ', $row['html']);
    $row['html']=stri_replace('<form ', '<form target=_blank ', $row['html']);

    if ($js)
        $row['html']=str_replace("'", "\"", $row['html']);

    $ad.='<table border="0" cellpadding="0" cellspacing="0"><tr><td>'.$row['html'].'</td></tr></table>';
}
if ($return)
    return($ad);
else
    echo $ad;

$mdgroup="#" . substr(md5($row['category']), 0, 8). "#";

if (($row['popupurl'] and !strpos('.'.$_SESSION['popupviewed'],$mdgroup) && !$js) || ($row['popupurl'] && in_array($row[popuptype],array('iframe','redirect'))))
{
    $row['popupurl']=str_replace("#USERNAME#", $_SESSION['username'], $row['popupurl']);
    $width='';
    $height='';

    if ($row['popuptype']=='redirect')
    {
        echo '<head>
                <noscript>
                <META HTTP-EQUIV="REFRESH" CONTENT="2;URL=' . runner_url .'?REDIRECT='.rawurlencode($row['popupurl']).'&hash='.md5($row['popupurl'].key).'"> 
                </noscript>
                ';
    ?>

        <script language="JavaScript" type="text/javascript">
        <!--
            var sURL = '<?php echo runner_url.'?REDIRECT='.rawurlencode($row[popupurl]).'&hash='.md5($row['popupurl'].key);?>';
            setTimeout( "refresh()", 1000 );

            function refresh()
            {
                window.location.href = sURL;
            }
        //-->
        </script>

        <script language="JavaScript1.1" type="text/javascript">
        <!--
        function refresh()
        {
            window.location.replace( sURL );
        }
        //-->
        </script>

        </head>
        <?php
	    return 1;
    }

    if ($row['popupwidth'])
        $width='width="'.$row['popupwidth'].'",';

    if ($row['popupheight'])
        $height='height="'.$row['popupheight'].'",';

    if ($row['popuptype'] == "popunder")
          $popunder='i'.mysqldate . ".blur() window.focus()\n";

    if ($row['popuptype']!='iframe')
    {
    ?>
    
                    <SCRIPT language = 'JavaScript'  type="text/javascript">
                        <!-- 
                    <?php echo 'i'.mysqldate;?>=window.open("<?php echo runner_url.'?REDIRECT='.rawurlencode($row['popupurl']).'&hash='.md5($row['popupurl'].key);?>","<?php echo 'i'.mysqldate;?>","<?php echo $width;?><?php echo $height;?>left=0,top=0,toolbars=0, scrollbars=1, location=0, statusbars=0, menubars=0, resizable=0"); 
                    <?php echo $popunder;?> 
                    //--> 
                    </SCRIPT>
    
                    <?php
                        $_SESSION['popupviewed'] = $popupviews . $mdgroup;
    }
    else 
    {
        echo '<iframe src='.runner_url.'?REDIRECT='.rawurlencode($row['popupurl']).'&hash='.md5($row['popupurl'].key).' space=0 vspace=0 '.$width.' '.$height.' marginwidth=0 marginheight=0 frameborder=0 scrolling=no></iframe>';
    }


}
return 1;
?>

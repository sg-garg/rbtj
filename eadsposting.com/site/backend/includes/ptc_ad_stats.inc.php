<?php
if (!defined('version')){
exit;}

if(allow_ad_removal == '1')
{
    echo "<form method='POST'>";
    $confirmcode = substr(md5($_SESSION['username'].unixtime."5"), 0,4);
    echo "<input type='hidden' value='$confirmcode' name='confirmation_delete'>";
    echo "<input type='hidden' value='delete_ad' name='user_form'>";
    echo "<input type='hidden' value='ptc' name='adtype'>";
    echo "<input type='hidden' value='1' name='userform'>";
    $showdelete = FALSE;
}

$results = @mysql_query("select * from " . mysql_prefix . "ptc_ads where id='$_SESSION[username]' order by description");

while ($row=@mysql_fetch_array($results))
{
    if ($row['run_type'] == 'date')
    {
        $row['run_quantity']= mytimeread(substr($row['run_quantity'],0,4).'-'.substr($row['run_quantity'],4,2).'-'.substr($row['run_quantity'],6,2).' '.substr($row['run_quantity'],8,2).':'.substr($row['run_quantity'],10,2).':'.substr($row['run_quantity'],12,2));
    }
    else
    {
        $row['run_quantity']=number_format($row['run_quantity'], 0);
    }

    $row['views']=number_format($row['views'], 0);
    $row['clicks']=number_format($row['clicks'], 0);

    if ($row['views'])
    {
        $ctr=number_format($row['clicks'] / $row['views'], 3). " to 1";
    }

    if ($row['run_quantity'] == 0)
    {
        $row['run_quantity']='...';
    }

    if ($row['run_type'] == 'clicks')
    {
        $run_type=$C;
    }

    if ($row['run_type'] == 'views')
    {
        $run_type=$V;
    }

    echo
        $L . '<a href=' . runner_url . '?REDIRECT=' . rawurlencode(
        $row[site_url]). '&hash='.md5($row['site_url'].key).' target=_blank>' . $row[description] . '</a>' . $M . $row[views] . $M . $row[clicks] . $M . $ctr . $M . $row[run_quantity] . ' ' . $run_type . $M . mytimeread(
        $row[time]). $R;

    if ($S == 'show')
    {
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

            if(allow_ad_removal == '1')
            {
                $showdelete = TRUE;
                $colcount = 5;
                $delptc = '<td align="center">
                            <input type="checkbox" name="ptcad[]" value="'.$row['ptcid'].'"> delete
                            </td>';
            }else{
                $colcount = 6;
                $delptc = '';
            }

            echo
                '<tr><td colspan="'.$colcount.'"><center><a href=' . runner_url . '?REDIRECT=' . rawurlencode(
                $row[site_url]). '&hash='.md5($row['site_url'].key).' target=_blank><img src=' . runner_url . '?REDIRECT=' . rawurlencode($row[image_url]). '&hash='.md5($row['image_url'].key).' alt="' . $alt_text . '" ' . $width . ' ' . $height . ' border=0></a></center></td></td>
                '.$delptc.'</tr>';
        }
        else
        {
            echo "<tr><td colspan='6'><center><table border=0 cellpadding=0 cellspacing=0><tr><td>$row[html]</td></tr></table></center></td>$delptc</tr>";
        }

    }
}

if(allow_ad_removal == '1')
{
    form_errors("confirmation_user_ptc","Wrong confirmation code","N/A","<tr><td colspan='6' bgcolor='red' align='center'>","</td></tr>");

    if($showdelete)
    {
        echo '<tr>
                <td align="right" colspan="6">
                    Confirmation code:  <b>'. $confirmcode .'</b> -&gt;

                    <input type="textbox" style="width:55px" name="confirmation_user"> -&gt;

                    <input class="delete_ad" type="submit" value="Delete selected PTC ads"></form>

                </td>
                </tr>';
    }else{
        echo "</form>";
    }
}


return 1;
?>

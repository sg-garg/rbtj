<?php
if (!defined('version')){
exit;}

    if(allow_ad_removal == '1')
    {
        echo "<form method='POST'>";
        $confirmcode = substr(md5($_SESSION['username'].unixtime."4"), 0,4);
        echo "<input type='hidden' value='$confirmcode' name='confirmation_delete'>";
        echo "<input type='hidden' value='delete_ad' name='user_form'>";
        echo "<input type='hidden' value='popup' name='adtype'>";
        echo "<input type='hidden' value='1' name='userform'>";
        $showdelete = FALSE;
    }

        $results = @mysql_query("select * from " . mysql_prefix . "rotating_ads where id='$_SESSION[username]' and popupurl!='' and popuptype='$T' order by description");

        while ($row=@mysql_fetch_array($results))
        {
            if ($row[runad] > 1)
                {
                $row[time]=$row[runad];
                }

            if ($row[time] == 0)
                {
                $row[time]='00000000000000';
                }


            if ($row[run_type] == 'date')
                {
                $row[run_quantity]=mytimeread(substr($row['run_quantity'],0,4).'-'.substr($row['run_quantity'],4,2).'-'.substr($row['run_quantity'],6,2).' '.substr($row['run_quantity'],8,2).':'.substr($row['run_quantity'],10,2).':'.substr($row['run_quantity'],12,2));
                }
            else
                {
                $row[run_quantity]=number_format($row[run_quantity], 0);
                }

            $row[views]=number_format($row[views], 0);

            if ($row[run_quantity] == 0)
                {
                $row[run_quantity]='...';
                }

            if(allow_ad_removal == '1')
            {
                $deletepop = '(<input type="checkbox" name="popup[]" value="'.$row[bannerid].'"> delete)';
            }else{
                $deletepop = '';
            }

            echo
                $L . '<a href=' . runner_url . '?REDIRECT=' . rawurlencode(
                $row[popupurl]). '&hash='.md5($row['popupurl'].key).' target=_blank>' . $row[description] . '</a>' . $M . $row[views] . $M . $row[run_quantity] . $M . mytimeread(substr($row['time'],0,4).'-'.substr($row['time'],4,2).'-'.substr($row['time'],6,2).' '.substr($row['time'],8,2).':'.substr($row['time'],10,2).':'.substr($row['time'],12,2)). " ".$deletepop . $R;

                $showdelete = TRUE;
        }

        if(allow_ad_removal == '1')
        {

            form_errors("confirmation_user_popup","Wrong confirmation code","N/A","<tr><td colspan='4' bgcolor='red' align='center'>","</td></tr>");

            if($showdelete)
            {
                echo '<tr>
                        <td align="right" colspan=4>
                            Confirmation code:  <b>'. $confirmcode .'</b> -&gt;

                            <input type="textbox" style="width:55px" name="confirmation_user"> -&gt;

                            <input class="delete_ad" type="submit" value="Delete selected pop style ads"></form>

                        </td>
                      </tr>';
            }else{
                echo "</form>";
            }
        }

return 1;
?>

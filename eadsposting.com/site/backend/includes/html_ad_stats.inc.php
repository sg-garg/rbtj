<?php
if (!defined('version')){
exit;}

    if(allow_ad_removal == '1')
    {
        echo "<form method='POST'>";
        $confirmcode = substr(md5($_SESSION['username'].unixtime), 0,4);
        echo "<input type='hidden' value='$confirmcode' name='confirmation_delete'>";
        echo "<input type='hidden' value='delete_ad' name='user_form'>";
        echo "<input type='hidden' value='html' name='adtype'>";
        echo "<input type='hidden' value='1' name='userform'>";
        $showdelete = FALSE;
    }


        $results = @mysql_query("select * from " . mysql_prefix . "rotating_ads where id='$_SESSION[username]' and site_url='' and image_url='' and html!='' order by description");

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

            echo
                $L . "$row[description]" . $M . "$row[views]" . $M . "$row[run_quantity]" . $M . mytimeread(substr($row['time'],0,4).'-'.substr($row['time'],4,2).'-'.substr($row['time'],6,2).' '.substr($row['time'],8,2).':'.substr($row['time'],10,2).':'.substr($row['time'],12,2)). $R;

            if ($S == 'show')
            {
                echo "<tr>
                        <td colspan=4>
                            <center>
                                <table border=0 cellpadding=0 cellspacing=0>
                                <tr>
                                <td>$row[html]</td>
                                </tr>
                                </table>
                            </center>
                         <br>
                        </td>
                      </tr>";

                if(allow_ad_removal == '1')
                {
                    echo '<tr>
                        <td colspan="4" align="center">
                        <input type="checkbox" name="htmlad[]" value="' .$row[bannerid]. '"> delete above HTML ad
                        </td>
                      </tr>';
                    $showdelete = TRUE;
                }
            }
        }

        if(allow_ad_removal == '1')
        {
            form_errors("confirmation_user_html","Wrong confirmation code","N/A","<tr><td colspan='4' bgcolor='red' align='center'>","</td></tr>");

            if($showdelete)
            {
                echo '<tr>
                        <td align="right" colspan=4>
                            Confirmation code:  <b>'. $confirmcode .'</b> -&gt;

                            <input type="textbox" name="confirmation_user" style="width:55px" value=""> -&gt;

                            <input class="delete_ad" type="submit" value="Delete selected HTML ads"></form>

                        </td>
                      </tr>';
            }else{
                echo "</form>";
            }
        }

return 1;
?>

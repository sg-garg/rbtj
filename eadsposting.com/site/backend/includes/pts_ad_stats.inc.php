<?php
if (!defined('version')){
exit;}

        $results = @mysql_query("select * from " . mysql_prefix . "pts_ads where id='$_SESSION[username]' order by description");

        while ($row=@mysql_fetch_array($results))
            {
            if ($row[run_type] == 'date')
                {
                $row[run_quantity]=mytimeread($row[run_quantity]);
                }
            else
                {
                $row[run_quantity]=number_format($row[run_quantity], 0);
                }

            $row[views]=number_format($row[views], 0);
            $row[signups]=number_format($row[signups], 0);

            if ($row[views])
                {
                $ctr=number_format($row[signups] / $row[views], 3). " to 1";
                }

            if ($row[run_quantity] == 0)
                {
                $row[run_quantity]='...';
                }

            if ($row[run_type] == 'signups')
                {
                $run_type=$C;
                }

            if ($row[run_type] == 'views')
                {
                $run_type=$V;
                }

            echo
                $L . '<a href=' . runner_url . '?REDIRECT=' . rawurlencode(
                $row[site_url]). '&hash='.md5($row['site_url'].key).' target=_blank>' . $row[description] . '</a>' . $M . $row[views] . $M . $row[signups] . $M . $ctr . $M . $row[run_quantity] . ' ' . $run_type . $M . mytimeread(
                $row[time]). $R;

            if ($S == 'show')
                {
                if ($row[image_url])
                    {
                    $width='';

                    $height='';

                    if ($row[img_width])
                        {
                        $width="width=$row[img_width]";
                        }

                    if ($row[img_height])
                        {
                        $height="height=$row[img_height]";
                        }

                    echo
                        '<tr><td colspan=6><center><a href=' . runner_url . '?REDIRECT=' . rawurlencode(
                        $row[site_url]). '&hash='.md5($row['site_url'].key).' target=_blank><img src=' . runner_url . '?REDIRECT=' . rawurlencode($row[image_url]). '&hash='.md5($row['image_url'].key).' alt="' . $alt_text . '" ' . $width . ' ' . $height . ' border=0></a></center></td></tr>';
                    }
                else
                    {
                    echo "<tr><td colspan=6><center><table border=0 cellpadding=0 cellspacing=0><tr><td>$row[html]</td></tr></table></center><br></td></tr>";
                    }
                
                }
            }



return 1;
?>

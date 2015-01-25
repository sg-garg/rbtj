<?
if (!defined('version')){
exit;} 

       $keys
            =@mysql_query('select keyword from ' . mysql_prefix . 'keywords order by keyword');

        $rowct=0;

        while ($row=@mysql_fetch_row($keys))
            {
            echo $l . '<input type=checkbox name="keyword[' . $rowct . ']" value="' . safeentities($row[0]) . '" ';

            interests($row[0], "checked");
            echo '> ' . $row[0] . $m;
            $row=@mysql_fetch_row($keys);

            if ($row[0])
                {
                $rowct++;

                echo '<input type=checkbox name="keyword[' . $rowct . ']" value="' . safeentities($row[0]) . '" ';
                interests($row[0], "checked");
                echo '> ' . $row[0] . $m;
                $row=@mysql_fetch_row($keys);

                if ($row[0])
                    {
                    $rowct++;

                    echo '<input type=checkbox name="keyword[' . $rowct . ']" value="' . safeentities($row[0]) . '" ';
                    interests($row[0], "checked");
                    echo '> ' . $row[0] . $r;
                    }
                }

            $rowct++;
            }


return 1;
?>

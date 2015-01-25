<?php
if (!defined('version')){exit;} 

$keys = @mysql_query('SELECT keyword, preselected FROM ' . mysql_prefix . 'keywords ORDER BY keyword');

$rowct = 0;

while ($row=@mysql_fetch_row($keys))
{
    echo $l . '<input type=checkbox name="keyword[' . $rowct . ']" value="' . safeentities($row[0]) . '" ';

    interestsform($row[0], "checked", $row[1]);
    echo '> ' . $row[0] . $m;
    $row=@mysql_fetch_row($keys);

    if ($row[0])
    {
        $rowct++;

        echo '<input type=checkbox name="keyword[' . $rowct . ']" value="' . safeentities($row[0]) . '" ';
        interestsform($row[0], "checked", $row[1]);
        echo '> ' . $row[0] . $m;

        $row=@mysql_fetch_row($keys);

        if ($row[0])
        {
            $rowct++;

            echo '<input type=checkbox name="keyword[' . $rowct . ']" value="' . safeentities($row[0]) . '" ';
            interestsform($row[0], "checked", $row[1]);
            echo '> ' . $row[0] . $r;
        }
    }

    $rowct++;
}


return 1;
?>

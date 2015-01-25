<?
if (!defined('version')){
exit;} 

        $states
            =@mysql_query('select state from ' . mysql_prefix . 'states order by state');

        while ($row=@mysql_fetch_row($states))
            {
            echo '<option value="' . safeentities($row[0]) . '"';

            if (strtolower(user('state','return')) == strtolower($row[0]))
                {
                echo ' selected';
                }

            echo '>' . $row[0];
            }


return 1;
?>

<?
if (!defined('version')){
exit;} 

        $countries
            =@mysql_query('select country from ' . mysql_prefix . 'countries order by country');

        while ($row=@mysql_fetch_row($countries))
            {
            echo '<option value="' . safeentities($row[0]) . '"';

            if (strtolower(user('country','return')) == strtolower($row[0]))
                {
                echo ' selected';
                }

            echo '>' . $row[0];
            }


return 1;
?>

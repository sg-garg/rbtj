<?
if (!defined('version')){
exit;} 

        $paytypes
            =@mysql_query('select type from ' . mysql_prefix . 'payment_types order by type');

        while ($row=@mysql_fetch_row($paytypes))
            {
            echo '<option value="' . safeentities($row[0]) . '"';

            if (strtolower($_POST[userform][pay_type]) == strtolower($row[0]))
                {
                echo ' selected';
                }

            echo '>' . $row[0];
            }


return 1;
?>

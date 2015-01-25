<?
if (!defined('version')){
exit;} 

$a=get_args(', ',$args);

$paytypes
            =@mysql_query('select type from ' . mysql_prefix . 'payment_types order by type');

        while ($row=@mysql_fetch_row($paytypes))
            {
           echo $comma.$row[0];
           $comma=$a[0]; 

            }


return 1;
?>

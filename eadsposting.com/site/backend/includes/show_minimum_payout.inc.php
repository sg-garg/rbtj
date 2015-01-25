<?
if (!defined('version')){
exit;} 

$a=get_args('2|.|,',$args);

echo number_format(payout,$a[0],$a[1],$a[2]);

return 1;
?>

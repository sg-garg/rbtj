<?
if (!defined('version')){
exit;} 

$a=get_args('1|2|.|,',$args);

echo number_format(ipntopoints*$a[0],$a[1],$a[2],$a[3]);

return 1;
?>

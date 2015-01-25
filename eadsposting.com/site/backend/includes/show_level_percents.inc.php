<?
if (!defined('version')){
exit;}

$a=get_args('Level | = |%|, ',$args);

$count=0; 
if (sales_comm!=''){
$count=count(explode(',',sales_comm));
if ($count)
$level=format_levels(sales_comm,$a[0],$a[1],$a[2],$a[3]);
}

if (count(explode(',',cashclicks))>=$count && cashclicks!=''){
$count=count(explode(',',cashclicks));
if ($count)
$level=format_levels(cashclicks,$a[0],$a[1],$a[2],$a[3]);
}

if (count(explode(',',pointclicks))>=$count && pointclicks!=''){
$count=count(explode(',',pointclicks));
if ($count)
$level=format_levels(pointclicks,$a[0],$a[1],$a[2],$a[3]);
}

echo $level;
return 1;

function format_levels($value,$ltag,$space,$percent,$comma)
{ 
$array=explode(',',$value);
for ($i=0;$i<count($array);$i++){
if ($i==count($array)-1)
$comma='';
$l=$i+1;
$level.=$ltag.$l.$space.$array[$i].$percent.$comma;
}

return $level;
}
?>

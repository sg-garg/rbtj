<?
if (!defined('version')){
exit;}
$count=0; 
if (sales_comm!='')
$count=count(explode(',',sales_comm));
if (count(explode(',',cashclicks))>$count && cashclicks!='')
$count=count(explode(',',cashclicks));
if (count(explode(',',pointclicks))>$count && pointclicks!='')
$count=count(explode(',',pointclicks));
echo $count;
return 1;
?>

<?
if (!defined('version')){
exit;}

	$levelcount=substr_count(pointclicks,',')+1;

        if (substr_count(cashclicks,',')+1>$levelcount)
                $levelcount=substr_count(cashclicks,',')+1;

        if (substr_count(sales_comm,',')+1>$levelcount)
                $levelcount=substr_count(sales_comm,',')+1;


echo '<table border=0 cellpadding=0 cellspacing=0><form><tr><td>';     

for ($idx=0;$idx<$levelcount;$idx++){
$level=$idx+1;

 if (user('account_type','return')!='canceled'){
	if ($idx==0){
	$dowlinegrab=@mysql_query('select username,referrer,account_type from '.mysql_prefix.'users where referrer="'.$_SESSION['username'].'" and upline="'.$_SESSION['username'].'" and account_type!="canceled" order by username');
$dowlinegrab2=@mysql_query('select '.mysql_prefix.'levels.username,'.mysql_prefix.'users.account_type from '.mysql_prefix.'levels,'.mysql_prefix.'users where '.mysql_prefix.'levels.upline="'.$_SESSION['username'].'" and '.mysql_prefix.'levels.username='.mysql_prefix.'users.username and referrer!="'.$_SESSION['username'].'" and account_type!="canceled" and level=0 order by '.mysql_prefix.'levels.username');
}
	else { 
	$dowlinegrab=@mysql_query('select username from '.mysql_prefix.'levels where upline="'.$_SESSION['username'].'" and level='.$idx.' order by username');
  }}

$rowtotal=0+@mysql_num_rows($dowlinegrab);

if ($idx==0)
$rowtotal+=@mysql_num_rows($dowlinegrab2);


$totalct+=$rowtotal;

echo '<select><option>'.$l.' '.$level.' ('.$rowtotal.')';
while ($row=@mysql_fetch_array($dowlinegrab)){
echo '<option>'.$row['username'].' ';

	if ($row['referrer']==$_SESSION['username'])
	echo '*';
        if ($row['account_type']=='advertiser')
        echo '$';

}
if ($idx==0){
while ($row=@mysql_fetch_array($dowlinegrab2)){
  echo '<option>'.$row['username'].' ';
  if ($row['account_type']=='advertiser')
        echo '$';
}
}
echo '</select><br>';}
echo '</td></tr></form></table>';
$_SESSION['levelcache']['all']=$totalct;
return 1;
?>

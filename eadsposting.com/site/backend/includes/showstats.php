<?php
if (!defined('version')){exit;}

if(!empty($_GET['password']))
{
    $password = $_GET['password'];
}
elseif(!empty($_POST['password']))
{
    $password = $_POST['password'];
}

if (!empty($_GET['username']))
{
    $_POST['password']=$password;
    $_COOKIE['autousername']=$_GET['username'];
    $_COOKIE['autopassword']=md5(password($password).key);
}

login('norecord');
header('Content-Type: text/plain');
$pointclicks = explode(',',pointclicks);
$cashclicks = explode(',',cashclicks);
$salescomm = explode(',',sales_comm);
$levelcount=count($pointclicks);
if (count($cashclicks)>$levelcount)
$levelcount=count($cashclicks);
if (count($salescomm)>$levelcount)
$levelcount=count($salescomm);
if ($_GET['showstats']!=2){
    echo 'EARNINGS_SUMMARY $'.number_format(cash_earnings('return'),5,'.','').', $'.number_format(dlcash_earnings('return'),5,'.','').', $'.number_format(cash_totals('all','return'),5,'.','').', '.number_format(points_earnings('return'),5,'.','').', '.number_format(dlpoints_earnings('return'),5,'.','').', '.number_format(points_totals('all','return'),5,'.',''); 
echo '
REFERRAL_COUNT ';
echo $levelcount;
for ($i=1;$i<=$levelcount;$i++){
    echo ', ';
    level_total($i);}
}
if ($_GET['showstats']==2){
    for ($i=0;$i<$levelcount;$i++)
    {
        $results=@mysql_query('select '.mysql_prefix.'levels.username,'.mysql_prefix.'users.upline from '.mysql_prefix.'levels left join '.mysql_prefix.'users on '.mysql_prefix.'users.username='.mysql_prefix.'levels.username where level='.$i.' and '.mysql_prefix.'levels.upline="'.$_SESSION['username'].'"');
        echo 'REFERRAL ID '.($i+1).', '.@mysql_num_rows($results);
        while ($row=@mysql_fetch_row($results))
        {
            echo ', '.$row[0].':'.$row[1];
        }
        echo "\r\n";
    }
}
exit; 
?>

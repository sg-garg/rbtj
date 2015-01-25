<?
include('../setup.php');
$ip=$_SERVER['REMOTE_ADDR'];
if ($_GET['log']){
echo '<h1>Logging String Found: '.$_GET[log].'</h1>';
$codes=explode(" ",$_GET[log]);
echo '<h1>log:'.$_GET[log].'</h1>';
echo '<h1>Post ID:'.$codes[3].'</h1>';
@mysql_query('update log_post set creation_date=now() where post_id="'.$codes[3].'"');

exit;
}
if ($_GET['renew'])
{
list($firstpart)=explode('@',$_GET['renew']);
$rnew=$_GET[renewable];
$totads=$_GET[count];
if ($rnew == 0 && $_GET[count] == 0){
echo "<h1>No Ad found for renewal for user: ".$_GET['renew']."</h1></br>";
$sql1 = "update posting_accounts set message ='No Ad found for renewal By Script' where Username='".$_GET['renew']."'";
@mysql_query($sql1);

}
if ($rnew>$totads)
$min=-9880;
else {
$min=@ceil(2880/$rnew);
if ($min==0)
$min=2880;
}
$next=$min-15;
@mysql_query('update posting_accounts set Renew_Time=now() + interval '.$next.' minute where Username="'.$_GET['renew'].'"');

echo "<h1>Ads found to renew: $rnew<br>Ads Renewed: $_GET[count]<br>Check again: $next minutes</h1>";
exit;
}
//@mysql_query("update posting_accounts set Renew_Time=now() where Username=(select Username from posting_accounts where  info = 'Y' order by Renew_Time limit 1)");
//@mysql_query('LOCK TABLES posting_accounts WRITE');
date_default_timezone_set("Asia/Kolkata");
$script_tz = date_default_timezone_get();
//echo "Today is " . date("Y/m/d h:i:sa"). "TZ:".$script_tz;

//$hour=date('H',unixtime);
$hour = date("H");
$odd = $hour%3;
$cincinnatidiscou = [3,6,9,12,15,18,20,21,23];
$eastcost = [1,4,7,11,13,17,19,22];
$austin = [0,2,5,8,10,14,16];
if(in_array($hour, $cincinnatidiscou)){
	$user_id= 'cincinnatidiscou';
}elseif(in_array($hour, $eastcost)){
	$user_id= 'eastcoastclearan';
}elseif(in_array($hour, $austin)){
	$user_id= 'localdiscount';
}else{
	$user_id= 'cincinnatidiscou';
}

        if ($hour>17 && $hour<16){
        $rc=15;
		
        }elseif ($hour>8 && $hour<18){
        $rc=30;
        }elseif ($hour>6 && $hour<20){
        $rc=60;
        }else{
        $rc=120;
		}
        //list($account,$password,$info)=@mysql_fetch_row(@mysql_query("select * from posting_accounts where  info = 'Y' order by RAND() limit 1"));
		//list($account,$password,$info)=@mysql_fetch_row(@mysql_query("select * from posting_accounts where info = 'Y' order by Renew_Time desc limit 1"));
		$sql = "select * from posting_accounts where info = 'Y' and user_id='$user_id' and last_used < now() - interval '48' hour order by Renew_Time asc limit 1";
		
		list($account,$password,$info)=@mysql_fetch_row(@mysql_query($sql));
		
		//list($account,$password,$info)=@mysql_fetch_row(@mysql_query("select * from posting_accounts where info = 'Y' and Renew_Time<now() - interval '.$rc.' minute order by Renew_Time limit 1"));
		//list($account,$password,$info)=@mysql_fetch_row(@mysql_query("select * from posting_accounts where info = 'Y' and Renew_Time<now() - interval '.$rc.' minute order by Renew_Time limit 1"));
if ($account){
 @mysql_query('update posting_accounts set Renew_Time=now() where Username="'.$account.'"');
 echo 'renew|'.$account.'|'.$password.'|'.$rc.'|'.$info."\n";
exit;
}
echo 'Hour: '.$hour.' Interval: '.$rc.'min. Nothing to do<br>'.$sql ;
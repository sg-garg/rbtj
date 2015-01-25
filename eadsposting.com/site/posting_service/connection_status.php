<? 
include('../frontend/setup.php');
echo $_SERVER['REMOTE_ADDR'];
if ($_GET['wan'] && $_GET['proxy']){
list($checkloc)=@mysql_fetch_row(@mysql_query('select ip from LDF_Proxy_Services.locations where ip="'.$_GET['wan'].'"'));

if (!$checkloc){

$tags=get_meta_tags('http://www.geobytes.com/IpLocator.htm?GetLocation&template=php3.txt&IpAddress='.$_GET['wan']);
if ($tags['known']=='true')
{
$location[4]=strtoupper($tags['country']);
$location[5]=strtoupper($tags['region']);
$location[6]=strtoupper($tags['city']);
}
else
{
$location=explode(';',file_get_contents('http://api.ipinfodb.com/v3/ip-city/?key=e6d1bbc7066f0763d1f538a69023feea63b5787546491e01e5c26d6f203fc564&ip='.$_GET['wan']));
}
$query='replace into LDF_Proxy_Services.locations set ip="'.$_GET['wan'].'",country="'.$location[4].'",state="'.$location[5].'",city="'.$location[6].'",zip="'.$location[7].'"';
mysql_query($query);
}

$query='replace into LDF_Proxy_Services.live_proxies set wan_ip="'.$_GET['wan'].'",proxy_ip="'.$_GET['proxy'].'",ms="'.$_GET['ms'].'"';
mysql_query($query);
}

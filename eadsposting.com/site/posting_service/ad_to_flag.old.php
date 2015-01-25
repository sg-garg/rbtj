<? 
exit;
include('../frontend/setup.php');

@mysql_query('insert into LDF_Flagging_Data.IP_Log set IP="'.ipaddr.'"');
if ($_GET['flagged'])
	{
	//list($url)=@mysql_fetch_row(@mysql_query('select URL from LDF_Flagging_Data.URLs where Flagged<6 order by timestamp desc limit 1'));
    @mysql_query('update LDF_Flagging_Data.URLs set Flagged=127 where URL="'.addslashes($_SESSION['Flagged_URL']).'"');
	$_SESSION['Flagged_URL']='';
	exit;
	}
if ($_GET['check_if_flagged']){
	?>
<html>
<head>
<?php
print("<meta http-equiv=refresh content='0;url=".$_SESSION['Flagged_URL']."'>");
?>
</head>
<body>
	<?
	exit;
	}
	while (!strpos($contents,'spam/overpost')){
		
		list($url)=@mysql_fetch_row(@mysql_query('select URL from LDF_Flagging_Data.URLs where Flagged=0 order by Flagged asc limit 1'));
        
		@mysql_query('update LDF_Flagging_Data.URLs set Flagged=3 where URL="'.addslashes($url).'"');
	    
		if (!$url)
		break;
	
        $contents=file_get_contents($url);
	}
	if ($url){
		if (strpos($contents,'buybedshere') || strpos($contents,'trk.php') || strpos($contents,'beds4cash'))
		$status=2;
		else 
		$status=1;
		
		@mysql_query('update into LDF_Flagging_Data.URLs set Flagged=1,Status='.$status.' where URL="'.addslashes($url).'"');
	}
if (!$url){
	if ($_SESSION['last_cl_check']<unixtime-60){
	$page=file('http://phoenix.craigslist.org/fud');
	$_SESSION['last_cl_check']=unixtime;
	
    for ($i;$i<count($page);$i++)
	{
	if (trim($page[$i])=='<span class="itemdate"></span>')
	{	
		$i++;
		list($junk,$url,$junk)=explode('"',$page[$i]);
		@mysql_query('insert into LDF_Flagging_Data.URLs set URL="'.addslashes($url).'"');
	}
	}
	}
	    
		list($url,$status)=@mysql_fetch_row(@mysql_query('select URL,Status from LDF_Flagging_Data.URLs order by Flagged asc,Timestamp desc limit 1'));
		if ($url)
		@mysql_query('update LDF_Flagging_Data.URLs set Flagged=Flagged+1 where URL="'.addslashes($url).'"');
		else
		{
		echo 'No URLs Found';
		exit;
		}
}
		$_SESSION['Flagged_URL']=$url;
		if (!$status){
		$contents=file_get_contents($url);
		if (strpos($contents,'buybedshere') || strpos($contents,'trk.php') || strpos($contents,'beds4cash') )
		$status=2;
		else
		$status=1;
		
		}
		$url_breakdown=parse_url($url);
		if ($status==2){
		 	$url='';
			@mysql_query('update LDF_Flagging_Data.URLs set Flagged=126 where URL="'.addslashes($url).'"');
		}
		else
		{  
		$url='http://'.$url_breakdown['host'].'/search/fud?query='.basename($url,'.html').'&srchType=A&minAsk=&maxAsk=';
		if ($_GET['lynx'])
		{  echo $url;
			exit;
		}
		 //  $url='http://'.$url_breakdown['host'].'/search/fud?query='.basename($url,'.html').'&srchType=A&minAsk=&maxAsk='; 
		}
?>
<html>
<head>
<?php
print("<meta http-equiv=refresh content='0;url=$url'>");
?>
</head>
<body>

</body>
</html>

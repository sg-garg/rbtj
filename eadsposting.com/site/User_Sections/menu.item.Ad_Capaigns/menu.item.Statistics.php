<?php
include("../../setup.php");
login();
//login('deny','Poster');
if (user('account_type','return')=='Poster'){
 	if ($_GET['client_id']) {
 	$client_id=$_GET['client_id'];
	 $_SESSION['client_id']=$client_id;	
	}
 	else {
 	$client_id=$_SESSION['client_id'];
	}
} elseif(user('account_type','return')=='Client Sales'){
	$client_id = user('owner_id','return');
	
} else {
  $client_id=$_SESSION['username'];
}

include(pages_dir."header.php");
?>
<style>
.leftfloat{
	text-align:left;
	padding:5px;
	}
div.pagination a {
	padding: 2px 5px 2px 5px;
	margin: 2px;
	border: 1px solid #AAAADD;
	
	text-decoration: none; /* no underline */
	color: #000099;
}
div.pagination a:hover, div.pagination a:active {
	border: 1px solid #000099;

	color: #000;
}
div.pagination span.current {
	padding: 2px 5px 2px 5px;
	margin: 2px;
		border: 1px solid #000099;
		
		font-weight: bold;
		background-color: #000099;
		color: #FFF;
	}
	div.pagination span.disabled {
		padding: 2px 5px 2px 5px;
		margin: 2px;
		border: 1px solid #EEE;
	
		color: #DDD;
	}

</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr> 
<!--td align="left" valign="top" class="account_menu"--> 
<?php 
 /*if (user('account_type','return')=='Poster') {
  include(pages_dir."poster_menu.php");
  } else {
     include(pages_dir."account_menu.php");   
  }*/
?>
<?php
//$rec_limit = 100;
$adid = $_GET['adid'];

$myQuery = "select count(*) mypost  from log_post where user_id ='".$client_id."' and creation_date>now()-interval 1 day";
if ($adid>0)
$myQuery = "select count(*) mypost  from log_post where user_id ='".$client_id."' and creation_date>now()-interval 1 day and ad_id=$adid";

$myTotal = @mysql_query($myQuery);
$row = @mysql_fetch_row($myTotal);
$myTotalCount = $row[0];

 /* Get total number of records */
$sql="SELECT count(*) FROM log_post where user_id ='".$client_id."'";
if ($adid>0)
$sql = "SELECT count(*) FROM log_post where user_id ='".$client_id."' and  ad_id=".$_GET['adid'];

$retval = @mysql_query($sql);
$row = @mysql_fetch_row($retval);
$rec_count = $row[0];
  
	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	$targetpage = $_PHP_SELF;  	//your file name  (the name of this file)
       $total_pages = $rec_count;
	//how many items to show per page
	$limit = 100; 				
       $page = $_GET['page'];
      if($page) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
		$start = 0;								//if no page var is given, set start to 0
	
	/* Get data. */
	//$sql = "SELECT * FROM $tbl_name ORDER BY lname ASC LIMIT $start, $limit ";
	if ($adid>0)
       $add_adid=" and ad_id=$adid";
      $sql = "SELECT ad_id,post_id, posting_account,confirmation_url,date_format(creation_date,'%m-%d-%Y %H:%i') creation, posted_by, date_format(first_creation_date,'%m-%d-%Y %H:%i') first_creation_date,DATEDIFF(creation_date,first_creation_date) renewtime  FROM log_post where user_id ='".$client_id."' $add_adid order by creation_date desc LIMIT $start, $limit";
       $retval = @mysql_query($sql);
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;						//last page minus 1
	
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div class=\"pagination\">";
		//previous button
		if ($page > 1) 
			$pagination.= "<a href=\"$targetpage?adid=$adid&page=$prev\">previous</a>";
		else
			$pagination.= "<span class=\"disabled\">previous</span>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a href=\"$targetpage?adid=$adid&page=$counter\">$counter</a>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?adid=$adid&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage?adid=$adid&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage?adid=$adid&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"$targetpage?adid=$adid&page=1\"> 1 </a>";
				$pagination.= "<a href=\"$targetpage?adid=$adid&page=2\"> 2 </a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?adid=$adid&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage?adid=$adid&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage?adid=$adid&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"$targetpage?adid=$adid&page=1\"> 1 </a>";
				$pagination.= "<a href=\"$targetpage?adid=$adid&page=2\"> 2 </a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?adid=$adid&page=$counter\">$counter</a>";					
				}
			}

		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<a href=\"$targetpage?adid=$adid&page=$next\">next</a>";
		else
			$pagination.= "<span class=\"disabled\">next</span>";
		$pagination.= "</div>\n";		
	
	}

?>

<!--/td -->
<td align="left" valign="top">
<table width=100% align="left" cellspacing="0" cellpadding="4">
<tr><td><b>&nbsp;&nbsp;Postings in the last 24 hours:&nbsp;&nbsp;<?php echo $myTotalCount;?></td>&nbsp;<td></td><td>&nbsp;</td></tr>
<tr><td><?php echo '<div  class=leftfloat>'.$pagination.'</div>' ;?></td></tr></table>
<!--form method="POST" -->
<table border=1  width=100% align="center" cellspacing="0" cellpadding="4" style="border-collapse:collapse;">
<tr><th class="displaytag_color" nowrap>Ad Id</th><th class="displaytag_color">Post Id</th><th class="displaytag_color">Confirmation URL</th><th class="displaytag_color" nowrap>Posted By</th><th class="displaytag_color" nowrap>Account</th><!--th class="displaytag_color" nowrap>Renew Date</th--><th class="displaytag_color" nowrap>Creation Date</th><!--th class="displaytag_color" nowrap>Is renewed?</th--></tr>
<?php 
$count =1;
while($row = @mysql_fetch_array($retval)){ 
    $renewcol ="";
    if($row['renewtime']>0){
     $renewcol ="<input type=checkbox name=renew disabled=true checked>";
    }else {
      $renewcol ="<input type=checkbox name=renew disabled=true>";
    }  
    if($count%2==0){
    echo "<tr class=\"nonshaded\">";
    }else {
      echo "<tr class=\"shaded\">";
    }
       echo "<td>$row[ad_id]</td><td width=20%>{$row['post_id']}</td> ".
         "<td width=70% nowrap><a href=".htmlentities($row['confirmation_url'],ENT_QUOTES|ENT_IGNORE)." target=_blank>".htmlentities($row['confirmation_url'],ENT_QUOTES|ENT_IGNORE)."</a></td><td nowrap>{$row['posted_by']}</td><td nowrap>$row[posting_account]</td><!--td nowrap>{$row['creation']}</td--><td nowrap>{$row['first_creation_date']}</td><!--td nowrap>".$renewcol."</td--></tr>";
  $count++;
} 
$navigationRow = "<tr class=\"nonshaded\"><td colspan=8><a href=\"#Top\"> Back to Top… </a> </td></tr>";
//echo '<div class=leftfloat>'.$pagination.'</div>' ;
if($count>40)
 echo $navigationRow;
?>
</table>
<!--/form-->
<br></td>
</tr>
</table>

<?php include(pages_dir."footer.php");?>


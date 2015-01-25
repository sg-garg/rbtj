<?php
include("../../setup.php");
login();
/*if (user('account_type','return')=='Poster') {
  header('Location: /frontend/Poster_Sections/menu.item.Posters/menu.item.Clients.php');
}*/
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
<!--style>
tbody tr:hover {
  background: #6E6E6E;
}
</style-->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr> 
<!--td align="left" valign="top" class="account_menu" --> 
<?php 
 /*if (user('account_type','return')=='Poster') {
  include(pages_dir."poster_menu.php");
  } else {
     include(pages_dir."account_menu.php");   
  }*/
?>
<!--/td -->
<td align="left" valign="top"><p>&nbsp;</p>
<form method="POST" action=savepost.php id="adForm">
<table border=1  width=100% align="center" cellspacing="0" cellpadding="4" style="border-collapse:collapse;">

<?php
$posts = @mysql_query("select ad_id,title,created_by,updated_by,date_format(creation_date,'%m-%d-%Y %H:%i') creation_date,date_format(updated,'%m-%d-%Y %H:%i') updated from dealer_ads where user_id ='".$client_id."' order by creation_date desc");
$totalposts = mysql_num_rows($posts);
if($totalposts) { ?>
<tr> 
<th align=center class="displaytag_color"><b>Id</b></th>
<th align=center class="displaytag_color"><b>Title</b></th>
<th align=center class="displaytag_color" nowrap=nowrap><b>Created By</b></th>
<th align=center class="displaytag_color" nowrap=nowrap><b>Updated By</b></th>
<th align=center class="displaytag_color" nowrap=nowrap><b>Creation Date</b></th>
<th align=center class="displaytag_color" nowrap=nowrap><b>Updated Date</b></th>
<th align=center class="displaytag_color"><b>Action</b></th>
</tr>
<tbody>
<?php
$count =1;
while ($row = @mysql_fetch_array($posts))
{ 
 $adId = $row['ad_id'];
 if($count%2==0){?>
<tr class="nonshaded"> 
<td align="center"><?php echo $adId; ?></td>
<td align="center"><?php echo htmlentities($row['title'],ENT_QUOTES|ENT_IGNORE); ?></td>
<td align="center"><?php echo htmlentities($row['created_by'],ENT_QUOTES|ENT_IGNORE); ?></td>
<td align="center"><?php echo htmlentities($row['updated_by'],ENT_QUOTES|ENT_IGNORE); ?></td>
<td align="center"><?php echo htmlentities($row['creation_date'],ENT_QUOTES|ENT_IGNORE); ?></td>
<td align="center"><?php echo htmlentities($row['updated'],ENT_QUOTES|ENT_IGNORE); ?></td>
<td nowrap align="center"><b><a href="<?php pages_url();?>User_Sections/menu.item.Ad_Capaigns/delete_ad.php?action=delete&adid=<?php echo $adId;?>" onclick="return confirm('Are you sure you want to delete?')"><img src="<?php pages_url();?>delete-icon.png" border="0" title="Click to delete"></a> </b>&nbsp;<b><a href="<?php pages_url();?>User_Sections/menu.item.Ad_Capaigns/menu.item.Create_Ad.php?action=edit&adid=<?php echo $adId;?>"><img src="<?php pages_url();?>Edit-icon.png" border="0" title="Click to edit"></a> </b>&nbsp;<b><a href="<?php pages_url();?>User_Sections/menu.item.Ad_Capaigns/menu.item.Statistics.php?adid=<?php echo $adId;?>"><img src="<?php pages_url();?>log-icon.png" border="0" title="Click to see log"></a> </b></td>
</tr>
<?php } else { ?>
<tr class="shaded"> 
<td align="center"><?php echo $adId; ?></td>
<td align="center"><?php echo htmlentities($row['title'],ENT_QUOTES|ENT_IGNORE); ?></td>
<td align="center"><?php echo htmlentities($row['created_by'],ENT_QUOTES|ENT_IGNORE); ?></td>
<td align="center"><?php echo htmlentities($row['updated_by'],ENT_QUOTES|ENT_IGNORE); ?></td>
<td align="center"><?php echo htmlentities($row['creation_date'],ENT_QUOTES|ENT_IGNORE); ?></td>
<td align="center"><?php echo htmlentities($row['updated'],ENT_QUOTES|ENT_IGNORE); ?></td>
<td nowrap align="center"><b><a href="<?php pages_url();?>User_Sections/menu.item.Ad_Capaigns/delete_ad.php?action=delete&adid=<?php echo $adId;?>" onclick="return confirm('Are you sure you want to delete?')"><img src="<?php pages_url();?>delete-icon.png" border="0" title="Click to delete"></a> </b>&nbsp;<b><a href="<?php pages_url();?>User_Sections/menu.item.Ad_Capaigns/menu.item.Create_Ad.php?action=edit&adid=<?php echo $adId;?>"><img src="<?php pages_url();?>Edit-icon.png" border="0" title="Click to edit"></a> </b>&nbsp;<b><a href="<?php pages_url();?>User_Sections/menu.item.Ad_Capaigns/menu.item.Statistics.php?adid=<?php echo $adId;?>"><img src="<?php pages_url();?>log-icon.png" border="0" title="Click to see log"></a> </b></td>
</tr>
  
<?php }
 $count++; 
  }
}else{
  echo "No Ad found.Please new Post";
} ?>
<tbody>
</table>
</form>
<br></td>
</tr>
</table>

<?php include(pages_dir."footer.php");?>

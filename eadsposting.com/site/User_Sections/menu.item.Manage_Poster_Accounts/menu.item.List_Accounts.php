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
} else {
  $client_id=$_SESSION['username'];
}

include(pages_dir."header.php");

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr> 
<!--td align="left" valign="top" class="account_menu"--> 
<?php 
/* if (user('account_type','return')=='Poster') {
  include(pages_dir."poster_menu.php");
  } else {
     include(pages_dir."account_menu.php");   
  }*/
?>
<!--/td-->
<td align="left" valign="top"><p>&nbsp;</p>
<form method="POST" action=savepost.php id="adForm">
<table border="1px"  width=100% align="center" cellspacing="0" cellpadding="4" style="border-collapse:collapse;">

<?php
$where='where owner_id="'.$client_id.'"';
/*if (user('account_type','return')=='Poster'){
$where='where client_id="Poster_Accounts"';
}*/
$client_id=$_SESSION['username'];

$sql = "select username,(case account_type when 'canceled' then 'Inactive' else 'Active' end) status from users  where owner_id='".$client_id."' order by username";

$posts = @mysql_query($sql);
$totalposts = mysql_num_rows($posts);
if($totalposts) { ?>
<tr> 
<th align=center class="displaytag_color"><b>User Name</b></th>
<th align=center class="displaytag_color"><b>Status</b></th>
<th align=center class="displaytag_color"><b>Action</b></th>

</tr>
<?php

$count = 1;
while ($row = @mysql_fetch_array($posts))
{ 
 $key = $row['username'];
if($count%2==0){?>
<tr class="shaded"> 
<td align="center"><?php echo htmlentities($row['username'],ENT_QUOTES|ENT_IGNORE); ?></td>
<td align="center"><?php echo htmlentities($row['status'],ENT_QUOTES|ENT_IGNORE); ?></td>
<td align="center"><b><a href="save_account.php?mode=delete&key=<?php echo $key;?>" onclick="return confirm('Are you sure you want to delete?')"><img src="<?php pages_url();?>delete-icon.png" border="0" title="Click to delete the user"></a> </b>&nbsp;<b><a href="menu.item.Create_Account.php?mode=edit&key=<?php echo $key;?>"><img src="<?php pages_url();?>Edit-icon.png" border="0" title="Click to Edit"></a></b>&nbsp;<?php if($row['status']=='Active'){ ?><b><a href="save_account.php?mode=disable&key=<?php echo $key;?>" onclick="return confirm('Are you sure you want to disable user ?')"><img src="<?php pages_url();?>disable-icon.png" border="0" title="Click to Inactivate the user"></a> </b> <?php } if($row['status']=='Inactive'){ ?><b><a href="save_account.php?mode=enable&key=<?php echo $key;?>" onclick="return confirm('Are you sure you want to enable user ?')"><img src="<?php pages_url();?>enable-icon.png" border="0" title="Click to Activate the user"></a> </b><?php } ?></td>
</tr>  
<?php } else { ?>
<tr class="shaded"> 
<td align="center"><?php echo htmlentities($row['username'],ENT_QUOTES|ENT_IGNORE); ?></td>
<td align="center"><?php echo htmlentities($row['status'],ENT_QUOTES|ENT_IGNORE); ?></td>
<td align="center"><b><a href="save_account.php?mode=delete&key=<?php echo $key;?>" onclick="return confirm('Are you sure you want to delete?')"><img src="<?php pages_url();?>delete-icon.png" border="0" title="Click to delete the user"></a> </b>&nbsp;<b><a href="menu.item.Create_Account.php?mode=edit&key=<?php echo $key;?>"><img src="<?php pages_url();?>Edit-icon.png" border="0" title="Click to Edit"></a></b>&nbsp;<?php if($row['status']=='Active'){ ?><b><a href="save_account.php?mode=disable&key=<?php echo $key;?>" onclick="return confirm('Are you sure you want to disable user ?')"><img src="<?php pages_url();?>disable-icon.png" border="0" title="Click to Inactivate the user"></a> </b> <?php } if($row['status']=='Inactive'){ ?><b><a href="save_account.php?mode=enable&key=<?php echo $key;?>" onclick="return confirm('Are you sure you want to enable user ?')"><img src="<?php pages_url();?>enable-icon.png" border="0" title="Click to Activate the user"></a> </b><?php } ?></td>
</tr> 
<?php }
 $count++; 
  }
}else{
  echo "No Records Found";
} ?>
</table>
</form>
<br></td>
</tr>
</table>

<?php include(pages_dir."footer.php");?>

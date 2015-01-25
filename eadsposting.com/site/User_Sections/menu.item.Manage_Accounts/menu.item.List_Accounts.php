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
<table border=1  width=100% align="center" cellspacing="0" cellpadding="4" style="border-collapse:collapse;">

<?php
$where='where isActive = 1 and  client_id="'.$client_id.'"';
if (user('account_type','return')=='Poster'){
$where='where isActive = 1 and client_id="Poster_Accounts"';
}
$posts = @mysql_query('select id, Username, password, email_password, last_used,Info,security_email,security_phone, user_id  from posting_accounts '. $where .' order by user_id desc, last_used asc');
$totalposts = mysql_num_rows($posts);
?>
<tr> 
<th align=center class="displaytag_color"><b>Account</b></th>
<th align=center class="displaytag_color"><b>Craigslist Password</b></th>
<th align=center class="displaytag_color"><b>Email Password</b></th>
<th align=center class="displaytag_color"><b>Last Accessed</b></th>
<th align=center class="displaytag_color"><b>Security Email</b></th>
<th align=center class="displaytag_color"><b>Security Phone</b></th>
<th align=center class="displaytag_color"><b>Client</b></th>
<th align=center class="displaytag_color"><b>Action</b></th>
</tr>
<?php if($totalposts) { 

$count = 1;
while ($row = @mysql_fetch_array($posts))
{ 
 $key = $row['id']; 
if($count%2==0){?>
<tr class="nonshaded"> 
<td align="center"><?php echo htmlentities($row['Username'],ENT_QUOTES|ENT_IGNORE); ?></td>
<td align="center"><?php echo htmlentities($row['password'],ENT_QUOTES|ENT_IGNORE); ?></td>
<td align="center"><?php echo htmlentities($row['email_password'],ENT_QUOTES|ENT_IGNORE); ?></td>
<td align="center"><?php echo htmlentities($row['last_used'],ENT_QUOTES|ENT_IGNORE); ?></td>
<?php if (user('account_type','return')=='Poster') { ?>
<td align="center"><?php echo htmlentities($row['security_email'],ENT_QUOTES|ENT_IGNORE); ?></td>
<td align="center"><?php echo htmlentities($row['security_phone'],ENT_QUOTES|ENT_IGNORE); ?></td>
<td align="center"><?php echo htmlentities($row['user_id'],ENT_QUOTES|ENT_IGNORE); ?></td>
<?php } ?>
<td align="center"><b><a href="save_account.php?mode=delete&key=<?php echo $key;?>" onclick="return confirm('Are you sure you want to delete?')"><img src="<?php pages_url();?>delete-icon.png" border="0" title="Click to delete"></a> </b>&nbsp;<b><a href="menu.item.Create_Account.php?mode=edit&key=<?php echo $key;?>"><img src="<?php pages_url();?>Edit-icon.png" border="0" title="Click to edit"></a></b>&nbsp;<b><?php if($row['Info'] == 'Y') {?><b><a href="save_account.php?mode=disable&key=<?php echo $key;?>" onclick="return confirm('Are you sure you want to disable user ?')"><img src="<?php pages_url();?>disable-icon.png" border="0" title="Click to Inactivate"></a> </b> <?php } else { ?><b><a href="save_account.php?mode=enable&key=<?php echo $key;?>" onclick="return confirm('Are you sure you want to enable user ?')"><img src="<?php pages_url();?>enable-icon.png" border="0" title="Click to activate"></a> </b> <?php }?></td></tr> 
<?php } else { ?>
<tr class="shaded"> 
<td align="center"><?php echo htmlentities($row['Username'],ENT_QUOTES|ENT_IGNORE); ?></td>
<td align="center"><?php echo htmlentities($row['password'],ENT_QUOTES|ENT_IGNORE); ?></td>
<td align="center"><?php echo htmlentities($row['email_password'],ENT_QUOTES|ENT_IGNORE); ?></td>
<td align="center"><?php echo htmlentities($row['last_used'],ENT_QUOTES|ENT_IGNORE); ?></td>
<?php if (user('account_type','return')=='Poster') { ?>
<td align="center"><?php echo htmlentities($row['security_email'],ENT_QUOTES|ENT_IGNORE); ?></td>
<td align="center"><?php echo htmlentities($row['security_phone'],ENT_QUOTES|ENT_IGNORE); ?></td>
<td align="center"><?php echo htmlentities($row['user_id'],ENT_QUOTES|ENT_IGNORE); ?></td>
<?php } ?>
<td align="center"><b><a href="save_account.php?mode=delete&key=<?php echo $key;?>" onclick="return confirm('Are you sure you want to delete?')"><img src="<?php pages_url();?>delete-icon.png" border="0" title="Click to delete"></a> </b>&nbsp;<b><a href="menu.item.Create_Account.php?mode=edit&key=<?php echo $key;?>"><img src="<?php pages_url();?>Edit-icon.png" border="0" title="Click to edit"></a></b>&nbsp;<b><?php if($row['Info'] == 'Y') {?><b><a href="save_account.php?mode=disable&key=<?php echo $key;?>" onclick="return confirm('Are you sure you want to disable user ?')"><img src="<?php pages_url();?>disable-icon.png" border="0" title="Click to Inactivate"></a> </b> <?php } else { ?><b><a href="save_account.php?mode=enable&key=<?php echo $key;?>" onclick="return confirm('Are you sure you want to enable user ?')"><img src="<?php pages_url();?>enable-icon.png" border="0" title="Click to activate"></a> </b> <?php }?></td></tr> 
<?php }
 $count++; 
  }
}else{?>

<tr> 
	<td align="center" colspan="3" style="color: red;"><?php echo "No Records Found"; ?></td>
</tr> 

<?php } ?>
</table>
</form>
<br></td>
</tr>
</table>

<?php include(pages_dir."footer.php");?>

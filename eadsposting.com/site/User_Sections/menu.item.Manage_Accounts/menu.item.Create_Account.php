<?php
include("../../setup.php");
login();
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
<!--td align="left" valign="top" class="account_menu" --> 
<?php 
 /*if (user('account_type','return')=='Poster') {
  include(pages_dir."poster_menu.php");
  } else {
     include(pages_dir."account_menu.php");   
  }*/

$client_id =$_SESSION['username'];
$mode = $_GET['mode'];
$key = $_GET['key'];

//if($mode =='edit'){
	$sql ="select * from posting_accounts where id=$key";
       $accountDetails = @mysql_query($sql);
       $row = @mysql_fetch_array($accountDetails);	
//}


  
?>

<!--/td-->
<td align="left" valign="top"><p>&nbsp;</p>
<form method="POST" action ="save_account.php" id="adform" onsubmit="return validateUserAccountData();">
<table width="100%" border="0" cellpadding="0" cellspacing="0" name="Errors">
<input type="hidden" name="mode" value="<?php echo $mode;?>" />
</table>
<table border=0  width=90% align="center" cellspacing="0" cellpadding="4">

<tr>
<td nowrap>&nbsp;</td>
<td nowrap colspan=2><span class="error" ><?php echo $showMessage;?></span></td>
</tr>
<tr>
<td nowrap>&nbsp;</td>
<td nowrap><b>Email:</b><span class="error" >*</span></td>
<td> 
<input type=text name="username_account" id="username_account" size=50 value="<?php echo htmlentities($row['Username'],ENT_QUOTES|ENT_IGNORE);?>"/><span class="error" id="username_accountError"></span>
</td>
</tr>
<tr> 
<td>&nbsp;</td>
<td><b>Craigslist Password:</b><span class="error" >*</span></td>
<td> 
<input type="text" name="password_account" id="password_account" size=50 value="<?php echo htmlentities($row['Password'],ENT_QUOTES|ENT_IGNORE);?>"/><span class="error" id="password_accountError"></span></td>
</tr>
<tr> 
<td>&nbsp;</td>
<td><b>Email Password:</b><span class="error" >*</span></td>
<td> 
<input type="text" name="email_password" id="email_password" size=50 value="<?php echo htmlentities($row['email_password'],ENT_QUOTES|ENT_IGNORE);?>"/><span class="error" id="emailPasswordError"></span></td>
</tr>
<?php if (user('account_type','return')=='Poster') { ?>
<tr> 
<td>&nbsp;</td>
<td><b>Security Email:</b></td>
<td> 
<input type="text" name="security_email" id="security_email" size=50 value="<?php echo htmlentities($row['security_email'],ENT_QUOTES|ENT_IGNORE);?>"/><span class="error" id="emailPasswordError"></span></td>
</tr>

<tr> 
<td>&nbsp;</td>
<td><b>Security Phone:</b></td>
<td> 
<input type="text" name="security_phone" id="security_phone" size=50 value="<?php echo htmlentities($row['security_phone'],ENT_QUOTES|ENT_IGNORE);?>"/><span class="error" id="emailPasswordError"></span></td>
</tr>

<tr> 
<td>&nbsp;</td>
<td><b>DOB:</b></td>
<td> 
<input type="text" name="email_dob" id="email_dob" size=50 value="<?php echo htmlentities($row['dob'],ENT_QUOTES|ENT_IGNORE);?>"/><span class="error" id="emailPasswordError"></span></td>
</tr>

<tr> 
<td>&nbsp;</td>
<td><b>Client:</b></td> 
<td> 
<select name="user_id">
<option value="<?php echo htmlentities($row['user_id'],ENT_QUOTES|ENT_IGNORE);?>" selected><?php echo htmlentities($row['user_id'],ENT_QUOTES|ENT_IGNORE);?></option>
<?php
$clients = @mysql_query("SELECT username FROM users where account_type = '';");
while ($client = @mysql_fetch_array($clients)){
	$name = $client['username'];
	echo "<option value='$name'>$name</option>";
}
?>	
</select>&nbsp;&nbsp;&nbsp;(Select Client)</td>
</tr>

<tr> 
<td>&nbsp;</td>
<td><b>Activated:</b></td>
<td> 
<select name="isActive">
	<option value="<?php echo htmlentities($row['Info'],ENT_QUOTES|ENT_IGNORE);?>" selected><?php echo htmlentities($row['Info'],ENT_QUOTES|ENT_IGNORE);?></option>
	<option value='N'>N</option>
	<option value='Y'>Y</option>
</select>&nbsp;&nbsp;&nbsp;(Select 'Y' if Account is active for postings)</td>
</tr>
<?php } ?>
<tr>
<td colspan=3><hr></td>
</tr><tr> 
<td colspan=3 align=center>To save your changes,click 'Save Changes'<br>
<br>
<input type="hidden" name="key" value="<?php echo $key;?>"/>
<input type="submit" name="submit" value="Save Changes"/>

</td>
</tr>
</table>
</form>
<br></td>
</tr>
</table>

<?php include(pages_dir."footer.php");?>


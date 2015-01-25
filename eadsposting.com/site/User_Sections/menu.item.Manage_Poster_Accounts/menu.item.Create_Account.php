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
$selectedUser = $_GET['key'];

$sql ="select * from users where Username='".$selectedUser."'";
$accountDetails = @mysql_query($sql);
$row = @mysql_fetch_array($accountDetails);	

$myusername = $row['username'];
$mypassword = $row['password'];  
$myemail = $row['email'];
//when user create/update existing and get error because of user/email already exists in system
if(empty($selectedUser)) {
  $myusername = $selectedactuser;
 // $mypassword = $row['password'];  
  $myemail = $selectedemailid;
   $mode = $selectedmode;

}

?>

<!--/td-->
<td align="left" valign="top"><p>&nbsp;</p>
<form method="POST" action ="save_account.php" id="adform" onsubmit="javascript:return validateEmailAccountData();">
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
<td nowrap><b>User Name:</b><span class="error" >*</span></td>
<td> 
<input type=text name="username_account" id="username_account" size=50 value="<?php echo htmlentities($myusername,ENT_QUOTES|ENT_IGNORE);?>"/><span class="error" id="username_accountError"></span>
</td>
</tr>
<tr> 
<td>&nbsp;</td>
<td><b>Password:</b><span class="error" >*</span></td>
<td> 
<input type="password" name="password_account" id="password_account" size=50 value="<?php echo htmlentities($mypassword,ENT_QUOTES|ENT_IGNORE);?>"/><span class="error" id="password_accountError"></span></td>
</tr>
<tr> 
<td>&nbsp;</td>
<td><b>Email:</b><span class="error" >*</span></td>
<td> 
<input type="text" name="email_account" id="email_account1" size=50 value="<?php echo htmlentities($myemail,ENT_QUOTES|ENT_IGNORE);?>"/><span class="error" id="eidError"></span></td>
</tr
<tr>
<td colspan=3><hr></td>
</tr><tr> 
<td colspan=3 align=center>To save your changes,click 'Save Changes'<br>
<br>

<input type="submit" name="submit" value="Save Changes"/>

</td>
</tr>
</table>
</form>
<br></td>
</tr>
</table>

<?php include(pages_dir."footer.php");?>


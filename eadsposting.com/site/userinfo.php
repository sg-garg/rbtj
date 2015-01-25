<?php
include("setup.php");
login('deny','advertiser');
include("header.php");
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr> 
<!--td align="left" valign="top" class="account_menu"> 
<?php include("account_menu.php");?>
</td-->
<td align="left" valign="top"><p>&nbsp;</p>
<form method="POST">
<input type=hidden name=user_form value=userinfo>
<input type=hidden name=required value='email,first_name,last_name,address,city,state,zipcode,country'>
<table width="100%" border="0" cellpadding="0" cellspacing="0" name="Errors">
<?php
form_errors("email","You must place an email address in the email address field","The email address you select is already in use please try another","<tr><td colspan=2 bgcolor=red align=center><font color=ffffff><b>","</b></font></td></tr>");
form_errors("emailsent","Verification email has been sent to chosen email address","","<tr><td colspan=2 bgcolor=red align=center><font color=ffffff><b>","</b></font></td></tr>");
form_errors("emailblocked","This email address has been blocked from this site. Please choose another one.","","<tr><td colspan=2 bgcolor=red align=center><font color=ffffff><b>","</b></font></td></tr>");
form_errors("first_name","You must place your first name in the first name field","N/A","<tr><td colspan=2 bgcolor=red align=center><font color=ffffff><b>","</b></font></td></tr>");
form_errors("last_name","You must place your last name in the last name field","N/A","<tr><td colspan=2 bgcolor=red align=center><font color=ffffff><b>","</b></font></td></tr>");
form_errors("address","You must place your street address in the address field","N/A","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff><b>","</b></font></td></tr>");
form_errors("city","You must place your city in the city field","N/A","<tr><td colspan=2 bgcolor=red align=center><font color=ffffff><b>","</b></font></td></tr>");
form_errors("state","You must place your state in the state field or type N/A if you do not have a state","N/A","<tr><td colspan=2 bgcolor=red align=center><font color=ffffff><b>","</b></font></td></tr>");
form_errors("zipcode","You must place your zip or postal code in the zip code field","N/A","<tr><td colspan=2 bgcolor=red align=center><font color=ffffff><b>","</b></font></td></tr>");
form_errors("country","Please select your country","N/A","<tr><td colspan=2 bgcolor=red align=center><font color=ffffff><b>","</b></font></td></tr>");
form_errors("password","The password you entered did not match what you put in the confirmation field","N/A","<tr><td colspan=2 bgcolor=red align=center><font color=ffffff><b>","</b></font></td></tr>");
form_errors("alterinfo_password","The password you entered was not correct. No changes saved","N/A","<tr><td colspan=2 bgcolor=red align=center><font color=ffffff><b>","</b></font></td></tr>");
?>
</table>
<table border=0  width=90% align="center" cellspacing="0" cellpadding="4">
<tr> 
<td>&nbsp;</td>
<td>Security Level:</td><td><?php user('account_type');?></td></tr><tr>
<td>&nbsp;</td>
<td>Username:</td>
<td> 
<?php echo $_SESSION['username']; ?>
</td>
</tr><tr>
<td>&nbsp;</td>
<td> E-Mail:</td>
<td><input type="text" name="userform[email]" value="<?php user("email"); ?>"></td>
</tr><tr>
<td nowrap>&nbsp;</td>
<td nowrap>Send emails to:</td>
<td> 
<select name=userform[email_setting]>
<?php existing_member_email_setting();?>
</select>
</td>
</tr><tr> 
<td>&nbsp;</td>
<td>First Name:</td>
<td> 
<input type="text" name="userform[first_name]" value="<?php user("first_name"); ?>"></td>
</tr><tr> 
<td>&nbsp;</td>
<td>Last Name:</td>
<td> 
<input type="text" name="userform[last_name]" value="<?php user("last_name");?>"></td>
</tr><tr> 
<td>&nbsp;</td>
<td>Address:</td>
<td><input type="text" name="userform[address]" value="<?php user("address");?>"></td>
</tr><tr> 
<td>&nbsp;</td>
<td>City:</td>
<td> 
<input type="text" name="userform[city]" value="<?php user("city");?>"></td>
</tr><tr>
<td>&nbsp;</td>
<td>State:</td>
<td> 
<select name="userform[state]">
<option value='' selected>Please select your state</option>
<?php existing_member_states(); ?>
</select>
</td>
</tr><tr>
<td>&nbsp;</td>
<td>Zip Code:</td>
<td> 
<input type="text" name="userform[zipcode]" value="<?php user("zipcode"); ?>"></td>
</tr><tr>
<td>&nbsp;</td>
<td>Country:</td>
<td> 
<select name="userform[country]">
<option value='' selected>Please select your country</option>
<?php existing_member_countries();?>
</select></td>
</tr><tr>
<td colspan=3>&nbsp;</td>
<tr> 
<td colspan="3"><hr size="1"></td>
</tr><tr> 
<td nowrap>&nbsp;</td>
<td nowrap>New Password:</td>
<td> 
<input type=password name="userform[password]">
</td>
</tr><tr>
<td>&nbsp;</td>
<td>Confirm<br>
New Password:</td>
<td><input type=password name="userform[confirm_password]"></td>
</tr><tr>
<td colspan=3><hr></td>
</tr><tr> 
<td colspan=3 align=center>To save your changes, enter your password and click 'Save Changes'<br>
Password: 
<input type=password name="userform[alterinfo_password]">
<br>
<input type="submit" value="Save Changes">
</td>
</tr>
</table>
</form>
<br></td>
</tr>
</table>

<?php include("footer.php");?>

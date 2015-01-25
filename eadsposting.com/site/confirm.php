<script language="php"> include("setup.php");</script>
<script language="php"> include("header.php");</script>
<table width="100%" border="0" cellspacing="5" cellpadding="5" align="center">
<tr>
<td align="center"> 
<p>&nbsp;</p>
<p><b>Become an affiliate</b></p>
<p>To begin the sign-up process we ask you to confirm your e-mail address. Enter 
your e-mail address in the field below and click the continue button. Shortly 
after, you will receive a confirmation e-mail, which requires you to click on 
a confirmation link. Clicking the link provided to you in your confirmation e-mail, 
will take you to our affiliate application. Complete the application and you 
will receive your &quot;Welcome&quot; e-mail. </p>
<table border=0>
<form method=post>
<script language="php"> form_errors("email","You must place an email address in the email address field","The email address you selected is already in use. Please do not create another account","<tr><td colspan=2 bgcolor=red align=center><font face=arial color=ffffff>","</font></td></tr>");</script>
<tr> 
<td align="center">Please enter your E-mail address to sign-up:<br>
<br>
<input type=hidden name=user_form value=signup>
<input type="text" name="userform[email]">
<br>
<br>
<input type="submit" value="Continue" name="submit">
</form>
</tr></td>
</table>
</td>
</tr>
</table>

<script language="php"> include("footer.php");</script>

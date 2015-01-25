<script language="php"> include("setup.php");</script>
<script language="php"> include("header.php");</script>

<table width="100%" border="0" cellspacing="5" cellpadding="5" align="center">
<tr> 
<td align="center"> 
<p align="center"><br>
<br>
<b><font color="#FF0000">Error! Invalid user name or password</font></b><br>
<br>
</p>
<form method=post>
<div align="center"> <b>Can't remember your user name or password?<br>
Enter your email address below and it will be sent to you.</b><br>
<input type=hidden name=user_form value=userinfo>
<input type=text name=userform[send_login_info]>
<input type=submit value="Send login info" name="submit">
</form>
<br>
<br>
<a href=contact.php>Contact us if you need help</a>
<form method=post>
<table border=0>
<tr> 
<td><b><font size="2">Username or eMail address:</font></b></td>
<td> 
<input type=text name=username>
</td>
</tr>
<tr> 
<td><b><font size="2">Password:</font></b></td>
<td> 
<input type=password name=password>
</td>
</tr>
<tr> 
<td colspan=2 nowrap><b>Use Auto-login 
<input type=checkbox checked name=autologin value=1>
<br>
</b> 
<center>
<b> 
<input type=submit value="Login" name="submit">
</b> 
</center>
</td>
</tr>
</table>
</form>
</div>
<p><br>
<br>
</p>
</td>
</tr>
</table>
 
<script language="php"> include("footer.php");</script>


<script language="php"> include("setup.php");</script>
<script language="php"> include("header.php");</script>
<table width="100%" border="0" cellspacing="5" cellpadding="5" align="center">
<tr> 
<td align="center"> 
<center>
<p>&nbsp;</p>
<p><b>Error! That email address is not in our database</b> </p>
<form method=post>
<div align="center"> 
<p><b>Can't remember your username or password?<br>
Enter your email address below and it will be sent to you</b>.<br>
<input type=hidden name=user_form value=userinfo>
<input type=text name=userform[send_login_info]>
<input type=submit value="Send login info" name="submit">
</p>
</div>
</form>
</center>
<form method=post>
<div align="center"> 
<table border=0>
<tr> 
<td> 
<p>Username or eMail address:</p>
</td>
<td> 
<input type=text name=username>
</td>
</tr>
<tr> 
<td> 
<p>Password:</p>
</td>
<td> 
<input type=password name=password>
</td>
</tr>
<tr> 
<td colspan=2> 
<input type=submit value="Login" name="submit">

</td>
</tr>
</table>
</div>
</form>
<div align="center"></div>
</td>
</tr>
</table>
<br>
<script language="php"> include("footer.php");</script>

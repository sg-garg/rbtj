<script language="php"> include("setup.php");</script>
<script language="php"> include("header.php");</script>
<table width="100%" border="0" cellspacing="5" cellpadding="5" align="center">
<tr> 
<td align="center"> 
<p>&nbsp;</p>
<p align="center"><b>Log In</b> </p>
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
<td colspan=2 nowrap> 
<p>Use Auto-login 
<input type=checkbox checked name=autologin value=1>
<br>
<input type=submit value="Login" name="submit">
</p>
</td>
</tr>
</table>
</div>
<p align="center"><b><a href="<script language="php"> pages_url();</script>lost_login.php">(Forgot 
your password/ID?)</a></b> </p>
</form>
<p align="center"><br>
<br>
</p>
</td>
</tr>
</table>
<script language="php"> include("footer.php");</script>


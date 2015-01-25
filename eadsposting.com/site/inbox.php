<script language="php"> include("setup.php");</script>
<script language="php"> login('deny','advertiser');</script>  
<script language="php"> include("header.php");</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td valign=top align="left" class="account_menu"><script language="php"> include("account_menu.php");</script></td>
<td width="100%" valign="top"> 
<table width="100%" border="0" cellspacing="4" cellpadding="4">
<tr> 
<td align="center" valign="top"> 
<p>&nbsp;</p>
<p> 
<script language="php"> show_pop3_info();</script>
<br>
<b>You have 
<script language="php"> inboxcount();</script>
message(s) in your inbox <br>
<font size=-2>(messages older then 
<script language="php"> inbox_days();</script>
day(s) are automatically deleted)</font><br>
</b><br>
</p>
<table border=1 cellpadding="3" cellspacing="0" bordercolor="#111111">
<tr bordercolor="#CCCCCC"> 
<td><b>Date and Time</b></td>
<td><b>Subject</b></td>
<td><b>Delete?</b></td>
</tr>
<script language="php"> showinbox("<tr><td><font face=arial>","</td><td><font face=arial>","</td></tr>","Delete");</script>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
<script language="php"> include("footer.php");</script>


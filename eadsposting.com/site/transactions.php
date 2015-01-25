<script language="php"> include("setup.php");</script>
<script language="php"> login('deny','advertiser');</script>  
<script language="php"> include("header.php");</script>
<table border=0 width="100%" cellpadding="0" cellspacing="0">
<tr>
<td valign=top align="left" class="account_menu">
<script language="php"> include("account_menu.php");</script>
</td>
<td valign=top width="100%"> 
<p>&nbsp;</p>
<table width="100%" border="0" cellspacing="4" cellpadding="4">
<tr>
<td width="100%" valign="top" align="center"> 
<table width=400 cellpadding=3 border=0 cellspacing="0">
<tr align="left"> 
<td colspan=2><b>Account Balances</b></td>
</tr>
<tr> 
<td nowrap align="left">Total Cash Balance:</td>
<td align=right width="100%" nowrap><b>$ <script language="php"> cash_totals();</script>&nbsp;</b></td>
</tr>
<tr> 
<td nowrap align="left">Total Point Balance:</td>
<td align=right width="100%" nowrap><b><script language="php"> points_totals();</script>&nbsp;</b></td>
</tr>
</table>
<p>&nbsp;</p>
<table border=0 width=400 cellpadding="3" cellspacing="0">
<tr> 
<td colspan=2 nowrap align="left"><b>Account Transaction History </b></td>
</tr>
<tr> 
<td colspan=2 align="left">Cash</td>
</tr>
<script language="php"> cash_transactions("all","<tr><td align=right>","</td><td align=right>","</td></tr>","desc","yes","</td><td>",5,100,10);</script>
<tr>
<td colspan=2 align="left">Points</td>
</tr>
<script language="php"> point_transactions("all","<tr><td align=right>","</td><td align=right>","</td></tr>","desc","yes","</td><td>",5,1,10);</script>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
</td>
</tr>
</table></td>
</tr>
</table>
<script language="php"> include("footer.php");</script>

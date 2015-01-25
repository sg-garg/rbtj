<? 
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header ('Last-Modified: ' . gmdate('D, d M Y H:i:s'). ' GMT');
    header ('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header ('Pragma: no-cache');
session_start();?>
<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=<? echo charset;?>">
<title><? echo $_GET['M'].' submenu';?></title>
</head>
<STYLE TYPE="text/css">
  <!--
body {
color: #000000;
font-family: Verdana, Arial, Helvetica, sans-serif;
font-size: 11px;}
a.MN9782641 {text-decoration:none; color:#FFFFFF;  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;} 
a.MN9782641:hover {text-decoration:none; color:#FAFF6B;  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;} 
a.SMN9782641 {text-decoration:none; color:#FFFFFF;  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;} 
a.SMN9782641:hover {text-decoration:none; color:#FAFF6B;  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;}  
table.MT9782641 {background:#49913E; border:2 outset #39617A; }
td.MTD9782641 {padding-left:4; padding-right:4; background:#2B86B7; border:1 outset #A3FFFF; }
.MTI9782641 {color:#FFFFFF; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;}  
.MSI9782641 {color:#FFFFFF; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;}  
-->
  </STYLE>
<script>window.focus();</script>
<STYLE TYPE='text/css'>#DM9790193 {visibility:hidden;}</STYLE>
<script language="javascript">var thisbrowser9790193
if(document.layers){ thisbrowser9790193='NN4'; }
if(document.all){ thisbrowser9790193='IE'; }
if(!document.all && document.getElementById){ thisbrowser9790193='NN6'; }
function hilite9790193(menuitem) 
{
if(thisbrowser9790193=='IE') document.all[menuitem].style.backgroundColor = '#144C24';
if(thisbrowser9790193=='NN6') document.getElementById(menuitem).style. backgroundColor = '#144C24';
}
function unhilite9790193(menuitem) 
{
if(thisbrowser9790193=='IE') document.all[menuitem].style.backgroundColor = '#2B86B7';
if(thisbrowser9790193=='NN6') document.getElementById(menuitem).style. backgroundColor = '#2B86B7';
}
document.open();
document.write('<body background=blue.jpg topmargin="5" leftmargin="5" rightmargin="0" marginwidth="0" marginheight="0">');
document.write('<STYLE TYPE="text/css">a.MNA9790193 {text-decoration:none; color:#FFFFFF; font-weight: 400; font-family: Verdana; font-size:11px; font-style:normal;} </STYLE>');
document.write('<STYLE TYPE="text/css">P.MN9790193 {color:#FFFFFF; font-weight: 400; font-family: Verdana; font-size:11px; font-style:normal;} </STYLE>');
document.write('<STYLE TYPE="text/css">a.MNA9790193:hover {text-decoration:none; color:#FAFF6B; }</STYLE>');
document.write('<STYLE TYPE="text/css">a.SMNA9790193 {text-decoration:none; color:#FFFFFF; font-weight: 400; font-family: Verdana; font-size:11px; font-style:normal;} </STYLE>');
document.write('<STYLE TYPE="text/css">a.SMNA9790193:hover {text-decoration:none; color:#FAFF6B; }</STYLE>');
document.write('<STYLE TYPE="text/css">P.SMN9790193 {text-decoration:none; color:#FFFFFF; font-weight: 400; font-family: Verdana; font-size:11px; font-style:normal;} </STYLE>');
document.write('<STYLE TYPE="text/css">table.SMT9790193 {background:#49913E; border:1 outset #39617A; }');
document.write('td.MTD9790193 {padding-left:4; padding-right:4; background:#2B86B7; border:1 outset #A3FFFF; }');
document.write('.MTI9790193 {color:#FFFFFF; font-weight: 400; font-family: Verdana; font-size: 11px; font-style:normal;}');
document.write('.MSI9790193 {color:#FFFFFF; font-weight: 400; font-family: Verdana; font-size: 11px; font-style:normal;}</STYLE>');
document.write('<table border=0 class="MT9790193" bgcolor=#39617A cellspacing=1 cellpadding=2 width=190><TR><TD bgcolor=#39617A><table border=0 cellspacing=0 cellpadding=2 class="MT9790193" width="100%">');
<?
if ($_GET['M']=='accounting'){?>
document.write('<tr><td align=LEFT id="MI9801518" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9801518\');" onMouseOut="unhilite9790193(\'MI9801518\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9801518\');" onMouseOut="unhilite9790193(\'MI9801518\');" href="redeemmgr.php" target="_<? echo  $_SESSION[target];?>" title="">Redemption Options</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9785366" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9785366\');" onMouseOut="unhilite9790193(\'MI9785366\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9785366\');" onMouseOut="unhilite9790193(\'MI9785366\');" href="paymentopt.php" target="_<? echo  $_SESSION[target];?>" title="">Payment Options</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9788281" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9788281\');" onMouseOut="unhilite9790193(\'MI9788281\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9788281\');" onMouseOut="unhilite9790193(\'MI9788281\');" href="transactions.php" target="_<? echo  $_SESSION[target];?>"  accesskey=l title="">Transaction <u>L</u>edger</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9779951" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9779951\');" onMouseOut="unhilite9790193(\'MI9779951\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9779951\');" onMouseOut="unhilite9790193(\'MI9779951\');" href="convertpoints.php" target="_<? echo  $_SESSION[target];?>" title="">Convert Points To Cash</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9798625" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9798625\');" onMouseOut="unhilite9790193(\'MI9798625\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9798625\');" onMouseOut="unhilite9790193(\'MI9798625\');" href="cashearnings.php" target="_<? echo  $_SESSION[target];?>" title="">Cash Balances</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9789052" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9789052\');" onMouseOut="unhilite9790193(\'MI9789052\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9789052\');" onMouseOut="unhilite9790193(\'MI9789052\');" href="pointearnings.php" target="_<? echo  $_SESSION[target];?>" title="">Point Balances</a>');
document.write('</td></tr>');
<?
}
if ($_GET['M']=='users'){?>
document.write('<tr><td align=LEFT id="MI2004051300" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI2004051300\');openSM9778640();;" onMouseOut="unhilite9790193(\'MI2004051300\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI2004051300\');openSM9778640();;" onMouseOut="unhilite9790193(\'MI2004051300\');" href="adduser.php" target="_<? echo  $_SESSION[target];?>" title="">Add a User</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI2004031300" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI2004031300\')" onMouseOut="unhilite9790193(\'MI2004031300\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI2004031300\');" onMouseOut="unhilite9790193(\'MI2004031300\');" href="membertypes.php" target="_<? echo  $_SESSION[target];?>" title="">Membership Types</a>');
document.write('</td></tr>'); 
document.write('<tr><td align=LEFT id="MI9790012" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9790012\');" onMouseOut="unhilite9790193(\'MI9790012\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9790012\');" onMouseOut="unhilite9790193(\'MI9790012\');"  accesskey=u href="usermanager.php" target="_<? echo  $_SESSION[target];?>" title="">View/Edit/Delete <u>U</u>sers</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9801291" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9801291\');" onMouseOut="unhilite9790193(\'MI9801291\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9801291\');" onMouseOut="unhilite9790193(\'MI9801291\');" href="purge.php" target="_<? echo  $_SESSION[target];?>" title="">Purge Old Accounts</a>');
document.write('</td></tr>');
<?
}
if ($_GET['M']=='reports'){?>
document.write('<tr><td align=LEFT id="MI9781077" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9781077\');" onMouseOut="unhilite9790193(\'MI9781077\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9781077\');" onMouseOut="unhilite9790193(\'MI9781077\');" href="viewrefs.php" target="_<? echo  $_SESSION[target];?>" title="">Referral Stats</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9810015" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9810015\');" onMouseOut="unhilite9790193(\'MI9810015\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9810015\');" onMouseOut="unhilite9790193(\'MI9810015\');" href="viewclicks.php?type=cash" target="_<? echo  $_SESSION[target];?>" title="">Cash Click Counters</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI2004071401" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI2004071401\');openSM9778448();;" onMouseOut="unhilite9790193(\'MI2004071401\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI2004071401\');openSM9778448();;" onMouseOut="unhilite9790193(\'MI2004071401\');" href="viewclicks.php?type=points" target="_<? echo  $_SESSION[target];?>" title="">Point Click Counters</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI2004082701" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI2004082701\');openSM9778448();;" onMouseOut="unhilite9790193(\'MI2004082701\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI2004082701\');openSM9778448();;" onMouseOut="unhilite9790193(\'MI2004082701\');" href="viewclicks.php?type=Total" target="_<? echo  $_SESSION[target];?>" title="">Total Click Counter</a>');
document.write('</td></tr>');
<?
}
if ($_GET['M']=='cheaters'){?> 
document.write('<tr><td align=LEFT id="MI9798463" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9798463\');" onMouseOut="unhilite9790193(\'MI9798463\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9798463\');" onMouseOut="unhilite9790193(\'MI9798463\');" href="dupfinder.php" target="_<? echo  $_SESSION[target];?>" title="">List Cheaters</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9805250" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9805250\');" onMouseOut="unhilite9790193(\'MI9805250\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9805250\');" onMouseOut="unhilite9790193(\'MI9805250\');" href="posdupfind.php" target="_<? echo  $_SESSION[target];?>" title="">List Possible Cheaters</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9782673" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9782673\');" onMouseOut="unhilite9790193(\'MI9782673\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9782673\');" onMouseOut="unhilite9790193(\'MI9782673\');" href="samecomputer.php" target="_<? echo  $_SESSION[target];?>" title="">Accounts Accessed By Same PC</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9797598" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9797598\');" onMouseOut="unhilite9790193(\'MI9797598\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9797598\');" onMouseOut="unhilite9790193(\'MI9797598\');" href="possamecomputer.php" target="_<? echo  $_SESSION[target];?>" title="">Accounts Possibly Accessed By Same PC</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9787917" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9787917\');" onMouseOut="unhilite9790193(\'MI9787917\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9787917\');" onMouseOut="unhilite9790193(\'MI9787917\');" href="viewturing.php" target="_<? echo  $_SESSION[target];?>" title="">Bad Turing Clicks Report</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9806212" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9806212\');" onMouseOut="unhilite9790193(\'MI9806212\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9806212\');" onMouseOut="unhilite9790193(\'MI9806212\');" href="cheatlink.php" target="_<? echo  $_SESSION[target];?>" title="">Cheat Link Report</a>');
document.write('</td></tr>');
<?
}
if ($_GET['M']=='ads'){?> 
document.write('<tr><td align=LEFT id="MI9779476" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9779476\');" onMouseOut="unhilite9790193(\'MI9779476\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9779476\');" onMouseOut="unhilite9790193(\'MI9779476\');"  accesskey=a href="autosurf.php?AS=1" target="_<? echo  $_SESSION[target];?>" title=""><u>A</u>uto/Manual Surf</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9787592" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9787592\');" onMouseOut="unhilite9790193(\'MI9787592\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9787592\');" onMouseOut="unhilite9790193(\'MI9787592\');"  accesskey=e href="emailadmgr.php" target="_<? echo  $_SESSION[target];?>" title=""><u>E</u>mail Ads</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9790140" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9790140\');" onMouseOut="unhilite9790193(\'MI9790140\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9790140\');" onMouseOut="unhilite9790193(\'MI9790140\');"  accesskey=c href="ptcadmgr.php" target="_<? echo  $_SESSION[target];?>" title="">Paid2<u>C</u>lick Ads</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI204020400" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI204020400\');" onMouseOut="unhilite9790193(\'MI204020400\');" >'); 
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI204020400\');" onMouseOut="unhilite9790193(\'MI204020400\');" href="reviewadmgr.php" accesskey=w target="_<? echo  $_SESSION[target];?>" title="">Paid2Revie<u>W</u> Ads</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9778018" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9778018\');" onMouseOut="unhilite9790193(\'MI9778018\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9778018\');" onMouseOut="unhilite9790193(\'MI9778018\');" accesskey=s href="ptsadmgr.php" target="_<? echo  $_SESSION[target];?>" title="">Paid2<u>S</u>ignup Ads</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9808093" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9808093\');" onMouseOut="unhilite9790193(\'MI9808093\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9808093\');" onMouseOut="unhilite9790193(\'MI9808093\');"  accesskey=p href="startpage.php" target="_<? echo  $_SESSION[target];?>" title=""><u>P</u>aid Start Page</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9808215" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9808215\');" onMouseOut="unhilite9790193(\'MI9808215\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9808215\');" onMouseOut="unhilite9790193(\'MI9808215\');"  accesskey=r href="admgr.php" target="_<? echo  $_SESSION[target];?>" title=""><u>R</u>otating Ads</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9804610" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9804610\');" onMouseOut="unhilite9790193(\'MI9804610\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9804610\');" onMouseOut="unhilite9790193(\'MI9804610\');"  accesskey=m href="massmail.php" target="_<? echo  $_SESSION[target];?>" title="">Send e<u>M</u>ail To Members</a>');
document.write('</td></tr>');
<?
}
if ($_GET['M']=='targeting'){?> 
document.write('<tr><td align=LEFT id="MI9778775" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9778775\');" onMouseOut="unhilite9790193(\'MI9778775\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9778775\');" onMouseOut="unhilite9790193(\'MI9778775\');" href="keywords.php" target="_<? echo  $_SESSION[target];?>" title="">Keyword Selections</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9794413" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9794413\');" onMouseOut="unhilite9790193(\'MI9794413\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9794413\');" onMouseOut="unhilite9790193(\'MI9794413\');" href="states.php" target="_<? echo  $_SESSION[target];?>" title="">State Selections</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9805466" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9805466\');" onMouseOut="unhilite9790193(\'MI9805466\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9805466\');" onMouseOut="unhilite9790193(\'MI9805466\');" href="countries.php" target="_<? echo  $_SESSION[target];?>" title="">Country Selections</a>');
document.write('</td></tr>');
<?
}
if ($_GET['M']=='blockers'){?> 
document.write('<tr><td align=LEFT id="MI9808535" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9808535\');" onMouseOut="unhilite9790193(\'MI9808535\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9808535\');" onMouseOut="unhilite9790193(\'MI9808535\');" href="browser.php" target="_<? echo  $_SESSION[target];?>" title="">Block certain browsers</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9799342" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9799342\');" onMouseOut="unhilite9790193(\'MI9799342\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9799342\');" onMouseOut="unhilite9790193(\'MI9799342\');" href="emails.php" target="_<? echo  $_SESSION[target];?>" title="">Block email addresses and domains</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9795238" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9795238\');" onMouseOut="unhilite9790193(\'MI9795238\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9795238\');" onMouseOut="unhilite9790193(\'MI9795238\');" href="ips.php" target="_<? echo  $_SESSION[target];?>" title="">Block IPs and host names</a>');
document.write('</td></tr>');
<?
}
if ($_GET['M']=='settings'){?> 
document.write('<tr><td align=LEFT id="MI9781353" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9781353\');" onMouseOut="unhilite9790193(\'MI9781353\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9781353\');" onMouseOut="unhilite9790193(\'MI9781353\');" href="password.php" target="_<? echo  $_SESSION[target];?>" title="">Set Admin Password</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI005062501" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI005062501\');openSM9775104();;" onMouseOut="unhilite9790193(\'MI005062501\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI005062501\');openSM9775104();;" onMouseOut="unhilite9790193(\'MI005062501\');" href="charset.php" target="_<? echo  $_SESSION[target];?> title="">Set Default Character Set</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI2004011701" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI2004011701\');" onMouseOut="unhilite9790193(\'MI2004011701\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI2004011701\');" onMouseOut="unhilite9790193(\'MI2004011701\');" href="urlsanddirs.php" target="_<? echo  $_SESSION[target];?>" title="">Set URLs and Directories</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI2004070101" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI2004070101\');openSM9775104();;" onMouseOut="unhilite9790193(\'MI2004070101\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI2004070101\');openSM9775104();;" onMouseOut="unhilite9790193(\'MI2004070101\');" href="timer_settings.php" target="_<? echo  $_SESSION[target];?>" title="">Timer Settings</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI2005010101" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI2005010101\');openSM9775104();;" onMouseOut="unhilite9790193(\'MI2005010101\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI2005010101\');openSM9775104();;" onMouseOut="unhilite9790193(\'MI2005010101\');" href="autosurfconfig.php" target="_<? echo  $_SESSION[target];?>" title="">Auto/Manual Surf Settings</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9782228" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9782228\');" onMouseOut="unhilite9790193(\'MI9782228\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9782228\');" onMouseOut="unhilite9790193(\'MI9782228\');" href="accounting_settings.php" target="_<? echo  $_SESSION[target];?>" title="">Accounting Settings</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9785120" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9785120\');" onMouseOut="unhilite9790193(\'MI9785120\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9785120\');" onMouseOut="unhilite9790193(\'MI9785120\');" href="emailsettings.php" target="_<? echo  $_SESSION[target];?>" title="">Site eMail Settings</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9812206" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9812206\');" onMouseOut="unhilite9790193(\'MI9812206\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9812206\');" onMouseOut="unhilite9790193(\'MI9812206\');" href="anticheat.php" target="_<? echo  $_SESSION[target];?>" title="">Anti-Cheat Settings</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9789220" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9789220\');" onMouseOut="unhilite9790193(\'MI9789220\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9789220\');" onMouseOut="unhilite9790193(\'MI9789220\');" href="bksettings.php" target="_<? echo  $_SESSION[target];?>" title="">MySQL Backup Settings</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9796832" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9796832\');" onMouseOut="unhilite9790193(\'MI9796832\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9796832\');" onMouseOut="unhilite9790193(\'MI9796832\');" href="listme.php" target="_<? echo  $_SESSION[target];?>" title="">List Your site</a>');
document.write('</td></tr>');
<?
}
if ($_GET['M']=='database'){?> 
document.write('<tr><td align=LEFT id="MI9780022" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9780022\');" onMouseOut="unhilite9790193(\'MI9780022\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9780022\');" onMouseOut="unhilite9790193(\'MI9780022\');"  accesskey=b href="backup.php" title=""><u>B</u>ackup MySQL Data</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9777902" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9777902\');" onMouseOut="unhilite9790193(\'MI9777902\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9777902\');" onMouseOut="unhilite9790193(\'MI9777902\');" href="restore.php" target="_<? echo  $_SESSION[target];?>" title="">Restore MySQL Data</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI9795470" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI9795470\');" onMouseOut="unhilite9790193(\'MI9795470\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI9795470\');" onMouseOut="unhilite9790193(\'MI9795470\');" href="maillist.php" title="">Download eMail Addresses</a>');
document.write('</td></tr>');
document.write('<tr><td align=LEFT id="MI2004022301" height=10 bgcolor=#2B86B7 class="MTD9790193" onMouseOver="hilite9790193(\'MI2004022301\');" onMouseOut="unhilite9790193(\'MI2004022301\');" >');
document.write('<a class="SMNA9790193" onMouseOver="hilite9790193(\'MI2004022301\');" onMouseOut="unhilite9790193(\'MI2004022301\');" href="mysql_admin.php" target="_<? echo  $_SESSION[target];?>" title="">MySQL Admin</a>');
document.write('</td></tr>');
<? } ?>
document.write('</table></TD></TR></table><br><center><a href=javascript:window.close()>Close Submenu</a></center>');
document.close();
</script>
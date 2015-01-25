<?
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
$noheader=1;
admin_login();
$paidmailinsert=@mysql_query("select emailid,description,id,clicks,run_quantity from ".mysql_prefix."email_ads where clicks<run_quantity or run_type='ongoing' order by emailid desc");
?>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=<? echo charset;?>">
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function copyit(theField) {
var tempval=eval("document."+theField)
tempval.focus()
tempval.select()
therange=tempval.createTextRange()
therange.execCommand("Copy")
}
//  End -->
</script>
 <STYLE TYPE="text/css">
  <!--
    textarea,select,input{
background: #ffffff;
color: #183468;
font-family: Verdana, Arial, Helvetica, sans-serif;
font-size: 11px;font-weight: bold;
text-indent: 2px;
border:1px groove #002458;
border-top-width: 1px;
border-right-width: 1px;
border-bottom-width: 2px;
border-left-width: 1px;
 }
  .checkbox{
background: ;
text-indent: 0px;
border:;
border-top-width: 0px;
border-right-width: 0px;
border-bottom-width: 0px;
border-left-width: 0px;
}
th,td {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;}
body {
color: #000000;
font-family: Verdana, Arial, Helvetica, sans-serif;
font-size: 11px;
scrollbar-face-color: #4E8CD1;
scrollbar-highlight-color: #2060B0;
scrollbar-shadow-color: #D0E4F8;
scrollbar-3dlight-color: #D0E4F8;
scrollbar-arrow-color: #2E6CB3;
scrollbar-track-color: #D0E4F8;
scrollbar-darkshadow-color: #2060B0;
scrollbar-base-color: #D0E4F8
 * }
-->
</STYLE>
</HEAD>
<body>
<form name=it>
Current active email ads from newest to oldest<br><br>
<?
while ($listpm=@mysql_fetch_array($paidmailinsert)){
$count++;
echo "<table border=0 width=410><tr><td width=210>Username: $listpm[id]</td><td width=100>Clicks: $listpm[clicks]</td><td width=100>Expires: $listpm[run_quantity]</td></tr><tr><td colspan=3>Description: $listpm[description]</td></tr></table>";
?>
<input type=text size=31 name=select<? echo $count;?> value='&lt;PM&gt;<? echo $listpm[emailid];?>&lt;/PM&gt;'><input onclick="copyit('it.select<? echo $count;?>')" type="button" value="Copy" name="cpy">
<hr>
<?
}
echo "</form></body>";

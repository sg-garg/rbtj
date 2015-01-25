<?php
include("../../setup.php");
login('allow','Poster');
$client_id=$_GET['poster_id'];

include(pages_dir."header.php");
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr> 
<!--td align="left" valign="top" class="account_menu"--> 
<?php /*include(pages_dir."poster_menu.php");*/ ?>
<?php
$rec_limit = 100;
$myQuery = "select count(*) mypost  from log_post where posted_by ='".$client_id."' and creation_date>now()-interval 1 day";
$myTotal = @mysql_query($myQuery);
$row = @mysql_fetch_row($myTotal);
$myTotalCount = $row[0];

 /* Get total number of records */

$retval = @mysql_query("SELECT count(*) FROM log_post where posted_by ='".$client_id."'");
$row = @mysql_fetch_row($retval);
$rec_count = $row[0];

if( isset($_GET{'page'} ) )
{
   $page = $_GET{'page'} + 1;
   $offset = $rec_limit * $page ;
}
else
{
   $page = 0;
   $offset = 0;
}
$left_rec = $rec_count - ($page * $rec_limit);

$sql = "SELECT post_id, confirmation_url, date_format(creation_date,'%m-%d-%Y %H:%i') creation FROM log_post where posted_by ='".$client_id."' order by creation_date desc LIMIT $offset, $rec_limit";

$retval = @mysql_query($sql);

$navigationRow ="";
if( $page > 0 )
{
   $last = $page - 2;
   $navigationRow = "<tr \"nonshaded\"><td colspan=\"3\"><a href=\"$_PHP_SELF?poster_id=$client_id&page=$last\">Last 100 Records</a> |<a href=\"$_PHP_SELF?poster_id=$client_id&page=$page\">Next 100 Records</a>";

}
else if( $page == 0 )
{
   $navigationRow = "<tr \"nonshaded\"><td colspan=\"3\"><a href=\"$_PHP_SELF?poster_id=$client_id&page=$page\">Next 100 Records</a>";
}
else if( $left_rec < $rec_limit )
{
   $last = $page - 2;
   $navigationRow = "<tr \"nonshaded\"><td colspan=\"3\"><a href=\"$_PHP_SELF?poster_id=$client_id&page=$last\">Last 100 Records</a>";
}

?>

<!--/td-->
<td align="left" valign="top">
<table width=100% align="center" cellspacing="0" cellpadding="4">
<tr><td><b>Postings in the last 24 hours:&nbsp;&nbsp;<?php echo $myTotalCount;?></td>&nbsp;<td></td><td>&nbsp;</td></tr>
<?php echo $navigationRow; ?></td></tr></table>
<form method="POST">
<table border=1  width=100% align="center" cellspacing="0" cellpadding="4" style="border-collapse:collapse;">
<tr><th class="displaytag_color">Post Id</th><th class="displaytag_color">Confirmation URL</th><th class="displaytag_color">Creation Date</th></tr>
<?php 
$count =1;
while($row = @mysql_fetch_array($retval)){   
    if($count%2==0){
    echo "<tr class=\"nonshaded\"><td width=30%>{$row['post_id']}</td> ".
         "<td><a href=".htmlentities($row['confirmation_url'],ENT_QUOTES|ENT_IGNORE)." target=_blank>".htmlentities($row['confirmation_url'],ENT_QUOTES|ENT_IGNORE)."</a></td> <td nowrap>{$row['creation']}</td></tr>";
    }else {
      echo "<tr class=\"shaded\"><td width=30%>{$row['post_id']}</td> ".
         "<td><a href=".htmlentities($row['confirmation_url'],ENT_QUOTES|ENT_IGNORE)." target=_blank>".htmlentities($row['confirmation_url'],ENT_QUOTES|ENT_IGNORE)."</a></td> <td nowrap>{$row['creation']}</td></tr>";

    }
  $count++;
} 
$navigationRow = $navigationRow."|<a href=\"#Top\"> Back to Top… </a> </td></tr>";
echo $navigationRow;
?>
</table>
</form>
<br></td>
</tr>
</table>

<?php include(pages_dir."footer.php");?>


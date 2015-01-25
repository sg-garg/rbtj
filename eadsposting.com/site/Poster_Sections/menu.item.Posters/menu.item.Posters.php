<?php
include("../../setup.php");
//login();
login('allow','Poster');
include(pages_dir."header.php");
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr> 
<!--td align="left" valign="top" class="account_menu"--> 
<?php /*include(pages_dir."poster_menu.php");*/?>
<?php
$rec_limit = 100;
$posts = @mysql_query("select u.username user_id,COALESCE (total_post,0) mypost  from users u left join (select  distinct posted_by,count(*) total_post from log_post l where  l.creation_date>now()-interval 1 day  group by l.posted_by order by count(*)) a on (u.username=a.posted_by) where u.account_type='Poster' order by mypost");
$rec_count =  mysql_num_rows($posts);

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

$sql = "select u.username user_id,COALESCE (total_post,0) mypost  from users u left join (select  distinct posted_by,count(*) total_post from log_post l where  l.creation_date>now()-interval 1 day  group by l.posted_by order by count(*)) a on (u.username=a.posted_by) where u.account_type='Poster' order by mypost LIMIT $offset, $rec_limit";

$listAll = @mysql_query($sql);

$navigationRow ="";
if( $page > 0 )
{
   $last = $page - 2;
   $navigationRow = "<tr class=\"nonshaded\"><td colspan=\"3\"><a href=\"$_PHP_SELF?page=$last\">Last 100 Records</a> |<a href=\"$_PHP_SELF?page=$page\">Next 100 Records</a>";

}
else if( $page == 0 )
{
   $navigationRow = "<tr class=\"nonshaded\"><td colspan=\"3\"><a href=\"$_PHP_SELF?page=$page\">Next 100 Records</a>";
}
else if( $left_rec < $rec_limit )
{
   $last = $page - 2;
   $navigationRow = "<tr class=\"nonshaded\"><td colspan=\"3\"><a href=\"$_PHP_SELF?page=$last\">Last 100 Records</a>";
}

?>

<!--/td-->
<td align="left" valign="top">
<table width=100% align="center" cellspacing="0" cellpadding="4">
<?php echo $navigationRow; ?></td></tr></table>
<form method="POST">
<table border=1  width=100% align="center" cellspacing="0" cellpadding="4" style="border-collapse:collapse;">
<tr><th class="displaytag_color">User Id</th><th class="displaytag_color">Total Post in last 24 hrs</th></tr>
<?php 
$count =1;
while($row = @mysql_fetch_array($listAll))
{   
    if($count%2==0){
    echo "<tr class=\"nonshaded\"><td align=center><a href=\"Poster_History.php?poster_id={$row['user_id']}\">{$row['user_id']}</a></td> ".
         "<td align=center width=40%>{$row['mypost']}</td></tr>";
    }else {
      echo "<tr class=\"shaded\"><td align=center><a href=\"Poster_History.php?poster_id={$row['user_id']}\">{$row['user_id']}</a></td> ".
         "<td align=center width=40%>{$row['mypost']}</td></tr>";

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


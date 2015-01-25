<?php
include("../../setup.php");
//login();
login('allow','Poster');
include(pages_dir."header.php");
$_SESSION['ad_post_count']=1; //this counter will be reset everytime when poster comes back from the manual posting page
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr> 
<!--td align="left" valign="top" class="account_menu"--> 
<?php /*include(pages_dir."poster_menu.php");
 */ ?>
<?php
$rec_limit = 100;
$posts = @mysql_query("select username,count(*) from users,log_post where account_type='' and (username=log_post.user_id or log_post.user_id is null) and creation_date>now()-interval  1 day group by username order by 2 asc");
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

$sql = "select user_id,mypost from ((select username user_id, 0 mypost from users u where account_type='' and owner_id='' and username not in (select distinct user_id from log_post where creation_date>now()-interval 1 day) order by mypost)  union (select  distinct u.username,round(count(*)/(u.free_refs+1)) from users u left join log_post l on(u.username=l.user_id) where account_type='' and owner_id='' and l.creation_date>now()-interval 1 day  group by l.user_id order by count(*))) a order by mypost LIMIT $offset, $rec_limit";

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
$clienttopost="<font color=red><b>Post for this client&gt;&gt;&gt;&gt;&gt; </b></font>";

while($row = @mysql_fetch_array($listAll))
{   
    if($count%2==0){
    echo "<tr class=\"nonshaded\"><td align=center><a href=\"../../User_Sections/menu.item.Post_Ads/menu.item.Manual_Ad_Posting.php?client_id={$row['user_id']}\">{$row['user_id']}</a></td> ".
         "<td align=center width=40%>{$row['mypost']}</td></tr>";
    }else {
      echo "<tr class=\"shaded\"><td align=center><a href=\"../../User_Sections/menu.item.Post_Ads/menu.item.Manual_Ad_Posting.php?client_id={$row['user_id']}\">$clienttopost{$row['user_id']}</a></td> ".
         "<td align=center width=40%>{$row['mypost']}</td></tr>";

    }

$clienttopost="";
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


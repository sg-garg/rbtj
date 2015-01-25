<? 
//LDF SCRIPTS
include("functions.inc.php");

$title='Access Log';
admin_login();
$result=@mysql_query('select value from '.mysql_prefix.'access_log order by time desc');
while ($row=@mysql_fetch_row($result))
echo nl2br($row[0]);
?>
<? footer();

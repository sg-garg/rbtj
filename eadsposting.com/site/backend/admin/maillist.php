<? 
//LDF SCRIPTS
include("functions.inc.php");

$noheader=1;
admin_login();
if (!ini_get('safe_mode'))
set_time_limit(0);
$cur_time=date("Y-m-d H:i");
header("Content-disposition: filename=$mysql_database.emails.dat");
                                        header("Content-type: application/octetstream");
                                        header("Pragma: no-cache");
                                        header("Expires: 0");
$result=@mysql_query("select email from ".mysql_prefix."users where account_type!='canceled'");
while($row=@mysql_fetch_row($result)){
echo $row[0]."\r\n";
}

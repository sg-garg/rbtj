<?
//LDF SCRIPTS
include("functions.inc.php");

$title='URL and Directory Settings';
admin_login();

if ($_POST[sysval]){
reset($_POST[sysval]);
while (list($key, $value) = each($_POST[sysval])){
if(!$value)    
$value=' ';
$value=system_value($key,$value);
@mysql_query("replace into ".mysql_prefix."system_values set name='$key',value='".addslashes(trim($value))."'");
}}?>
<form method=post>
<? echo 'You can support multiple templates in the default pages dir by placing each set of templates in their own sub-directory. For example your default pages dir is '.system_value('pages_dir').' and that has your English pages you can place a Dutch set of templates in the dir '.system_value('pages_dir').'dutch/  then you can place <br>?pages=dutch/ at the end of any link that would call the first  page from the Dutch templates and it will set the needed cookie on the member\'s PC so that they are always taken to the Dutch pages when logging in.
<br><br>
An example of a link that would take a member to Dutch templates (assuming you have them installed)<br>
&lt;a href='.system_value('pages_url').'dutch/index.php?pages=dutch/&gt;Dutch&lt;/a&gt;
<br><Br>
You can place a link on the Dutch templates that would revert a member back to English if they wanted to<br>
&lt;a href='.system_value('pages_url').'index.php?pages=./&gt;English&lt;/a&gt;<br>';
?>
<br><table border=0>
<tr><td align=right>Default Pages Dir: </td><td><input type=text size=80 name=sysval[pages_dir]  value='<? print system_value("pages_dir");?>'></td></tr>   
<tr><td align=right>Default Pages URL: </td><td><input type=text size=80 name=sysval[pages_url] value='<? print system_value("pages_url");?>'></td></tr>
<tr><td colspan=2><hr></td></tr>
<tr><td align=right>Scripts Dir: </td><td><input type=text size=80 name=sysval[scripts_dir]  value='<? print system_value("scripts_dir");?>'></td></tr>
<tr><td align=right>Scripts URL: </td><td><input type=text size=80 name=sysval[scripts_url]  value='<? print system_value("scripts_url");?>'></td></tr>     
<tr><td align=right>Runner URL: </td><td><input type=text size=80 name=sysval[runner_url]  value='<? print system_value("runner_url");?>'></td></tr>
</table>
<input type=submit value='Save Changes'></form>
<? footer();

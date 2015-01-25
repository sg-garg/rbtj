<? 
include("functions.inc.php");

$title='Set Admin Character Set';
admin_login();

if ($_POST['sysval']){
postsysval();
}?>
<form method=post>
<table border=0>
<?
edit_sysval('Default character set for web pages','charset');
edit_sysval('Default character set for emails','emailcharset');
?>
</table><input type=submit value='Save Changes'></form>
<? footer();

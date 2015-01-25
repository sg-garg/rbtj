<?
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='Paid Start Page';
admin_login();

$value=$value*100000;
if ($vtype=='cash'){
$value=$value*admin_cash_factor;}
if ($save==1){
@mysql_query("delete from ".mysql_prefix."paid_clicks where id=$ptcid");
@mysql_query("delete from ".mysql_prefix."ptc_ads where description='#PAID-START-PAGE#'");
@mysql_query("insert into ".mysql_prefix."ptc_ads set html='$html',hrlock='$hrlock',description='#PAID-START-PAGE#',site_url='$site_url',run_type='ongoing',value='$value',vtype='$vtype'");}
echo "Place this code: <b>&lt;? show_start_page_url(); ?&gt;</b> on the page where you wish to display the start page url information for the users<br><br>";
$savemode=1;
$row=@mysql_fetch_array(@mysql_query("select * from ".mysql_prefix."ptc_ads where description='#PAID-START-PAGE#'"));
$row[value]=$row[value]/100000;
if ($row[vtype]=='cash'){
$row[value]=$row[value]/admin_cash_factor;}
?>
<a name="adform"></a><form action="startpage.php" method="POST" name="form">
<input type="hidden" name="save" value="1">
<? if (!$row[html]){$row[html]=system_value('domain')." Paid Start Page";}?>
        <input type=hidden name=ptcid value=<? echo $row[ptcid]?>>
	<table border=0 width=400><tr><th colspan=2><?= $mode;?> Start Page</th></tr>
        <tr><td>Description</td><td><input type=text name=html value='<? echo $row[html];?>'></td></tr>
        <tr><td>Value:</td><td><input type=text name=value value=<?= $row[value];?>>
        </td></tr><tr><td>Value Type:</td><td><input type=radio class=checkbox name=vtype <? if ($row[vtype]=='points'){echo "checked";}?> value=points>Points<br><input type=radio class=checkbox name=vtype <? if ($row[vtype]=='cash'){echo "checked";}?> value=cash>Cash
        </td></tr><tr><td>Hours between each credit</td><td><input type=text name=hrlock value=<?=$row[hrlock];?>>
	</td></tr><tr><td>Site URL:<br>(To place the username in the url put #USERNAME# where you would like it to appear)</td><td><input type=text name=site_url size=40 value='<?=$row[site_url];?>'>
</td></tr><tr><td colspan=2>	<input type="submit" name="add" value="Save">
</form>
<? 
echo "</td></tr></table>";
footer();

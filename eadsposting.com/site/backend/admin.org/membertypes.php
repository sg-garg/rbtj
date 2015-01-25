<?php
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='Membership Types';
admin_login();
$commission_amount=$_POST['commission_amount']*100000;
if ($_POST['save']){
$lowerdesc=strtolower($_POST['description']);
if ($lowerdesc=='free' || $lowerdesc=='canceled' ||$lowerdesc=='suspended' || $lowerdesc=='inactive' ||$lowerdesc=='custom' || $lowerdesc=='advertiser'){
$_POST['save']=0;}}
if ($_POST['save']==2 and $oldid){
@mysql_query("update ".mysql_prefix."users set free_refs='$free_refs',commission_amount='$commission_amount',account_type='".addslashes($description)."' where account_type='".addslashes($oldid)."'");
@mysql_query("update ".mysql_prefix."member_types set description='".addslashes($description)."',commission_amount='$commission_amount',free_refs='$free_refs' where description='".addslashes($oldid)."'");}  
if ($_POST['save']==1){
$searchphrase='';
@mysql_query("insert into ".mysql_prefix."member_types set description='".addslashes($description)."',commission_amount='$commission_amount',free_refs='$free_refs'");}
if ($mode=='Delete'){
@mysql_query("update ".mysql_prefix."users set account_type='' where account_type='".addslashes($description)."'");
@mysql_query("delete from ".mysql_prefix."member_types where description='".addslashes($description)."'");}
echo "
<form method=post>Search membership types: (leave blank to list all membership types) <input type=text name=searchphrase>
<input type=hidden name=get value=search><input type=submit value='Search'><br><a href=membertypes.php#transform target=_top>Create a new membership type</a></form><br>";
if ($get=='search'){$searchphrase="%".$searchphrase."%";}
echo "<table  border=1><tr><th>Description</th><th>Extra Commission (%)</th><th>Free Referrals Priority</th><th>Action</th></tr>";                                                                               
if (!$searchphrase){$searchphrase='*****************************';}
if ($limit){$limit="limit $limit";}
$getads=@mysql_query("select * from ".mysql_prefix."member_types where description like '$searchphrase' or description = '".addslashes($description)."' order by description"); 
while($row=@mysql_fetch_array($getads)){
echo "<form action=membertypes.php#transform method=post><input type=hidden name=searchphrase value='$searchphrase'><input type=hidden name=description value='$row[description]'><tr $bgcolor><td>$row[description]</td><td align=right>".number_format($row['commission_amount']/100000,5)."</td><td align=right>".$row['free_refs']."</td><td><input type=submit name=mode value='Delete'><input type=submit name=mode value='Edit'><input type=submit name=mode value='Copy'></td></tr></form>";
if($bgcolor){$bgcolor='';}else{$bgcolor='bgcolor=#DDDDDD';}
}
echo "</table>";
$count=mysql_num_rows($getads);
if ($searchphrase){echo "<b>".$count." record(s) found</b><br><br>";}
$savemode=1;
$row='';
if ($mode=='Edit' or $mode=='Copy'){
$savemode=2;
if ($mode=='Copy'){
$savemode=1;}
$row=@mysql_fetch_array(@mysql_query("select * from ".mysql_prefix."member_types where description='".addslashes($description)."'"));
}
if (!$mode){$mode='Create New';}
$row['commission_amount']=$row['commission_amount']/100000;
?>
<a name="transform"></a><form method="POST" name="form">
<input type=hidden name=searchphrase value='<?= $searchphrase;?>'>
<input type="hidden" name="save" value="<?=$savemode;?>">
<? if ($savemode==2){?><input type=hidden name=oldid value='<?=$row[description];?>'><? } ?>
	<table  border=0 width=400><tr><th colspan=2><?= $mode;?> Membership Type:</th></tr>
        <tr><td>Description:</td><td><input type="text" name="description" value="<?=$row[description];?>">
	</td></tr><tr><td>Extra Commission (%):</td><td><input type="text" name="commission_amount" value=<?= number_format($row['commission_amount'],5,".","");?>>
</td></tr><tr><td>Free Referrals Priority:</td><td><input type="text" name="free_refs" value=<?= $row['free_refs'];?>>
</td></tr><tr><td colspan=2><input type="submit" name="add" value="Save Membership Type">
</form>
<?echo "</td></tr></table>";
footer();

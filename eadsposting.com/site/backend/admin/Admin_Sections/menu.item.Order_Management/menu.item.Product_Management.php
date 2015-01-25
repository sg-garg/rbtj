<?php
//LDF SCRIPTS
include("../../functions.inc.php");
if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='Product Management';

admin_login();

if ($_POST['save']==2 and $oldid){
	$query="update LDF_Order_System.Products set Vendor_Product_ID='".addslashes($Vendor_Product_ID)."',Vendor='".addslashes($Vendor)."',Category='".addslashes($Category)."',Name='".addslashes($Name)."',Description='".addslashes($Description)."',Image_URL='".addslashes($Image_URL)."',Cost_Price='$Cost_Price',Sale_Price='$Sale_Price' where Product_ID='".addslashes($oldid);
	@mysql_query($query);
}  

if ($_POST['save']==1)
{
	$searchphrase='';
	$query="insert into LDF_Order_System.Products set Vendor_Product_ID='".addslashes($Vendor_Product_ID)."',Vendor='".addslashes($Vendor)."',Category='".addslashes($Category)."',Name='".addslashes($Name)."',Description='".addslashes($Description)."',Image_URL='".addslashes($Image_URL)."',Cost_Price='$Cost_Price',Sale_Price='$Sale_Price'";
	@mysql_query($query);
}

if ($mode=='Delete'){
@mysql_query("delete from LDF_Order_System.Products where Product_ID='".$Product_ID."'");}
echo "<br>
<form method=post>Search Products: (leave blank to list all products) <input type=text name=searchphrase>
<input type=hidden name=get value=search><input type=submit value='Search'><br><a href=#transform target=_top>Create a new product</a></form><br>";
if ($get=='search'){$searchphrase="%".$searchphrase."%";}
echo "<table width=100% border=1><tr><th>Product ID</th><th>Vendor</th><th>Category</th><th>Description</th><th>Cost Price</th><th>Sale Price</th><th>Action</th></tr>";                                                                               
if (!$searchphrase){$searchphrase='*****************************';}
if ($limit){$limit="limit $limit";}
$getads=@mysql_query("select * from LDF_Order_System.Products where Name like '$searchphrase' or Description like '$searchphrase' or Vendor like '$searchphrase' or Vendor_Product_ID like '$searchphrase' or Vendor_Product_ID = '".addslashes($Vendor_Product_ID)."' order by Vendor,Category,Name"); 
while($row=@mysql_fetch_array($getads)){
echo "<form action=#transform method=post><input type=hidden name=searchphrase value='$searchphrase'><input type=hidden name=Product_ID value='$row[Product_ID]'><tr $bgcolor><td align=center>$row[Vendor_Product_ID]</td><td align=center>".$row['Vendor']."</td><td align=center>".$row['Category']."</td><td align=center>$row[Description]</td><td align=right>$row[Cost_Price]</td><td align=right>$row[Sale_Price]</td><td align=center><input type=submit name=mode value='Delete'><input type=submit name=mode value='Edit'><input type=submit name=mode value='Copy'></td></tr></form>";
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
$row=@mysql_fetch_array(@mysql_query("select * from LDF_Order_System.Products where Product_ID='".addslashes($Product_ID)."'"));
}
if (!$mode){$mode='Create New';}

?>
<a name="transform"></a><form method="POST" name="form">
<input type=hidden name=searchphrase value='<?= $searchphrase;?>'>
<input type="hidden" name="save" value="<?=$savemode;?>">
<? if ($savemode==2){?><input type=hidden name=oldid value='<?=$row[Product_ID];?>'><? } ?>
	<table  border=0 width=400><tr><th colspan=2><?= $mode;?> Product:</th></tr>
    <tr><td>Product ID:</td><td><input type="text" name="Vendor_Product_ID" value="<?=$row[Vendor_Product_ID];?>"></td></tr>
	<tr><td>Vendor:</td><td><input type="text" name="Vendor" value=<?= $row['Vendor'];?>></td></tr>
	<tr><td>Category:</td><td><input type="text" name="Category" value="<?=$row[Category];?>"></td></tr>
    <tr><td>Description:</td><td><input type="text" name="Description" value="<?=$row[Description];?>"></td></tr>
    <tr><td>Image URL:</td><td><input type="text" name="Image_URL" value="<?=$row[Image_URL];?>"></td></tr>
    <tr><td>Cost Price:</td><td><input type="text" name="Cost_Price" value="<?=$row[Cost_Price];?>"></td></tr>
    <tr><td>Sale Price:</td><td><input type="text" name="Sale_Price" value="<?=$row[Sale_Price];?>"></td></tr>
<tr><td colspan=2><input type="submit" name="add" value="Save Product">
</form>
<?echo "</td></tr></table>";
footer();

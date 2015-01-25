<?php
include("../../setup.php");
login();
if (user('account_type','return')=='Poster'){
 	if ($_GET['client_id']) {
 	$client_id=$_GET['client_id'];
	 $_SESSION['client_id']=$client_id;	
	}
 	else {
 	$client_id=$_SESSION['client_id'];
	}
	
} elseif(user('account_type','return')=='Client Sales'){
	$client_id = user('owner_id','return');
	
} else {
  $client_id=$_SESSION['username'];
}

include(pages_dir."header.php");
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr> 
<!--td align="left" valign="top" class="account_menu"--> 
<?php 
 /*if (user('account_type','return')=='Poster') {
  include(pages_dir."poster_menu.php");
  } else {
     include(pages_dir."account_menu.php");   
  }*/
?>
<?php

$adid = $_GET['adid'];
$action = $_GET['action'];
     
$posts = @mysql_query("select * from dealer_ads where user_id ='".$client_id."' and ad_id =".$_GET['adid']);
$row = @mysql_fetch_array($posts);
$post_images = @mysql_query("select file_name from post_images a, dealer_ads b where a.ad_id=b.ad_id and b.user_id ='".$client_id."' and b.ad_id =".$_GET['adid']);

?>
<!--/td-->
<td align="left" valign="top"><p>&nbsp;</p>
<form method="POST" action=savepost.php enctype="multipart/form-data" id="adform" onsubmit="return validateData();">
<table width="100%" border="0" cellpadding="0" cellspacing="0" name="Errors">

</table>
<table border=0  width=90% align="center" cellspacing="0" cellpadding="4">
<input type="hidden" name="action" value="<?php echo $action;?>" />
<input type="hidden" name="adid" value="<?php echo $adid;?>" />
<tr>
<td nowrap>&nbsp;</td>
<td nowrap><b>Special Instruction:</b></td>
<td> 
<input type=text name="instruction" id="instruction" size=50 value="<?php echo htmlentities($row['instruction'],ENT_QUOTES|ENT_IGNORE);?>" />
</td>
</tr>
<tr> 
<td>&nbsp;</td>
<td><b>Classified Site URL:</b><span class="error" >*</span> </td>
<td> 
<input type="text" name="classSiteURL" id="classSiteURL" size=50 value="<?php echo htmlentities($row['class_site_url'],ENT_QUOTES|ENT_IGNORE);?>"/> <span class="error" id="classSiteURLError" nowrap="nowrap"></span></td>
</tr>
<tr> 
<td>&nbsp;</td>
<td><b>Posting Region:</b><span class="error" >*</span> </td>
<td> 
<input type="text" name="postingRegion" id="postingRegion" size=50 value="<?php echo htmlentities($row['post_region'],ENT_QUOTES|ENT_IGNORE);?>"/> <span class="error" id="postingRegionError"></span></td>
</tr>
<tr> 
<td>&nbsp;</td>
<td><b>Category:</b><span class="error" >*</span> </td>
<td> 
<input type="text" name="postingSection" id="postingSection" size=50 value="<?php echo htmlentities($row['posting_section'],ENT_QUOTES|ENT_IGNORE);?>"/> <span class="error" id="postingSectionError"></span></td>
</tr><tr> 
<td>&nbsp;</td>
<td><b>Priority:</b></td>
<td> 
<select name="priority">
<?php
for ($i=10;$i>0;$i--){

   if($row['priority']==$i)
     echo "<option value='$i' selected>";
     else
     echo "<option value='$i'>";

echo "$i</option>";
}
?>
</select>&nbsp;&nbsp;&nbsp;(10 is the highest priority and 1 is lowest.)</td>
</tr>
<tr> 
<td>&nbsp;</td>
<td><b>Ad Group:</b></td>
<td> 
<select name="adGroup">
<?php
for ($i=0;$i<=10;$i++){

   if($row['ad_group']==$i)
     echo "<option value='$i' selected>";
   else
     echo "<option value='$i'>";
   if($i == 0){
    echo "N/A</option>";
   } else {
    echo "$i</option>";
   }
}
?>
</td>
</tr>
<tr> 
<td>&nbsp;</td>
<td><b>Title:</b><span class="error" >*</span></td>
<td><input type="text" width="501" name="title" id="title" size=50 value="<?php echo htmlentities($row['title'],ENT_QUOTES|ENT_IGNORE);?>" /><span class="error" id="titleError"></span></td>
</tr><tr> 
<td>&nbsp;</td>
<td><b>Price:</b></td>
<td> 
<input type="text" name="price" id="price" value="<?php echo htmlentities($row['price'],ENT_QUOTES|ENT_IGNORE);?>" /><span class="error" id="priceError"></span></td>
</tr>
<tr> 
<td>&nbsp;</td>
<td><b>Location:</b></td>
<td> 
<input type="text" name="location" size=50 value="<?php echo htmlentities($row['location'],ENT_QUOTES|ENT_IGNORE);?>" /></td>
</tr>
<tr> 
<td>&nbsp;</td>
<td><b>Ad Body:</b><span class="error" >*</span></td>
<td> 
<textarea name="adBody" id="adBody" cols="50" rows="10"><?php echo htmlentities($row['ad_body'],ENT_QUOTES|ENT_IGNORE);?></textarea><span class="error" id="adBodyError"></span></td>
</tr>
<tr> 
<td>&nbsp;</td>
<td><b>Street:</b></td>
<td> 
<input type="text" name="addressLine1" size=50 value="<?php echo htmlentities($row['address_line1'],ENT_QUOTES|ENT_IGNORE);?>" /></td>
</tr>
<tr> 
<td>&nbsp;</td>
<td><b>Cross Street:</b></td>
<td> 
<input type="text" name="addressLine2" size=50 value="<?php echo htmlentities($row['address_line2'],ENT_QUOTES|ENT_IGNORE);?>" /></td>
</tr>
<tr>
<td>&nbsp;</td>
<td><b>City:</b></td>
<td>
<input type="text" name="city" size=50 value="<?php echo htmlentities($row['city'],ENT_QUOTES|ENT_IGNORE);?>" /> 
</td>
</tr>
<tr>
<td>&nbsp;</td>
<td><b>State:</b></td>
<td> 
<!--select name="state">
<option value=''>--Select State--</option>
<?php new_member_states(); ?>
<?php if($row['state'] != null){ ?>
<option value='<?php echo $row['state'];?>' selected><?php echo $row['state'];?></option>
<? }?>
</select-->
<input type="text" name="state" size=3 value="<?php echo htmlentities($row['state'],ENT_QUOTES|ENT_IGNORE);?>" maxlength=2 /> 
</td>
</tr><tr>
<td>&nbsp;</td>
<td><b>Zip Code:</b><span class="error" >*</span></td>
<td> 
<input type="text" name="zipCode" id="zipCode" size=50 value="<?php echo htmlentities($row['zip'],ENT_QUOTES|ENT_IGNORE);?>" /><span class="error" id="zipCodeError"></span></td>
</tr><tr>
<td>&nbsp;</td>
<td><b>Phone:</b></td>
<td> 
<input type="text" name="phone" size=50 value="<?php echo htmlentities($row['phone'],ENT_QUOTES|ENT_IGNORE);?>" /></td>
</tr>
<tr>
<td colspan=3>&nbsp;</td>
<tr> 
<td colspan="3"><hr size="1"></td>
</tr><tr> 
<td nowrap>&nbsp;</td>
<td nowrap><b>Uploaded Files:</b></td>
<td> <table><tr><?php 
$imagetr ="<tr>";
while ($imageRow = @mysql_fetch_array($post_images))
{   
   $count++;
  //$imagepath = "http://myaccount.ldfdealer.com".$imageRow['file_name'];
  $filename = $imageRow['file_name'];
  $imageUrl = pages_url."User_Sections/menu.item.Ad_Capaigns/delete_ad.php?action=remove&adid=".$adid."&image_name=".$filename;
  if($action != 'copy'){
    $imagetr = $imagetr."<td><a href=".$imageUrl.">Remove</a></td>";
  }
?>
<td><img height=100 width=90 src="<?php echo pages_url."upload_images/".$imageRow['file_name']; ?>"></td>
<?php 
 if($count>=5){ 
   $count = 0;
   if($action != 'copy'){
       echo $imagetr."</tr>"; 
	$imagetr ="<tr>";
	
      }
      echo "</tr><tr>";
   }
}
echo "</tr>";
$imagetr = $imagetr."</tr>";?>
<?php  if($action != 'copy'){
    echo $imagetr; } ?>

</table>
</td>
</tr>

<tr> 
<td nowrap>&nbsp;</td>
<td nowrap><b>Upload Files:</b></td>
<td> 
<div id='dvFile'><input type="file" id="uploadedfiles" name="files[]" multiple/>
</div>
<img height=10 width=10 src="/images/add.png">
<a href="#" onclick="add_more(); return false;">Add another file</a>
</td>
</tr> 
<tr>
<td colspan=3><span class="error" id="imageErrors" align="center"></span></td>
</tr>
<tr>
<td colspan=3><hr></td>
</tr><tr> 
<td colspan=3 align=center>To save your changes,click 'Save Changes'<br>

<br>
<input type="submit" value="Save Changes"/>
</td>
</tr>
</table>
</form>
<br></td>
</tr>
</table>

<?php include(pages_dir."footer.php");?>


<?php
include("../../setup.php");
login();
if (user('account_type','return')=='Poster'){
 	if ($_GET['client_id']) {
 	    $client_id= $_GET['client_id'];
	    $_SESSION['client_id']= $client_id;	
	}
 	else {
 	    $client_id=$_SESSION['client_id'];
	}
} elseif(user('account_type','return')=='Client Sales'){
	$client_id = user('owner_id','return');
	
} else {
  $client_id=$_SESSION['username'];
}

$ad_id=$_GET['adid'];
$action = $_GET['action'];
$flag = 1;

if($action == 'delete'){
   $deleteAd = 1;
   $post_images = @mysql_query("select file_name,file_type,file_size from post_images a, dealer_ads b where a.ad_id=b.ad_id and b.user_id ='".$client_id."' and b.ad_id =".$_GET['adid']);
   while ($imageRow = @mysql_fetch_row($post_images)){
         $imagepath = pages_dir."upload_images/".$imageRow[0];
         @unlink($imagepath);
         $deleteAd = 0;
   }
   @mysql_query("delete from post_images where ad_id=".$_GET['adid']);
   @mysql_query("delete from dealer_ads where ad_id=".$_GET['adid']);

}
if($action == 'remove'){
   $post_images = @mysql_query("select file_name,file_type,file_size from post_images a, dealer_ads b where a.ad_id=b.ad_id and b.user_id ='".$client_id."' and b.ad_id =".$_GET['adid']);
   while ($imageRow = @mysql_fetch_row($post_images)){
         if(strpos($imageRow[0],$_GET['image_name']) !== false){
        	 $imagepath = pages_dir."upload_images/".$imageRow[0];
         	@unlink($imagepath);
         	$deleteAd = 0;
         	@mysql_query("delete from post_images where ad_id=".$_GET['adid']." and file_name='".$imageRow[0]."'");
         }
      }
   
  }

if($flag && $action =='delete'){
include('menu.item.List_Campaigns.php');
}else if($flag && $action =='remove'){
  $action ="edit";
  $_GET['action'] ="edit";
  $adid = $ad_id; 
 include('menu.item.Create_Ad.php');
}else {
echo "ERROR";
}
?>

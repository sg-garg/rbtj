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
$current_user=$_SESSION['username'];

$postSec = addslashes($_POST['postingSection']);
$priority= addslashes($_POST['priority']);
$adGroup= addslashes($_POST['adGroup']);
$title = addslashes($_POST['title']);
$price = $_POST['price'];
$location= addslashes($_POST['location']);
$adBody = addslashes($_POST['adBody']);
$addrline1= addslashes($_POST['addressLine1']);
$addrline2 = addslashes($_POST['addressLine2']);
$city = addslashes($_POST['city']);
$state = addslashes($_POST['state']);
$zip = addslashes($_POST['zipCode']);
$phone = addslashes($_POST['phone']);
$instruction= addslashes($_POST['instruction']);
$action = $_POST['action'];
$adid = $_POST['adid'];
//$user_id =$_SESSION[username];
$user_id =$client_id;
$classSiteURL = addslashes($_POST['classSiteURL']);
$postingRegion = addslashes($_POST['postingRegion']);
if(empty($price)){
 $price = 0;
}

$sql = "insert into dealer_ads(posting_section,priority,title,price,location,ad_body,address_line1,address_line2,city,state,zip,phone,instruction,user_id,creation_date,class_site_url,post_region,ad_group,created_by,updated_by) values('$postSec',$priority,'$title',$price,'$location','$adBody','$addrline1','$addrline2','$city','$state','$zip','$phone','$instruction','$user_id',now(),'$classSiteURL','$postingRegion',$adGroup,'$current_user','$current_user')";
if($action == 'edit'){
 $sql = "update dealer_ads set posting_section='".addslashes($postSec)."',priority=".$priority.",title='".$title."',price=".$price.",location='".$location."',ad_body='".$adBody."',address_line1='".$addrline1."',address_line2='".$addrline2."',city='".$city."',state='".$state."',zip='".$zip."',phone='".$phone."',instruction='".$instruction."',class_site_url='".$classSiteURL."',post_region='".$postingRegion."',ad_group=".$adGroup.",updated_by='".$current_user."'  where user_id ='".$client_id."' and ad_id =".$_POST['adid'];  
}
$result = @mysql_query($sql);
$ad_id=0;
$flag = 1;
$count = 1;
if($action != 'edit'){
 $ad_id = mysql_insert_id();
 if($action == 'copy'){
   $post_images = @mysql_query("select file_name,file_type,file_size from post_images a, dealer_ads b where a.ad_id=b.ad_id and b.user_id ='".$client_id."' and b.ad_id =".$_POST['adid']);
   // $newPath ="/home/ldfdealer/public_html/images/";
   $folderName = "upload_images/";
   $newPath = pages_dir.$folderName;
  

   $copyImageFlag = 0;
   while ($imageRow = @mysql_fetch_row($post_images)){
         $imagepath = pages_dir.$folderName.$imageRow[0];
         $newPath = $newPath.$ad_id."_".$count.".".$imageRow[1];
         $imagePathInDB = $ad_id."_".$count.".".$imageRow[1];
         $query="INSERT into post_images (ad_id,file_name,file_size,file_type) VALUES($ad_id,'$imagePathInDB','$imageRow[2]','$imageRow[1]'); ";

         if(!copy($imagepath,$newPath)){
             echo "error while copying";
             $flag = 0;
          }
         mysql_query($query);
        $count++;
	
       $newPath = pages_dir.$folderName;
       $copyImageFlag = 1;
     }
    if($copyImageFlag){
      $count--;
      @mysql_query("update dealer_ads set image_count=".$count." where user_id ='".$client_id."' and ad_id =".$ad_id);
      $count++; //if apart from the copied images,user added the new images then this will give the count value	for the next image
     }
  }
}
if($action == 'edit'){
   $ad_id = $adid;
   $imageResult = @mysql_query("select image_count from  dealer_ads b where  b.user_id ='".$client_id."' and b.ad_id =".$_POST['adid']);
   if($data = @mysql_fetch_row($imageResult)){
      $count  = $data[0];
      $count++; //get the id for the next image
    }
}


if(isset($_FILES['files'])){
 if(count($_FILES['files']['name'])>0){ 
   $errors= array();
    $imageInserted = 0;  
	foreach($_FILES['files']['tmp_name'] as $key => $tmp_name ){
		$file_name = $key.$_FILES['files']['name'][$key];
		$file_size =$_FILES['files']['size'][$key];
		$file_tmp =$_FILES['files']['tmp_name'][$key];
		$file_type=$_FILES['files']['type'][$key];	
        if($file_size > 2097152){
	   $errors[]='File size must be less than 2 MB';
        }		
        
      // $desired_dir="/home/ldfdealer/public_html/images/";
       $folderName = "upload_images/";
       $desired_dir = pages_dir.$folderName;
      	$fileType = substr($file_type,6);
       $desiredFileName =$desired_dir.$ad_id."_".$count.".".$fileType;
       $imageFileNameInDB = $ad_id."_".$count.".".$fileType;
       $query="INSERT into post_images (ad_id,file_name,file_size,file_type) VALUES($ad_id,'$imageFileNameInDB','$file_size','$fileType'); ";
        if(empty($errors)==true){
            if(is_dir($desired_dir)==false){
                mkdir("$desired_dir", 0700);		// Create directory if it does not exist
            }
            if(is_dir("$desired_dir/".$file_name)==false){
                move_uploaded_file($file_tmp,$desiredFileName);
            }else{									//rename the file if another one exist
                $new_dir=$desiredFileName.$file_name.time();
                 rename($file_tmp,$new_dir) ;				
            }
         if($file_size>0){ 
          mysql_query($query);
          $imageInserted = 1;
         }			
        }else{
           print_r($errors);
        }
         $count++;
    }
   if($imageInserted){ 
      $count--;
      @mysql_query("update dealer_ads set image_count=".$count." where user_id ='".$client_id."' and ad_id =".$ad_id);	
   }
    if(empty($error)){
		//echo "Success";
	}
 }
}
if($result && $flag){
include('menu.item.List_Campaigns.php');
}else {
echo "ERROR";
}
?>

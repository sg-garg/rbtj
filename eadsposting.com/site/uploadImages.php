<?php
if(isset($_FILES['files'])){
    $errors= array();
       $count = 1;
	foreach($_FILES['files']['tmp_name'] as $key => $tmp_name ){
		$file_name = $key.$_FILES['files']['name'][$key];
		$file_size =$_FILES['files']['size'][$key];
		$file_tmp =$_FILES['files']['tmp_name'][$key];
		$file_type=$_FILES['files']['type'][$key];	
        if($file_size > 2097152){
	   $errors[]='File size must be less than 2 MB';
        }		
        //$query="INSERT into upload_data (`USER_ID`,`FILE_NAME`,`FILE_SIZE`,`FILE_TYPE`) VALUES('$user_id','$file_name','$file_size','$file_type'); ";
        $desired_dir="/home/ldfdealer/public_html/images/";
	 echo "File type".$count." is".$file_type;
        $desiredFileName =$desired_dir.$count.".png";
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
          //  mysql_query($query);			
        }else{
                print_r($errors);
        }
         $count++;
    }
	if(empty($error)){
		echo "Success";
	}
}
?>


<form action="" method="POST" enctype="multipart/form-data">
 MY form	
  <input type="file" name="files[]" multiple/>
	<input type="submit" value="submit"/>
</form>
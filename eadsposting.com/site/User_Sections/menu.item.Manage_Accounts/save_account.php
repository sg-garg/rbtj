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
} else {
  $client_id=$_SESSION['username'];
}

$account_username = addslashes($_POST['username_account']);
$account_password = addslashes($_POST['password_account']);
$email_password = addslashes($_POST['email_password']);
$security_email = addslashes($_POST['security_email']);
$security_phone = addslashes($_POST['security_phone']);
$email_dob = addslashes($_POST['email_dob']);
$user_id = addslashes($_POST['user_id']);
$isActive = addslashes($_POST['isActive']);


$client_id =$_SESSION['username'];
$mode = $_GET['mode'];

if(empty($mode)){
 $mode = $_POST['mode'];
 
}

$where='client_id="'.$client_id.'"';
if (user('account_type','return')=='Poster'){
$where='client_id="Poster_Accounts"';
$client_id='Poster_Accounts';
}
$duplicateAccount =0;



if($mode =='edit'){
  $key = $_POST['key']; 
  $sqlduplicateuser ="select * from posting_accounts where id<>$key and Username='".$account_username."'";
   $accountDetails = @mysql_query($sqlduplicateuser);
   //$row = @mysql_fetch_array($accountDetails);
  if(@mysql_num_rows($accountDetails)) {
     $duplicateAccount =1 ;
  }
    
	$sql ="update posting_accounts set Password = '$account_password', Username ='$account_username', email_password='$email_password', user_id ='$user_id', info='$isActive', security_email = '$security_email', security_phone= '$security_phone', dob = '$email_dob' where  id=$key and $where";
	//echo $sql;
} else if($mode =='delete'){
  $key = $_GET['key'];
  $sql ="delete from posting_accounts  where id =$key and $where";
}else if($mode =='disable'){
  $key = $_GET['key'];
  $sql ="update posting_accounts set Info = 'N'  where id =$key and $where";
}else if($mode =='enable'){
  $key = $_GET['key'];
  $sql ="update posting_accounts set Info = 'Y'  where id =$key and $where";
} else {
   $key = $_POST['key']; 
   $sqlduplicateuser ="select * from posting_accounts where Username='".$account_username."'";
   $accountDetails = @mysql_query($sqlduplicateuser);
   //$row = @mysql_fetch_array($accountDetails);
   if(@mysql_num_rows($accountDetails)) {
     $duplicateAccount =1 ;
   }
	
   $sql ="insert into posting_accounts(Username,Password,Renew_Time,client_id,info,email_password,user_id,security_email,security_phone,dob) values('$account_username','$account_password',now(),'$client_id','$isActive','$email_password','$user_id','$security_email','$security_phone','$email_dob')"; 
   //echo $sql;
}
//echo $sql;
if($duplicateAccount){
$showMessage ="Account already exist with the name <b>".$account_username."</b>";
$selectedemailUser = $account_username;
$selectedMode = "";
if($mode =='edit'){
  $selectedMode = 'edit';
}
include('menu.item.Create_Account.php');
}else {
 $result = @mysql_query($sql);  
 include('menu.item.List_Accounts.php');
}
?>

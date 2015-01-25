<?php
include("../../setup.php");
login();
/*if (user('account_type','return')=='Poster'){
 	if ($_GET['client_id']) {
 	$client_id=$_GET['client_id'];
	 $_SESSION['client_id']=$client_id;	
	}
 	else {
 	$client_id=$_SESSION['client_id'];
	}
} else {
  $client_id=$_SESSION['username'];
}*/

$client_id=$_SESSION['username'];

$account_username = addslashes($_POST['username_account']);
$account_password = addslashes($_POST['password_account']);
$email_account = $_POST['email_account'];
$client_id =$_SESSION['username'];
$mode = $_GET['mode'];
if(empty($mode)){
 $mode = $_POST['mode'];
}

$where="owner_id='".$client_id."' and account_type ='Client Poster'";

//$account_username = strtolower(substr(preg_replace('([^a-zA-Z0-9])', '', $account_username), 0, 16);

$account_password = password($account_password);
$duplicateAccount =0;
if($mode =='edit'){
  if(!empty($account_password)) {
    $sql1 ="update users set password = '$account_password' where password <> $account_password and username ='$account_username' and $where";
    @mysql_query($sql1);
   }
  if(!empty($account_password)) {
    $sql2 ="select * from users where username <> '".$account_username."' and email ='".$email_account."'";
    $validateemail = @mysql_query($sql2);
    if(@mysql_num_rows($validateemail)) {
       $duplicateAccount = 2 ; //if email is already exist.
     }
   }

 $sql ="update users set email = '$email_account' where username ='$account_username' and $where";
 
} else if($mode =='delete'){
  $key = $_GET['key'];
  $sql ="delete from users  where username ='".$key."' and $where";
}else if($mode =='disable'){
  $key = $_GET['key'];
  $sql ="update users set account_type = 'canceled' where username ='$key' and $where";
} else if($mode =='enable'){
  $key = $_GET['key'];
  $sql ="update users set account_type ='Client Poster' where username ='$key' and owner_id ='$client_id'";
} else {
   $sqlduplicateuser ="select * from users where username='".$account_username."' or email ='".$email_account."'";
   $accountDetails = @mysql_query($sqlduplicateuser);
   $row = @mysql_fetch_array($accountDetails);
   if(@mysql_num_rows($accountDetails)) {
       if($row['username'] == $account_username){
         $duplicateAccount = 1 ;
       }else {
          $duplicateAccount = 2 ; //if email is already exist.
       }
  
   }
   $sql ="insert into users(username,password,account_type,owner_id,email) values('$account_username','$account_password','Client Poster','$client_id','$email_account')"; 
}
if($duplicateAccount){
   if($duplicateAccount == 1){
     $showMessage ="Account already exist with the name <b>".$account_username."</b>";
   } else {
     $showMessage ="Account already exist with the email <b>".$email_account."</b>";
   }
    //if error occurs in edit mode then pass the same mode
    $selectedmode ="";
    if($mode =='edit') {
     $selectedmode = 'edit';
     }
   $selectedactuser = $account_username;
   $selectedemailid = $email_account;
  include('menu.item.Create_Account.php');
}else {
 
 $result = @mysql_query($sql);
 include('menu.item.List_Accounts.php');
}
?>

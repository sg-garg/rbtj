<?php
// Include any startup scripts
include("../../setup.php");
// Process Login
login();
// Show the page header
//below code is used to verify that from where this page is being accessed.(Ad Campaign or Poster Access)

if (user('account_type','return')=='Poster'){      
 	if ($_GET['client_id']) {
 	 $client_id=$_GET['client_id'];
	 $_SESSION['client_id']=$client_id;	
	}
 	else {
 	$client_id=$_SESSION['client_id'];
	}
} else if (user('account_type','return')=='Client Poster' || user('account_type','return')=='Client Sales' ){
      $client_id = user('owner_id','return');
} else {
  $client_id=$_SESSION['username'];
}

include(pages_dir."header.php");
// Include the account menu you see on the left when logged in
?><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<!--td align="left" valign="top" class="account_menu"-->

<!--/td--><td align="right"><table width=100%><tr><Td><SCRIPT LANGUAGE="JavaScript">

<!-- This script and many more are available free online at -->
<!-- The JavaScript Source!! http://javascript.internet.com -->
<!-- Original:  Russ (NewXS3@aol.com) -->
<!-- Web Site:  http://dblast.cjb.net -->

<!-- Begin
function copyit(theField) {
//alert(theField);
var tempval=eval("document."+theField);

tempval.focus();
tempval.select();
therange=tempval.createTextRange();
therange.execCommand("Copy");
}
function showComment(fieldvalue) {

  if(fieldvalue.value == 'N'){
    document.getElementById('commentId1').style.display = 'block';
  } else {
   document.getElementById('commentId1').style.display = 'none';
  }
  
}
//  End -->
</script>



    <style type="text/css">
      body {
        color:black;
        padding:0px;
        font-size:12px;
        font-family: Arial;
        background:none;
        border:none;
        display:block;
              } 
       td { font-size:12px;
            color:black;
           }

       th { font-size:12px; 
            color:blue;} 
      .toppaddig{
       padding-top:2px;
       }         
    </style>
<?
$confURL = $_POST['confirmation_url'];
$postIdNew = $_POST['postId'];
$logadId =  $_POST['logadid'];
$loggedInUser = $_SESSION['username']; 
$user_id = $client_id;
$accountused =$_POST['accountuser'];
//client_id is the dealer username and username in session is logged in user
if($postIdNew != null) {
  if (!empty($confURL)){
     //$confURL="Ad Skipped";
  mysql_query("insert into log_post(user_id,ad_id,post_id,confirmation_url,posted_by,posting_account,first_creation_date) values('$user_id',$logadId,'$postIdNew','$confURL','$loggedInUser','$accountused',now())");
 } 
 $postIdNew = null;
  if (user('account_type','return')=='Poster'){
      
       $maxCount = $_SESSION['ad_post_count'];
       if(empty($maxCount)){
         $maxCount = 0;
       }
       if($maxCount > 3 ){
         echo '<script type="text/javascript">location.href = "/site/Poster_Sections/menu.item.Posters/menu.item.Clients.php";</script>';
        }
        $maxCount++;
        $_SESSION['ad_post_count']=$maxCount;
       
  }
}	




//$dealer= mysql_query("select * from dealer_ads where  priority > priority_count and user_id ='".$client_id."' and updated<now()-interval 12 hour order by updated desc limit 1");	
$dealer= mysql_query("select * from dealer_ads where user_id ='".$client_id."' order by last_post asc limit 1");	
$rowSelected =mysql_num_rows($dealer);
$dealer_res=mysql_fetch_array($dealer);
$isPostRowExist = 1;
if(!$rowSelected){
  $totalRows = mysql_query("select count(*) from dealer_ads where user_id ='".$client_id."'");	
  if(mysql_num_rows($totalRows)){
      //$isPostRowExist = 1;
      mysql_query("update dealer_ads set priority_count=0 where user_id ='".$client_id."'");
      //$dealer= mysql_query("select * from dealer_ads where  priority > priority_count and user_id ='".$client_id."' order by updated asc limit 1");	
      $dealer= mysql_query("select * from dealer_ads where  user_id ='".$client_id."' order by last_post asc limit 1");	
	  $dealer_res=mysql_fetch_array($dealer);
  }else {
        $isPostRowExist = 0;
        echo "Sorry no Ads found to post.";
   }
}
$client_ip = $loggedInUser.':'.$_SERVER['REMOTE_ADDR'];
//if (user('account_type','return')=='Poster' || user('account_type','return')=='Client Poster' || $isClientLoggedIn){
/*if user account is not working the put message in info field */
 if(!empty($_POST['accountuser'])) {
   $selectedActUser = $_POST['accountuser'];
   $selectedActPwd = $_POST['accountpwd'];
   $activeActUsr = $_POST['activeActUser'];
   $commentMsg = $_POST['comment'];

  mysql_query("update posting_accounts set info='$activeActUsr',message='$commentMsg' where Username='$selectedActUser'");
 
  }
/* End here*/
$account_user="";
$account_password ="";
$email_password ="";
$user_id = $_SESSION['client_id'];
$POSTERIP = $user_id.":".$_SERVER['REMOTE_ADDR'];
/*if($user_id == 'localdiscount'){
	$user_id = 'capitolcrew';
}*/

if(empty($_SESSION['POSTER_IP'])  && $_SESSION['POSTER_IP'] != $POSTERIP && (user('account_type','return') == 'Poster')){
	$_SESSION['POSTER_IP'] = $POSTERIP;
	$_SESSION['poster_account'] = '';
}
if(empty($_SESSION['poster_account'])){
	$where = " and client_id='Poster_Accounts' and user_id = '$user_id' order by last_used asc limit 1";
	if(user('account_type','return')!='Poster'){
	 $where = " and ip='$client_ip' and client_id= '$client_id' and last_used>now()-interval 15 minute";
	}
	//echo "select * from posting_accounts where isActive = 1 and info='Y' ".$where;
	$showaccoutDetails = mysql_query("select * from posting_accounts where isActive = 1 and info='Y' ".$where);
	if(!mysql_num_rows($showaccoutDetails)){
		 $where = " and client_id='Poster_Accounts' and user_id = '$user_id' order by last_used asc limit 1";
		if(user('account_type','return')!='Poster'){
		  $where = " and client_id= '$client_id' order by last_used asc limit 1";
		}
	  //echo "select * from posting_accounts where isActive = 1 and info='Y' order by last_used asc limit 1";
	   $showaccoutDetails = mysql_query("select * from posting_accounts where isActive = 1 and info='Y' ".$where); 
	  //$showaccoutDetails = mysql_query("select * from posting_accounts where isActive = 1 and info='Y' order by last_used asc limit 1"); 
	}
	$row_data =mysql_fetch_array($showaccoutDetails);
	$account_user = $row_data['Username'];
	$account_password = $row_data['Password'];
	$email_password = $row_data['email_password'];
	$_SESSION['poster_account'] = $row_data['Username'];
} else{

	$poster_account = $_SESSION['poster_account'];
	$showaccoutDetails = mysql_query("select * from posting_accounts where Username = '$poster_account'");
	$row_data =mysql_fetch_array($showaccoutDetails);
	$account_user = $row_data['Username'];
	$account_password = $row_data['Password'];
	$email_password = $row_data['email_password'];
}

$where = " and ip='$client_ip' and client_id='Poster_Accounts' and last_client_id='$client_id' and user_id = '$user_id' and last_used > now()-interval 15 minute";
if(user('account_type','return')!='Poster'){
 $where = " and ip='$client_ip' and client_id= '$client_id' and last_used>now()-interval 15 minute";
}
//echo "select * from posting_accounts where isActive = 1 and info='Y' ".$where;
$showaccoutDetails = mysql_query("select * from posting_accounts where isActive = 1 and info='Y' ".$where);
if(!mysql_num_rows($showaccoutDetails)){
     $where = " and client_id='Poster_Accounts' and user_id = '$user_id' order by last_used asc limit 1";
    if(user('account_type','return')!='Poster'){
      $where = " and client_id= '$client_id' order by last_used asc limit 1";
    }
  //echo "select * from posting_accounts where isActive = 1 and info='Y' order by last_used asc limit 1";
   $showaccoutDetails = mysql_query("select * from posting_accounts where isActive = 1 and info='Y' ".$where); 
  //$showaccoutDetails = mysql_query("select * from posting_accounts where isActive = 1 and info='Y' order by last_used asc limit 1"); 
}
$row_data =mysql_fetch_array($showaccoutDetails);
$account_user = $row_data['Username'];
$account_password = $row_data['Password'];
$email_password = $row_data['email_password'];
//To highlight the user name and password if user changes
$showcolor ="style=color:black";
$showlabelcolor = "style=color:blue;text-align:right";
 if(!empty($_SESSION['posting_accountuser'])) {
   $posting_accountuser = $_SESSION['posting_accountuser'];
   if($posting_accountuser != $account_user){
      $showcolor ="style=color:red";
      $showlabelcolor = "style=color:red;text-align:right";
       $_SESSION['posting_accountuser'] = $account_user;
    }
 }else if(!empty($account_user)) {
  $_SESSION['posting_accountuser'] = $account_user;
  $showcolor ="style=color:red";
  $showlabelcolor = "style=color:red;text-align:right";
 }
//end here
	if(!empty($account_user)){
		mysql_query("update posting_accounts set last_used=now(),renew_time = now()+interval 1 hour,ip='$client_ip',last_client_id='$client_id' where Username='$account_user'");
	}
//}
if($isPostRowExist){
if ($dealer_res[ad_group]>0) {
$ad_group_ad=@mysql_fetch_array(@mysql_query("select * from dealer_ads where ad_body!='".addslashes($dealer_res[ad_body])."' and ad_group='$dealer_res[ad_group]' and ad_id!=$dealer_res[ad_id] and user_id ='$client_id' order by rand() limit 1"));
if ($ad_group_ad[price]>0)
$ad_group_ad[price]="\$$ad_group_ad[price]";
else 
$ad_group_ad[price]="";
$dealer_res[ad_body].="\n\n\n\n$ad_group_ad[title]  $ad_group_ad[price]\n\n$ad_group_ad[ad_body]";
}
$post_id=substr(md5($dealer_res[user_id]),0,8).time();
$randomid = $dealer_res[user_id].date("md")." ".$post_id;
$randomarray2[0]= $randomid;
$stoppoint=rand(30,60);
$random=$dealer_res[title]." ".$dealer_res[ad_body]." ".$dealer_res[location];
$random=str_replace("\n"," ",$random);
$randomarray=explode(" ",$random);
$random="";
shuffle($randomarray);
for ($i=0;$i<count($randomarray);$i++){
if (trim($randomarray[$i]))
$randomarray2[$i+1]=trim($randomarray[$i]);
if ($i>$stoppoint)
break;
}
shuffle($randomarray2);
$random=join(" ",$randomarray2);
//$body=$dealer_res[ad_body]."\n\n\n\n".$dealer_res[phone]."\n".$dealer_res[address_line2]."\n".$dealer_res[city]." ".$dealer_res[state]." ".$dealer_res[zip]." \n\n\n\n\n indsg-$dealer_res[user_id]";     //$random"; removed writting extra footer
$body=$dealer_res[ad_body];

/*
$version=rand(1,5);
$phone_str = ''; 

if ($version==1){
$phone_str  = "\nPlease call me on - ". chunk_split($dealer_res[phone], 1, '  ');;

}
if ($version==2){
$phone_str  ="\nPlease call me on - ".  chunk_split($dealer_res[phone], 2, ' ** ');

}

if ($version==3){
$phone_str  ="\ncall or text ". chunk_split($dealer_res[phone], 2, ' *#') .'------------';
}

if ($version==4){
$phone_str  ="\nPlease call or text ". chunk_split($dealer_res[phone], 2, '  ') .'  %%%';
}

if ($version==5){
$phone_str  ="\nContact Us -- ". chunk_split($dealer_res[phone], 2, '### ') .'---';
}

//$body = $body . $phone_str;
*/
$version=rand(1,20);
if ($version==1){
$dealer_res[title] = str_replace(' ', '__', $dealer_res[title]);

}
if ($version==2){
$dealer_res[title] = str_replace(' ', '_', $dealer_res[title]);

}
if ($version==3){
$dealer_res[title] = "***".$dealer_res[title]."***";

}
if ($version==4){
$body = $dealer_res[title]."\n\n".$body;
$dealer_res[title] = "%%%".$dealer_res[title]."####";

}
if ($version==5){
$body = $dealer_res[title]."\n\n".$body;
$dealer_res[title] =  ">>".$dealer_res[title]."***";

}
if ($version==6){
$dealer_res[title] = ">->->".$dealer_res[title]."$$$";
$body = $dealer_res[title]."\n\n".$body;
}
if ($version==7){
$body = $dealer_res[title]."\n\n".$body;
$dealer_res[title] = "##-->".$dealer_res[title]."######";

}
if ($version==8){
$body = $dealer_res[title]."\n\n".$body;
$dealer_res[title] = "--->".$dealer_res[title]."@@";

}
if ($version==9){
$body = $dealer_res[title]."\n\n".$body;
$dealer_res[title] = "###-->".$dealer_res[title]."$$$!!!!!!!!";

}
if ($version==10){
$dealer_res[title] = "@@@".$dealer_res[title]."###!!!";

}
if ($version==11){
$dealer_res[title] ="--->". $dealer_res[title]."~~~~~~~~";

}
if ($version==12){
$dealer_res[title] ="--->". $dealer_res[title]."~~~~~~~~";

}
if ($version==13){
$dealer_res[title] = $dealer_res[title]."...............";
}
if ($version==14){
$dealer_res[title] = $dealer_res[title].">>>>>";
}
if ($version==15){
$dealer_res[title] = "@$".$dealer_res[title]."---------";
}
if ($version==16){
$dealer_res[title] = "@@".$dealer_res[title]."***######";
}
if ($version==17){
$dealer_res[title] = "%%^^".$dealer_res[title]."++++++";
}

if ($version==18){
$dealer_res[title] = "^^^^".$dealer_res[title]."@@@@@@@";
}
if ($version==19){
$dealer_res[title] = $dealer_res[title].":::::::";
}
if ($version==20){
$dealer_res[title] = "!!!!".$dealer_res[title]."^^^^^^^^^^";
}
$version=rand(0,5);
if ($version==1){
$body=ucwords($body);
$dealer_res[title]=ucwords($dealer_res[title]);
$dealer_res[location]=ucwords($dealer_res[location]);
}
if ($version==2){
$body=ucwords(strtolower($body));
$dealer_res[title]=ucwords(strtolower($dealer_res[title]));
$dealer_res[location]=ucwords(strtolower($dealer_res[location]));
}
if ($version==3){
$body=strtolower($body);
$dealer_res[title]=strtolower($dealer_res[title]);
$dealer_res[location]=strtolower($dealer_res[location]);
}
    function ucsentence($string){
         $sentences = preg_split('/([.?!\n]+)/', $string, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);  
    $new_string = '';  
    foreach ($sentences as $key => $sentence) {  
        $new_string .= ($key & 1) == 0?  
            ucfirst(strtolower(trim($sentence))) :  
            $sentence.' ';  
    }  
    return trim($new_string);   
      
    }  
if ($version==4){
$body=ucsentence($body);
$dealer_res[title]=ucsentence($dealer_res[title]);
$dealer_res[location]=ucsentence($dealer_res[location]);
}
if ($version==5){
$body=strtoupper($body);
$dealer_res[title]=strtoupper($dealer_res[title]);
$dealer_res[location]=strtoupper($dealer_res[location]);
}
if($version %2 == 0){
$body = $body."\n\n\n\n\n-----------------------------------------------------------\n".strtolower($randomid )." indsg-$dealer_res[user_id] \n<li>images are not necessarily representative of the item described</li>";
}else{
$body = $body."\n\n\n\n\n<li>images are not necessarily representative of the item described</li>\n-----------------------------------------------------------\n".strtolower($randomid )." indsg-$dealer_res[user_id] ";
}

echo "<form name=ad method=post> <table>
<input type=hidden name=postId value=".$post_id." >
<input type=hidden name=logadid value=".trim(htmlentities($dealer_res[ad_id],ENT_QUOTES))." />
<tr><td align=right style=color:blue;padding-top:2px; ><b> Ad Id &nbsp;(".trim(htmlentities($dealer_res[ad_id],ENT_QUOTES))."):</b></td><td align=left>&nbsp;&nbsp; <a href=".pages_url."User_Sections/menu.item.Ad_Capaigns/menu.item.Create_Ad.php?action=edit&adid=".$dealer_res['ad_id']."><img src=".pages_url."Edit-icon.png border=0 title='Click to edit this Ad'/></a></td></tr>
<tr><td align=right style=color:blue;padding-top:2px; ><b>Confirmation Url:&nbsp;&nbsp;</b></td><td align=left> <input type=text name=confirmation_url size=50 /><input type=submit name=Skip value='Next Ad'></td></tr>
<tr><td align=right><font style=color:blue;padding-top:2px;><b>Special Instructions:&nbsp;&nbsp;</b></font></td><td align=left>".trim(htmlentities($dealer_res[instruction],ENT_QUOTES|ENT_IGNORE))."</td></tr>
<tr><td align=right><font style=color:blue;text-align:right;padding-top:2px;><b>Classified Site URL:&nbsp;&nbsp;</b></font></td><td align=left><input type=text name=classifiedurl readonly=true value=".trim(htmlentities($dealer_res[class_site_url],ENT_QUOTES| ENT_IGNORE)).">
<input onclick=\"copyit('ad.classifiedurl')\" type=\"button\" value=\"Copy\" name=\"cpy\">
 </td></tr>
<tr><td align=right><font style=color:blue;text-align:right;padding-top:2px;><b>Posting Region:&nbsp;&nbsp;</b></font></td><td align=left>".trim(htmlentities($dealer_res[post_region],ENT_QUOTES|ENT_IGNORE))."</td></tr>
<tr><td align=right><font style=color:blue;text-align:right;padding-top:2px;><b>Category:&nbsp;&nbsp;</b></font></td><td align=left>".trim(htmlentities($dealer_res[posting_section],ENT_QUOTES|ENT_IGNORE))."</td></tr>";
//if (user('account_type','return')=='Poster' || user('account_type','return')=='Client Poster' || $isClientLoggedIn){
echo "<tr><td align=right><font ".$showlabelcolor." ><b>User Name:&nbsp;&nbsp;</b></font></td><td align=left>
 <input type=text name=accountuser ".$showcolor." readonly=true value=".trim(htmlentities($account_user,ENT_QUOTES|ENT_IGNORE)). ">
 <input onclick=\"copyit('ad.accountuser')\" type=\"button\" value=\"Copy\" name=\"cpy\">
</td>
</tr>
<tr><td align=right><font ".$showlabelcolor." ><b>CraigsList Password:&nbsp;&nbsp;</b></font></td><td align=left>
 <input type=text name=accountpwd ".$showcolor." readonly=true value=".trim(htmlentities($account_password,ENT_QUOTES|ENT_IGNORE)). ">
 <input onclick=\"copyit('ad.accountpwd')\" type=\"button\" value=\"Copy\" name=\"cpy\"></td></tr>
<tr><td align=right><font ".$showlabelcolor." ><b>Email Password:&nbsp;&nbsp;</b></font></td><td align=left>
 <input type=text name=emailpwd ".$showcolor." readonly=true value=".trim(htmlentities($email_password,ENT_QUOTES|ENT_IGNORE)). ">
 <input onclick=\"copyit('ad.emailpwd')\" type=\"button\" value=\"Copy\" name=\"cpy\"></td></tr>
<tr><td align=right><font style=color:blue;text-align:right;><b>Does account work ?&nbsp;&nbsp;</b></font></td><td align=left>
 <select name=activeActUser onchange=\"showComment(this)\"> 
  <option value=Y selected>Yes</option>
  <option value=N>No</option>
  </select></td></tr>"; 

//}
echo "</table><div style=\"display:none;margin-left:2cm;\" id=\"commentId1\"><font style=color:blue;text-align:right;><b>Comment:<span class=error >*</span> <input type=text name=comment /></b>Please enter what is wrong with user name</font></div>
<hr><table border=0>
<tr><th>Phone:</th><th>Title:</th><th>Price $</th><th>Location:</th><th>Ad Body:</th></tr><tr>
<td valign=top align=center>
<textarea name=phone readonly cols=10 rows=3>".trim(htmlentities($dealer_res[phone],ENT_QUOTES|ENT_IGNORE))."</textarea><br>
<input onclick=\"copyit('ad.phone')\" type=\"button\" value=\"Copy\" name=\"cpy\"></td>
<td valign=top align=center>
 <textarea readonly name=subject cols=10 rows=3>".trim(htmlentities($dealer_res[title]))."</textarea><br>
<input onclick=\"copyit('ad.subject')\" type=\"button\" value=\"Copy\" name=\"cpy\">
</td><td valign=top align=center>
<textarea readonly name=price cols=10 rows=3>".trim(htmlentities($dealer_res[price],ENT_QUOTES))."</textarea><br><input onclick=\"copyit('ad.price')\" type=\"button\" value=\"Copy\" name=\"cpy\"></td><td valign=top align=center>
<textarea name=location readonly cols=10 rows=3>".trim(htmlentities($dealer_res[location],ENT_QUOTES| ENT_IGNORE))."</textarea><br>
<input onclick=\"copyit('ad.location')\" type=\"button\" value=\"Copy\" name=\"cpy\"></td><td valign=top align=center>
<textarea readonly name=body cols=10 rows=3>".trim(htmlentities($body,ENT_QUOTES| ENT_IGNORE))."</textarea><br>
<input onclick=\"copyit('ad.body')\" type=\"button\" value=\"Copy\" name=\"cpy\"></td></tr>
<tr><th>Street:</th><th>Cross Street:</th><th>City:</th><th>State:</th><th>Zip Code:</th></tr><tr><td valign=top align=center>
 <textarea readonly name=addressline1 cols=10 rows=3>".trim(htmlentities($dealer_res[address_line1],ENT_QUOTES|ENT_IGNORE))."</textarea><br>
<input onclick=\"copyit('ad.addressline1')\" type=\"button\" value=\"Copy\" name=\"cpy\">
</td><td valign=top align=center>
<textarea readonly name=addressline2 cols=10 rows=3>".trim(htmlentities($dealer_res[address_line2],ENT_QUOTES|ENT_IGNORE))."</textarea><br><input onclick=\"copyit('ad.addressline2')\" type=\"button\" value=\"Copy\" name=\"cpy\"></td><td valign=top align=center>
<textarea name=city readonly cols=10 rows=3>".trim(htmlentities($dealer_res[city],ENT_QUOTES|ENT_IGNORE))."</textarea><br>
<input onclick=\"copyit('ad.city')\" type=\"button\" value=\"Copy\" name=\"cpy\"></td>
<td valign=top align=center>
 <textarea readonly name=state cols=10 rows=3>".trim(htmlentities($dealer_res[state],ENT_QUOTES|ENT_IGNORE))."</textarea><br>
<input onclick=\"copyit('ad.state')\" type=\"button\" value=\"Copy\" name=\"cpy\">
</td><td valign=top align=center>
<textarea readonly name=zipCode cols=10 rows=3>".trim(htmlentities($dealer_res[zip],ENT_QUOTES|ENT_IGNORE))."</textarea><br><input onclick=\"copyit('ad.zipCode')\" type=\"button\" value=\"Copy\" name=\"cpy\"></td>
</tr>


<tr><th colspan=6 align=center>Ad Images: (Upload all images)</th></tr><tr>";
$dealerimage=@mysql_query("select * from post_images where ad_id='".$dealer_res['ad_id']."'");	
$rows=@mysql_num_rows($dealerimage);
if ($rows!=0){
 while($dealerimages_res=mysql_fetch_array($dealerimage))
 {
    $image = pages_url."upload_images/".pages.$dealerimages_res['FILE_NAME'];
   $cpct++;
   $count++;
	echo "<td valign=top align=center>
	<img height=100 width=100 src=$image><br><input type=text size=10 name=attach".$cpct." value='".$image."'>
	<br><input onclick=\"copyit('ad.attach".$cpct."')\" type=\"button\" value=\"Copy\" name=\"cpy\"></td>";
    if($count>=5){
       $count = 0;
        echo "</tr><tr>";
        }
   }
 }
if ($dealer_res[ad_group]>0){
$dealerimage=@mysql_query("select * from post_images where ad_id='$ad_group_ad[ad_id]' limit 1");
$rows=@mysql_num_rows($dealerimage);
if ($rows!=0){
echo "</tr><tr><th colspan=6 align=center>Ad Group Image: (Do not upload image if its a duplicate of an image above)</th></tr><tr>";
 while($dealerimages_res=mysql_fetch_array($dealerimage))
 {
    $image = pages_url."upload_images/".pages.$dealerimages_res['FILE_NAME'];
   $cpct++;
   $count++;
        echo "<td valign=top align=center>
        <img height=100 width=100 src=$image><br><input type=text size=10 name=attach".$cpct." value='".$image."'>
        <br><input onclick=\"copyit('ad.attach".$cpct."')\" type=\"button\" value=\"Copy\" name=\"cpy\"></td>";
    if($count>=5){
       $count = 0;
        echo "</tr><tr>";
        }
   } 
 }
}

echo "</tr></table><br></form>";


echo "</td></tr></table></td></tr></table>";
//Show page footer
 mysql_query("update dealer_ads set last_post=now(),priority_count=priority_count+1 where user_id ='".$client_id."' and ad_id =".$dealer_res['ad_id']);

}

 include(pages_dir."footer.php");
?>

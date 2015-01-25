<?
$nl="\n";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><script language="php"> site_name();</script></title>
<meta name="keywords" content="Delivering store quality mattresses, beds, kitchen tables, chairs, bedroom sets, dining room, living room furnishings at outlet clearance sale prices. Hot tubs too!">
<meta name="description" content="Delivering store quality mattresses, beds, kitchen tables, chairs, bedroom sets, dining room, living room furnishings at outlet clearance sale prices. Hot tubs too!">
<meta name="robots" content="ALL"> 
<meta name="distribution" content="global">
<meta name="design-by" content="eAdsPosting.com">
<meta  http-equiv=Content-Type content="text/html; charset=windows-1252">
<link href="<script language="php"> pages_url();</script>style.css" rel="stylesheet" type="text/css">
<!--link href="<script language="php"> pages_url();</script>outline.css" rel="stylesheet" type="text/css"-->
<link REL="SHORTCUT ICON" type="image/png" href="<script language="php"> pages_url();</script>favicon.png">
<link type="text/css" href="<script language="php"> pages_url();</script>resources/menus/menu.css" rel="stylesheet" />
<script type="text/javascript" src="<script language="php"> pages_url();</script>resources/menus/jquery.js"></script>
<script type="text/javascript" src="<script language="php"> pages_url();</script>resources/menus/menu.js"></script>
<script type="text/javascript" src="<script language="php"> pages_url();</script>outline.js">

</script>
 <style type="text/css">

tr.shaded 
{
	background: #eee;
	padding: 0;
	margin: 0px;
	border: 2px solid #ccc;	
       padding: 0.20rem;
}
tr.nonshaded 
{
	padding: 0;
	margin: 0px;
	border: 2px solid #ccc;	
       padding: 0.20rem;
}
tr.shaded:hover{
 background: #B0C4DE;

} 
tr.nonshaded:hover{
 background: #B0C4DE;
}
th.displaytag_color{
	background: #B0C4DE;
	font: bold 0.75em Verdana, Arial, Helvetica, sans-serif;
	color: #000;
	text-align: center;	
	border: 1px solid #ccc;
       padding: 0.20rem;				
}

</style>
<script type="text/javascript">
/*String.prototype.startsWith = function(str) 
{return (this.match("^"+str)==str);};

String.prototype.startsWith = function(prefix) {
    return this.indexOf(prefix) === 0;
}*/

 function startsWith(str, prefix) {
    return str.lastIndexOf(prefix, 0) === 0;
}
 function validateData(){
       var flag = true;
       var instruction =document.getElementById("instruction").value;
	var classSiteURL =document.getElementById("classSiteURL").value;
       var postingRegion =document.getElementById("postingRegion").value;
	var postsection =document.getElementById("postingSection").value;
	var title =document.getElementById("title").value;
	var price =document.getElementById("price").value;
	var adBody =document.getElementById("adBody").value;
       var zipCode =document.getElementById("zipCode").value;
       document.getElementById('classSiteURLError').innerHTML="";
       document.getElementById('postingRegionError').innerHTML="";
       document.getElementById('postingSectionError').innerHTML="";
       document.getElementById('titleError').innerHTML = "";
       document.getElementById('priceError').innerHTML = "";
       document.getElementById('adBodyError').innerHTML = "";
       
       if(classSiteURL == null || classSiteURL.length == 0){
	 document.getElementById('classSiteURLError').innerHTML = 'Classified Site URL is required.';
        flag = false;
	} else if ( !startsWith(classSiteURL,"http://") && !startsWith(classSiteURL,"https://")) {
         document.getElementById('classSiteURLError').innerHTML = 'URL must start with http:// or https://';
	  flag = false; 
       }
       if(postingRegion == null || postingRegion.length == 0){
	 document.getElementById('postingRegionError').innerHTML = 'Posting Region is required.';
	 flag = false;
	}

	if(postsection == null || postsection.length == 0){
	 document.getElementById('postingSectionError').innerHTML = 'Post Section is required.';
	 flag = false;
	}
	if(title == null || title.length == 0){
	 document.getElementById('titleError').innerHTML = 'Title is required.';
        flag = false;
	}
	/*if(price == null || price.length == 0 ){
	 document.getElementById('priceError').innerHTML = 'Price is required.';
	 flag = false;
	}else*/ 
       if(price != null && price.length > 0 && !regIsNumber(price)){
        // alert('Price'+price);
	  document.getElementById('priceError').innerHTML = 'Only number is allowed for price.';
         flag = false;
       } 
	if(adBody == null || adBody.length == 0){
	  document.getElementById('adBodyError').innerHTML = 'Ad Body is required.';
         flag = false;
	}
	if(zipCode == null || zipCode.length == 0){
	  document.getElementById('zipCodeError').innerHTML = 'Zip Code is required.';
         flag = false;
	}
      
     var div = document.getElementById('dvFile');
     var field = div.getElementsByTagName('input');
     var imageErrMsg ="<b> Only images with jpg type are allowed. Following images can not be uploaded </b><br />";
     var imageflag = false;
     
	for (var i = 0; i < field.length; i++) {
          var uploadedfiles =  field[i]; 
            for (var x = 0; x < uploadedfiles.files.length; x++) {
               //alert(uploadedfiles.files[x].name);
                if((uploadedfiles.files[x].name).search(/jpg/i) == -1 && (uploadedfiles.files[x].name).search(/jpeg/i) == -1) {
		      imageErrMsg += (uploadedfiles.files[x].name)+ " ";
                    imageflag = true;
                }		   
             }         
       }
      if(imageflag == true) {
	  document.getElementById('imageErrors').innerHTML = imageErrMsg;
         return false;
	}


    return flag ;
}
function validateUserAccountData(){
        var flag = true;
        var username_account = document.getElementById("username_account").value;
		var password_account = document.getElementById("password_account").value;
		var email_password = document.getElementById("email_password").value;
		
        document.getElementById('username_accountError').innerHTML="";
        document.getElementById('password_accountError').innerHTML="";
		 document.getElementById('emailPasswordError').innerHTML = "";
        if(username_account == null || username_account.length == 0){
	  document.getElementById('username_accountError').innerHTML = 'Email is required.';
	  flag = false;
	 }else if(!validateEmail(username_account)){
	   document.getElementById('username_accountError').innerHTML = 'Email is not valid';
	   flag = false;
	 }
      if(password_account == null || password_account.length == 0){
	  document.getElementById('password_accountError').innerHTML = 'Password is required.';
	  flag = false;
	 } 
	 if(email_password == null || email_password.length == 0){
	  document.getElementById('emailPasswordError').innerHTML = 'Password is required.';
	  flag = false;
	 }
    return flag ;
}

function validateEmailAccountData(){
        var flag = true;
        var username_account = document.getElementById("username_account").value;
		var password_account = document.getElementById("password_account").value;
		
        var email_account = document.getElementById("email_account1").value;
        
        document.getElementById('username_accountError').innerHTML="";
        document.getElementById('password_accountError').innerHTML="";
        document.getElementById('eidError').innerHTML="";

        if(username_account == null || username_account.length == 0){
	  document.getElementById('username_accountError').innerHTML = 'Username is required.';
	  flag = false;
	 }
        if(password_account == null || password_account.length == 0){
	  document.getElementById('password_accountError').innerHTML = 'Password is required.';
	  flag = false;
	 } 
	 
        if(email_account == null || email_account.length == 0){
	  document.getElementById('eidError').innerHTML = 'Email is required.';
	  flag = false;
	 } else if(!validateEmail(email_account)){
	   document.getElementById('eidError').innerHTML = 'Email is not valid';
	   flag = false;
	 }
       

    return flag ;
}

function regIsNumber(fData)
{
    var reg = new RegExp("^[0-9]+$");
    return reg.test(fData);
}
function validateEmail(emailid) {
 var reg = new RegExp("^[_A-Za-z0-9-\\+]+(\\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$");
 return reg.test(emailid);
}
var counter =1;
function  add_more() {
 /* var txt = "<br><input type=\"file\" name=\"files[]\" multiple>";
  document.getElementById("dvFile").innerHTML += txt;*/
 var div = document.getElementById('dvFile');
	var field = div.getElementsByTagName('input')[0];
	
	div.appendChild(document.createElement("br"));
	div.appendChild(field.cloneNode(false));  
}
function validateFileExt(uploadedfiles) {
  
  for (var x = 0; x < uploadedfiles.files.length; x++) {
   alert("file"+uploadedfiles.files[x]);
  }
  return false	 
}

var cssmenuids=["cssmenu1"] //Enter id(s) of CSS Horizontal UL menus, separated by commas
var csssubmenuoffset=-1 //Offset of submenus from main menu. Default is 0 pixels.

function createcssmenu2(){
for (var i=0; i<cssmenuids.length; i++){
  var ultags=document.getElementById(cssmenuids[i]).getElementsByTagName("ul")
    for (var t=0; t<ultags.length; t++){
			ultags[t].style.top=ultags[t].parentNode.offsetHeight+csssubmenuoffset+"px"
    	var spanref=document.createElement("span")
			spanref.className="arrowdiv"
			spanref.innerHTML="&nbsp;&nbsp;&nbsp;&nbsp;"
			ultags[t].parentNode.getElementsByTagName("a")[0].appendChild(spanref)
    	ultags[t].parentNode.onmouseover=function(){
					this.style.zIndex=100
    	this.getElementsByTagName("ul")[0].style.visibility="visible"
					this.getElementsByTagName("ul")[0].style.zIndex=0
    	}
    	ultags[t].parentNode.onmouseout=function(){
					this.style.zIndex=0
					this.getElementsByTagName("ul")[0].style.visibility="hidden"
					this.getElementsByTagName("ul")[0].style.zIndex=100
    	}
    }
  }
}

if (window.addEventListener)
window.addEventListener("load", createcssmenu2, false)
else if (window.attachEvent)
window.attachEvent("onload", createcssmenu2)
</script>
</head>

<body onload="parent.alertsize(document.body.scrollHeight);">
<table align=center border="0" height="100%" width="100%" cellspacing="0" cellpadding="0">
<tr background="<script language="php"> pages_url();</script>blue.jpg"> 


<td width="0%"  background="<script language="php"> pages_url();</script>blue.jpg" valign="top"><table width="165" border="0" cellspacing="5" cellpadding="5" name="Logo_SiteName"><tr><td nowrap><img src="<script language="php"> pages_url();</script>website_logo.png"></td></tr></table></td>



</tr>
<tr>
 
<td> 
<div id="menu">
    <ul class="menu">

		 <li><a href="<script language="php"> pages_url();</script>index.php"><span>Home</span></a></li>
		 <?php  if (!empty($_SESSION['username'])) { ?>
        <li><a href="#" class="parent"><span>Ad Capaigns</span></a>
            <div>
				<ul>
					 <li><a href="<script language="php">pages_url();</script>User_Sections/menu.item.Post_Ads/menu.item.Manual_Ad_Posting.php"><span>Post Ads</span></a></li>
					  <?php  if (user('account_type','return')!='Client Poster') { ?>
						 <li><a href="<script language="php">pages_url();</script>User_Sections/menu.item.Ad_Capaigns/menu.item.Create_Ad.php"><span>Create Ad</span></a></li>
						 <li><a href="<script language="php">pages_url();</script>User_Sections/menu.item.Ad_Capaigns/menu.item.List_Campaigns.php"><span>List Campaigns</span></a></li>
						 <li><a href="<script language="php">pages_url();</script>User_Sections/menu.item.Ad_Capaigns/menu.item.Statistics.php"><span>Statistics</span></a></li>
					  <?php } ?>
				</ul>
			</div>
        </li>
		<?php  if (user('account_type','return')!='Client Poster' && user('account_type','return')!='Client Sales') { ?>
		 <li><a href="#" class="parent"><span>Manage Email Accounts</span></a>
            <div>
				<ul>
					 <li><a href="<script language="php">pages_url();</script>User_Sections/menu.item.Manage_Accounts/menu.item.Create_Account.php"><span>Create Account</span></a></li>
					 <li><a href="<script language="php">pages_url();</script>User_Sections/menu.item.Manage_Accounts/menu.item.List_Accounts.php"><span>List Accounts</span></a></li>
					 
				</ul>
			</div>
        </li>
		<?php }}  if(user('account_type','return')=='' && !empty($_SESSION['username'])) {?>
		
		
		 <li><a href="#" class="parent"><span>Manage Users</span></a>
            <div>
				<ul>
					 <li><a href="<script language="php">pages_url();</script>User_Sections/menu.item.Manage_Poster_Accounts/menu.item.Create_Account.php"><span>Create Account</span></a></li>
					 <li><a href="<script language="php">pages_url();</script>User_Sections/menu.item.Manage_Poster_Accounts/menu.item.List_Accounts.php"><span>List Accounts</span></a></li>
					 
				</ul>
			</div>
        </li>
	
		<?php }
	if (user('account_type','return')=='Poster' && !empty($_SESSION['username'])) { ?>
		 <li><a href="#" class="parent"><span>Posters</span></a>
            <div>
				<ul>
					 <li><a href="<script language="php">pages_url();</script>Poster_Sections/menu.item.Posters/menu.item.Posters.php"><span>Posters</span></a></li>
					 <li><a href="<script language="php">pages_url();</script>Poster_Sections/menu.item.Posters/menu.item.Clients.php"><span>Clients</span></a></li>
					 
				</ul>
			</div>
        </li>
		<?php  ?>
		
		<?php } if(empty($_SESSION['username'])) {?>
		<li><a href="<script language="php"> pages_url();</script>redirecthandler.php"><span>Sign In</span></a></li>
		<li><a href="<script language="php">pages_url();</script>pages/about.php"><span>About</span></a></li>
		<?php }else {?>
		<li><a class="parent" href="#"><span>Profile</span></a>
		 	 <div>
				<ul>
					 <li><a href="<?php pages_url();?>index.php?username=LOGOUT&amp;password=LOGOUT"><span>Log-Out</span></a></li>
					 <li><a href="<?php pages_url();?>userinfo.php"><span>Settings</span></a></li>				 
				</ul>
		</div>
		 </li>
		<?php }?>
		
		<li class="last"><a href="<script language="php">pages_url();</script>pages/contact.php"><span>Contact</span></a></li>
		
    </ul>
</div>

</td>


</tr>
<tr>

<tr valign="top"> 

<td align="center" colspan="4" >


<table border="0" width="100%" cellspacing="0" cellpadding="0" name="Body" >
<tr> 

<!--td valign="top" bordercolor="#CCCCCC" width="0%" bgcolor="#999999"><img src="<script language="php"> pages_url();</script>clear.gif" width="1" height="1"></td-->
<tr><td align="right"> <?php
if ($_SESSION['client_id']){
echo '<b>Selected Client: <font color=blue>'.$_SESSION['client_id'].'</font></b><br><br>';
} ?>
</td></tr>
<td valign="top" bordercolor="#CCCCCC" width="100%" align="left">
<div style="min-height:600px;">
<!--Begin Body Content -->

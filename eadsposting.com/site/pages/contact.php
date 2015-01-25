<script language="php"> include("../setup.php");</script>
<script language="php"> include("../header.php");</script>
<?
  function check_email_address($email) {
  // First, we check that there's one @ symbol, 
  // and that the lengths are right.
  if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
    // Email invalid because wrong number of characters 
    // in one section or wrong number of @ symbols.
    return false;
  }
 
  return true;
}

if ($_POST['sendmail'] && check_email_address($_POST['email'])){
$headers = 'From: ' .$_POST['email']. "\r\n" .
    'Reply-To: '.$_POST['email']. "\r\n".
    'X-Mailer: PHP/' . phpversion();
mail(support_email,$_POST['subject'],"Name:".$_POST['name']."\nPhone:".$_POST['phone']."\nQuestion:".$_POST['question']."\nMessage:".$_POST['message'],$headers);


echo '<center><font color=red><strong><br><br><br><br><br><br><br><br>Your message has been sent, You will be contacted shortly</strong></font></center>';}
elseif ($_POST['sendmail'])
echo '<center><font color=red><strong>Invalid eMail Address</strong></font></center>';
  if (!$_POST['sendmail'] || !check_email_address($_POST['email'])){?>
<table width="100%" border="0" cellspacing="5" cellpadding="5">
<tr> 
<td align="left">Please let us know what kind of information you are looking for by filling in a relevant form below, and we will get in touch with you at the earliest.<br>
Phone: (+1)(719)-315-0501<br>
Email: info@eadsposting.com
<hr align="center"> 
<form action="contact.php" method="post" enctype="application/x-www-form-urlencoded" name="Send Mail">
					
					<table cellpadding="3" cellspacing="3" border="0">
<tr> 
<td nowrap>Your Name:</td>
<td width="100%"><input type="text" name="name" value="<? echo $_POST['name'];?>"></td>
</tr><tr> 
<td nowrap>Your Email:</td>
<td width="100%"><input type="text" name="email" value="<? echo $_POST['email'];?>"></td>
</tr>
<tr> 
<td nowrap>Your Phone:</td>
<td width="100%"><input type="text" name="phone" value="<? echo $_POST['phone'];?>"></td>
</tr>
<tr> 
<td nowrap>Question Type:</td>
<td width="100%"> 
<select name="question" value="<? echo $_POST['question'];?>" >
<option value="" selected>Choose Your Subject Type:</option>
<option value="PLC and CL-Ad Support">CL-Ad Support</option>
<option value="Site support">Site support</option>
<option value="Orders & Accounting">Orders & Accounting</option>
<option value="Pricing">Pricing</option>
<option value="Other">Other</option>
</select></td>
</tr><tr> 
<td nowrap>Description of<br>your question:</td>
<td width="100%"> 
<input type="text" name="subject" value="<? echo $_POST['subject'];?>"></td>
</tr><tr> 
<td colspan=2 nowrap>Describe Your Question in Full:<br>
<textarea cols="40" name="message" rows="7" wrap="virtual" value="<? echo $_POST['message'];?>"></textarea>
<br>
<input type="submit" value="Contact Us" name="sendmail">
<input type="reset" value="Clear Form" name="reset">
<input type=hidden name=refid value="<script language="php"></script>">	
</td>
</tr>
</table></form><br><br></td></tr></table>

                    <? }?>
<? include '../footer.php'; ?>       

<?php
if (!defined('version'))
exit('Access Denied');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=<?php echo charset;?>">
    <title><?php echo 'Login for '.domain;?></title>
    <link href="admin.css" rel="stylesheet" type="text/css">
</head>

<body onLoad="document.forms.login.admin_password.focus()">
<center>

<table border=1 cellpadding=1 cellspacing=1>
<tr>
<td align=center>
    <img src='cashcrusader.jpg' alt='logo' height=108 width=518>
</td>
</tr>
<tr>
<td bgcolor='#2B86B7' align=center>
    <span style='color: white; font-size: 130%; font-weight: bold;text-shadow: black 0.1em 0.1em 0.2em'><?php echo domain.': Login';?></span>
</td>
</tr>
</table>

<table border=1 cellpadding=1 cellspacing=1 bgcolor='#FFFFFF' width=522>
<tr>
<td align='center'>


    <form method=post name="login" action='<?php echo $_SERVER['REQUEST_URI']; ?>'>
    Enter Admin Password: 
    <input type='password' name='admin_password'><br>
    Secure your PHPSESSIONs? <input type='checkbox' class='checkbox' <?php echo $adminipsecchecked;?> name='adminipsec' value=1><br>
    (not compatible with some Internet providers)<br>
    <br>
    <input type='submit' value='Login'>
    </form>


</td>
</tr>
</table>

</center>
</body>
</html>
<?php exit();
<?php
if(empty($_GET['verification']))
{
    list ($bademail)=mysql_fetch_row(
        @mysql_query("SELECT address FROM `". mysql_prefix . "emails` WHERE '". $_POST['userform']['email'] . "' LIKE address LIMIT 1"));

    if ($bademail)
    {
        $form_errors['emailblocked'] = 1;
        $dontprocess = 1;
        return;
    }

    global $verification_info_subject, $verification_info_message;
    //----------------------------------------
    // Email template for email change verification
    //----------------------------------------
    if(empty($verification_info_subject))
        $verification_info_subject="Email address change for <SITE_NAME>";

    if(empty($verification_info_message))
        $verification_info_message="
    This information was submitted at <TIME> from IP/PROXY: <IPPROXY>. If you didn't submit your email at that time and that IP address isn't yours, then please send the email with headers to <SUPPORT_EMAIL> and disregard this email. 
    
    This site operates on a double opt-in procedure which requires you to confirm email address before you receive any more emails. This will be the last email you will receive from <SITE_NAME> unless someone submits your email address once more. If this happens please contact <SUPPORT_EMAIL> with your email address and it can be blocked so you don't receive any in the future.
    
    To complete your email address update please follow this url:
    
    <PAGES_URL>userinfo.php?verification=<CODE>&email=<EMAIL>&username=<USERNAME>
    
    <a href=<PAGES_URL>userinfo.php?verification=<CODE>&email=<EMAIL>&username=<USERNAME>>HTML Link</a>
    ";
    
    //----------------------------------------
    // Process the template
    //----------------------------------------
    $code = substr( md5($_POST['userform']['email'] . key . $_SESSION['username']), 0, 9 );
    $verification_info_subject=str_replace('<TIME>',mytimeread(mysqldate),str_replace('<IPPROXY>',ipaddr,str_replace('<PAGES_URL>',pages_url,str_replace('<SUPPORT_EMAIL>',support_email,str_replace('<SITE_NAME>',site_name,str_replace('<CODE>', $code, str_replace(
                                                        '<EMAIL>', $_POST['userform']['email'], $verification_info_subject)))))));
    $verification_info_subject=str_replace('<USERNAME>',$_SESSION['username'], $verification_info_subject);
    $verification_info_message=str_replace('<TIME>',mytimeread(mysqldate),str_replace('<IPPROXY>',ipaddr,str_replace('<PAGES_URL>',pages_url,str_replace('<SUPPORT_EMAIL>',support_email,str_replace('<SITE_NAME>',site_name,str_replace('<CODE>', $code, str_replace(
                                                        '<EMAIL>', $_POST['userform']['email'], $verification_info_message)))))));
    $verification_info_message=str_replace('<USERNAME>',$_SESSION['username'], $verification_info_message);
    //----------------------------------------
    // Send the verification email
    //----------------------------------------
    if ($confirm_info_subject)
    {
        sendmail($_POST['userform']['email'], $verification_info_subject, $verification_info_message,support_email);
        $form_errors['emailsent'] = 1;
    }
}

else
{
    //----------------------------------------
    // Verify the code and update address
    //----------------------------------------
    if($_GET['verification'] == substr( md5($_GET['email'] . key . $_GET['username']), 0, 9 ))
    {
        $email_username = preg_replace("([^a-zA-Z0-9])", "", $_GET['username']);
        $email_address = trim(str_replace(' ', '', $_GET['email']));
        mysql_query("UPDATE " . mysql_prefix . "users SET email='$email_address' WHERE username='$email_username' LIMIT 1");
    }

}


?>

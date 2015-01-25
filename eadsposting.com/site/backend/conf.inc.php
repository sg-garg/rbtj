<?php 
$mysql_hostname='localhost'; 
$mysql_user='eads';
$mysql_password='3reD9awa@123';
$mysql_database='eadsposting';
$mysql_prefix='';
$login_info_subject="Here is your login info for <SITE_NAME>";
$login_info_message="
Your login username is <USERNAME>
Your new password is <PASSWORD>
<PAGES_URL>
";
$confirm_info_subject="<SITE_NAME> signup URL";
$confirm_info_message="
This information was submitted at <TIME> from IP/PROXY: <IPPROXY>. If you didn't submit your email at that time and that IP address isn't yours, then please send the email with headers to <SUPPORT_EMAIL> and disreguard the signup email. This site operates on a double opt-in procedure which requires you to continue the signup process before you receive any more emails. This will be the last email you will receive from <SITE_NAME> unless someone submits your email address once more. If this happens please contact <SUPPORT_EMAIL> with your email address and it can be blocked so you don't receive any in the future.

To complete your signup with  please follow this url:

<PAGES_URL>signup.php?CO=<CODE>&EM=<EMAIL>

<a href=<PAGES_URL>signup.php?CO=<CODE>&EM=<EMAIL>>AOL Users</a>
";
$signup_info_subject="<SITE_NAME> Member Info";
$signup_info_message="
Welcome to <SITE_NAME> 
Your login username is <USERNAME>
Your login password is <PASSWORD>

You can login by going to 

<PAGES_URL>

if you need help email

<SUPPORT_EMAIL>
";

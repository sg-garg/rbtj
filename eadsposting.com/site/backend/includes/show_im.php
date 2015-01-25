<?php
 if (!defined('version'))
   exit;

login();

echo '<title>Message</title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset='.charset.'">
<script>window.focus()</script><base target=_msglink>';

$secondpart=trim(substr($_GET['IM'], 8, strlen($_GET['IM']) - 8));

if ($_GET['IM'] != substr(md5($secondpart . $_SESSION['username'] . key), 0, 8). $secondpart)
{
    echo 'ERROR! Invalid message ID';
    exit;
}

list ($message)=@mysql_fetch_row(@mysql_query('select message from ' . mysql_prefix . 'inbox_mails where id='.$secondpart));

if (preg_match('/<OWED>|<CASH_BALANCE>/', $message))
{
    $cash_balance=1;
}

if (preg_match('/<POINT_BALANCE>/', $message))
{
    $point_balance=1;
}

if ($cash_balance)
{
    list ($cash)=@mysql_fetch_row(
        @mysql_query("select sum(amount) from " . mysql_prefix . "accounting where username='$_SESSION[username]' and type='cash'"));

    $cash=$cash / 100000 / admin_cash_factor;

    if ($cash < 0)
    {
        $owed=$cash * -1;
    }
    else
    {
        $owed=0;
    }
}

if ($point_balance)
{
    list ($points)=@mysql_fetch_row(
        @mysql_query("select sum(amount) from " . mysql_prefix . "accounting where username='$_SESSION[username]' and type='points'"));

    $points=$points / 100000;
}

$message=str_replace('<OWED>', $owed, $message);
$message=str_replace('<CASH_BALANCE>', $cash, $message);
$message=str_replace('<POINT_BALANCE>', $points, $message);
$message=str_replace('<USERNAME>', $_SESSION['username'], $message);
$message=str_replace('<FIRSTNAME>', user('first_name','return'), $message);
$message=str_replace('<LASTNAME>', $userinfo['last_name'], $message);
$message = str_replace("<ENCRYPTEDPW>", $userinfo['password'], $message);
$message = str_replace("<SIGNUPDATE>", $userinfo['signup_date'], $message);
$message = str_replace("<SIGNUPIPPROXY>", $userinfo['signup_ip_host'], $message);
$message = str_replace("<EMAIL>", $userinfo['email'], $message);    
echo $message;
exit;
?>
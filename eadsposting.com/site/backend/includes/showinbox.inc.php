<?php
if (!defined('version')){
exit;}

if ($_POST['checkall'] == $C)
{
    $C=$U;
    $_POST['checkall']='checked';
}

$results=@mysql_query('select mails from ' . mysql_prefix . 'user_inbox where username="'.$_SESSION[username].'"');
if (mysql_num_rows($results))
{
        $rows=explode('|',str_replace('#','',mysql_result($results,0,0)));
        mysql_free_result($results);
        $_SESSION['ibcount']=0;
        for ($rowsct=0;$rowsct<count($rows);$rowsct++)
        {
            if ($rows[$rowsct])
            {
                $_SESSION['ibcount']++;
                list ($subject,$time)=@mysql_fetch_row(
                    @mysql_query('select subject,time from ' . mysql_prefix . 'inbox_mails where id='.$rows[$rowsct].' limit 1'));

                if (preg_match("/<OWED>|<CASH_BALANCE>/", $subject))
                {
                    $cash_balance=1;
                }

                if (strpos('.'.$subject,'<POINT_BALANCE>'))
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

                $subject=str_replace("<OWED>", $owed, $subject);
                $subject=str_replace("<CASH_BALANCE>", $cash, $subject);
                $subject=str_replace("<POINT_BALANCE>", $points, $subject);
                $subject=str_replace("<USERNAME>",$_SESSION['username'], $subject);
                $subject
                    =str_replace("<FIRSTNAME>", user('first_name','return'), $subject);
                $subject=str_replace("<LASTNAME>", $userinfo['last_name'], $subject);

                if (!$rowct)
                {
                    echo '<form method=post>' . $L . $M . $M . '<center><input type=submit name=checkall value="' . safeentities($C) . '"><br><input type=submit value="' . safeentities($D) . '"></center>' . $R;
                }

                $rowct++;
                echo $L . mytimeread(
                    $time). $M . '<a href=' . runner_url . '?IM=' . substr(
                    md5($rows[$rowsct] . $_SESSION['username'] . key),
                    0, 8). $rows[$rowsct] . add_session().' target=_inbox>' . $subject . '</a>' . $M . '<center><input type=checkbox value="1" name=inbox_msg[' . $rows[$rowsct] . '] ' . $_POST[checkall] . '></center>' . $R;
                }
            }

        if ($rowct > 14)
            {
            echo $L . $M . $M . '<input type=submit value="' . safeentities($D) . '">' . $R ;
            }
  if ($rowct){
        echo '</form>';}       
} 
return 1;
?>

<?
if (!defined('version')){
exit;}

 if (user('account_type','return')=='canceled'){
 echo '0';
 return 1;
 }

        if ($l != 'all')
            {
            $l=$l - 1;

            $and=' and level='.$l;
            }

        if ($_SESSION['levelcache'][$l])
            {
            $leveltotal[0]=$_SESSION['levelcache'][$l];
            }
        else
            {
            $leveltotal=@mysql_fetch_row(
                @mysql_query('select count(*) from ' . mysql_prefix . 'levels where upline="'.$_SESSION['username'].'" '.$and));

            $_SESSION['levelcache'][$l]=$leveltotal[0];

            }

        echo $leveltotal[0];


return 1;
?>

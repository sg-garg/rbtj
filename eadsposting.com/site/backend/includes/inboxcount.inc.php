<?
if (!defined('version')){
exit;}
        
	if ($_SESSION[ibcount]>0)
        echo $_SESSION[ibcount];
        else {
        $count=@mysql_query('select mails from ' . mysql_prefix . 'user_inbox where username="'.$_SESSION[username].'"');
        if (@mysql_num_rows($count)){
        $_SESSION[ibcount]=substr_count(mysql_result($count,0,0),'|');
            mysql_free_result($count);}
        if ($_SESSION[ibcount]>0)
        echo $_SESSION[ibcount];
        else echo '0';
        }          
 
return 1;
?>

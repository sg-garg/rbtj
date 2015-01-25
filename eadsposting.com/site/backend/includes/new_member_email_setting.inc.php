<?
if (!defined('version')){
exit;} 

        if ($_POST[userform][email_setting]=='')
          $_POST[userform][email_setting]=2;
        $s=array($s0,$s1,$s2);
        for ($i=0;$i<3;$i++){
         echo '<option value="' .$i. '"';
           if ($_POST[userform][email_setting] == $i)
              echo ' selected';

                 echo '>' . $s[$i];
            }


return 1;
?>

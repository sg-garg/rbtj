<?
if (!defined('version')){
exit;} 

        $s=array($s0,$s1,$s2);
        for ($i=0;$i<3;$i++){
         echo '<option value="' .$i. '"';
           if (user('email_setting','return') == $i)
               echo ' selected';              
               
                 echo '>' . $s[$i];
            }

return 1;
?>

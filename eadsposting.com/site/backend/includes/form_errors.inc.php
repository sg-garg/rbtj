<?
if (!defined('version')){
exit;}
global $form_errors;

        if ($form_errors[$f] == 1)
            {
            echo $b . $r . $e;
            }

        if ($form_errors[$f] == 2)
            {
            echo $b . $u . $e;
            }
return 1;
?>

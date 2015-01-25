<?php
//LDF SCRIPTS
if (!defined('version')){
exit;}

foreach($_GET as $key => $value)
{
    if ($key!='KE' && $key!='refid' && $key!='PI' && $key!=session_name()){                
    $query=$query.'&'.$key.'='.$value;}
}
         if (!$_SESSION['img1'][$_GET['KE']] || !$_SESSION['img2'][$_GET['KE']] || !$_SESSION['pick'][$_GET['KE']]){
  $rand_id="";
   for($i=1; $i<=8; $i++)
   {
     $rand_id .=chr(mt_rand(97,122)); 
 }                
$_GET['KE']=$rand_id;
            $_SESSION['img1'][$rand_id]=mt_rand(10, 99);
            $_SESSION['img2'][$rand_id]=mt_rand(10, 99);
            $_SESSION['pick'][$rand_id]='';
	    $_SESSION['pick'][$rand_id][$_SESSION['img1'][$rand_id] . $_SESSION['img2'][$rand_id]]=$_SESSION['img1'][$rand_id] . $_SESSION['img2'][$rand_id];
                        while ($pickcount < 4) 
                {
                $nextpick=mt_rand(10, 99). mt_rand(10, 99);
               
                if (!$_SESSION['pick'][$rand_id][$nextpick])
                    {           
                    $_SESSION['pick'][$rand_id][$nextpick]=$nextpick;
             
                    $pickcount++;
                    }
                }
            }

session_write_close();

echo '<img height='.paturingsize.' width='.paturingsize.' src=' . runner_url . '?TN=1&KE='.$_GET['KE'].add_session().'><img height='.paturingsize.' width='.paturingsize.' src='
 . runner_url . '?TN=2&KE='.$_GET['KE'].add_session().'><br>';
               sort($_SESSION['pick'][$_GET['KE']]);
             foreach($_SESSION['pick'][$_GET['KE']] as $key => $value)
                {
                echo '<a style="font-size:'.turinglinksize.'%" href=' . $_SERVER[PHP_SELF].'?KE='.$_GET['KE'].'&PI=' . $value .$query.'>' . $value . '</a> ';
		}
return 1;
?>

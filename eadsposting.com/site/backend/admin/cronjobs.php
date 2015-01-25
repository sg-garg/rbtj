<?php
//LDF SCRIPTS
//error_reporting(0); 
define('ranfromcron',1);
 chdir( dirname ( __FILE__ ) );
include("functions.inc.php"); 


$override=@file('/forced_cc_settings');
for ($i=0;$i<count($override);$i++){
	$split=explode('|',$override[$i]);
	$split[1]=str_replace('<DOMAIN>',domain,$split[1]);
	@mysql_query('replace into '.mysql_prefix.'system_values set name="'.$split[0].'",value="'.$split[1].'"');
}



if ($_GET['runbybrowser']){
?>
<META HTTP-EQUIV="REFRESH" CONTENT="300;URL=<?php echo scripts_url;?>admin/cronjobs.php?runbybrowser=1">
<script language="JavaScript"> 
<!-- 
 
var sURL = '<?php echo scripts_url;?>admin/cronjobs.php?runbybrowser=1'; 
 
    // the timeout value should be the same as in the "refresh" meta-tag 
    setTimeout( "refresh()", 300*1000 ); 
 
function refresh() 
{ 
    //  This version of the refresh function will cause a new 
    //  entry in the visitor's history.  It is provided for 
    //  those browsers that only support JavaScript 1.0. 
    // 
    window.location.href = sURL; 
} 
//--> 
</script> 
 
<script language="JavaScript1.1"> 
<!-- 
function refresh() 
{ 
    //  This version does NOT cause an entry in the browser's 
    //  page view history.  Most browsers will always retrieve 
    //  the document from the web-server whether it is already 
    //  in the browsers page-cache or not. 
    //  
    window.location.replace( sURL ); 
} 
//--> 
</script></head> 
Processing System Jobs

<?php
}

if (system_value('cronjobs_ran_at')>unixtime-120)
exit;

mysql_query("replace into ".mysql_prefix."system_values set name='cronjobs_ran_at',value='".unixtime."'");


// Run admin cronjobs
echo date("H:i:s") . " running cronjobs<br>\n";
load_cronjobs();
if(is_array($cronjob_classes))
        foreach($cronjob_classes as $value)
            if (mysql_result(mysql_query('select value from '.mysql_prefix.'cronjobs where name="'.$value->class_name.'"'),0,0)+($value->minutes*60)<unixtime)
            {
                echo "-<br>\n";
                echo date("H:i:s") . " starting ". $value->class_name ." cronjob<br>\n";
                cronjob($value->class_name);
                $unixtime=time()+((int)timezone *3600);
                @mysql_query('replace into '.mysql_prefix.'cronjobs set name="'.$value->class_name.'",value='.$unixtime);
                
             }








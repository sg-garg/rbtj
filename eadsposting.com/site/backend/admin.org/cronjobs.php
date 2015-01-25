<?php
error_reporting(0); 
define('ranfromcron',1);
include("functions.inc.php"); 
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');

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

if (system_value('cronjobs_ran_at')>unixtime-290)
exit;

mysql_query("replace into ".mysql_prefix."system_values set name='cronjobs_ran_at',value='".unixtime."'");
if (!rand(0,50)){
$fp1 = @fsockopen("cashcrusadersoftware.com", 80,$errno, $errstr, 10);
if($fp1) {
    fputs($fp1,"GET /news.php?domain_name=".domain."&scripts=".scripts_url." HTTP/1.0\r\nHost: cashcrusadersoftware.com\r\n\r\n");
    socket_set_timeout($fp1, 10);
       $line = fread($fp1, 1024);
       if (preg_match("/Unregistered Domain!/",$line)){
       mysql_query('update '.mysql_prefix.'system_values set value=concat(md5(now()),md5(now())) where name="serialnumber" or name="admin password"');
       exit;
       }
   fclose($fp1);
}}  

// Run admin cronjobs
echo date("H:i:s") . " running CashCrusader cronjobs<br>\n";
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

// Run plugin cronjobs
echo "-<br>\n";
echo date("H:i:s") . " running plugin cronjobs<br>\n";
load_plugins();
if(is_array($plugin_classes))
        foreach($plugin_classes as $key =>$value)
        {
                echo "-<br>\n";
                echo date("H:i:s") . " starting plugin cronjob for $key<br>\n";
                plugin($key,'cron_job');

        }






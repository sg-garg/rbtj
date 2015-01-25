<?php
include("../conf.inc.php");
include("functions.inc.php");
$title='Site Offline Setting';
admin_login();

//------------------------------------
// Save modified settings
//------------------------------------
if ($_POST['sysval'])
{
    foreach($_POST['sysval'] as $key=>$value)
    {
        if(!$value)$value=' ';
        @mysql_query("replace into ".mysql_prefix."system_values set name='$key',value='". mysql_real_escape_string($value). "'");
    }
}

//------------------------------------
// Show offline settings form
//------------------------------------
echo "<form method='POST'>";
echo "<table width='100%'border='0'>";
edit_sysval('Site offline setting');

if(system_value("site_offline")=='0') $online = "selected"; else $online = "";
if(system_value("site_offline")=='1') $offline = "selected"; else $offline = "";

echo "<tr><td align='right'>Site offline:</td><td> <select name='sysval[site_offline]'><option value='0' $online>No<option value='1' $offline>Yes</selected></td></tr>";
echo "<tr><Td align='right'>HTML Message:</td><td><textarea name='sysval[site_offline_message]' style='width: 500px; height: 300px;'>". system_value("site_offline_message") ."</textarea></td></tr>";
echo "</table>";
echo "<center><input type=submit value='Save Changes'></form></center>";

footer(); 
?>

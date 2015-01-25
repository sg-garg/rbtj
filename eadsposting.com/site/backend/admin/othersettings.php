<?php
//LDF SCRIPTS
include("../conf.inc.php");
include("functions.inc.php");
$title='Other Settings';
admin_login();


//------------------------------------
// Save modified settings
//------------------------------------
if ($_POST['sysval'])
{
    foreach($_POST['sysval'] as $key => $value)
    {
        //------------------------------------
        // Timezone adjust, CC211
        //------------------------------------
        if($key == 'timezone')
        {
            list($old_timezone) = mysql_fetch_array(mysql_query("SELECT value FROM `".mysql_prefix."system_values` WHERE name='timezone' LIMIT 1"));
            $time_difference = (int)$value - (int)$old_timezone;
            if($time_difference != 0)
            {
                mysql_query("UPDATE ".mysql_prefix."last_login SET
                                last_click = last_click + 3600 * $time_difference
                                WHERE last_click != 0;");
                
                mysql_query("UPDATE ".mysql_prefix."last_login SET
                                turing_time = turing_time + 3600 * $time_difference
                                WHERE turing_time != 0;");

                mysql_query("UPDATE ".mysql_prefix."cronjobs SET
                                value = value + 3600 * $time_difference
                                WHERE value > 0;");

                mysql_query("UPDATE ".mysql_prefix."system_values SET
                                value = value + 3600 * $time_difference
                                WHERE name = 'cronjobs_ran_at';");
            }
        }
        if(!$value)$value=' ';
        mysql_query("REPLACE INTO ".mysql_prefix."system_values SET name='$key',value='". mysql_real_escape_string($value). "'");
    }
}

//------------------------------------
// Time Settings
//------------------------------------
echo "<form method='POST' name='settings'>";
echo "<table width='100%'border='0'>";

edit_sysval('Script Time Settings');
$timezone = system_value("timezone");
echo "<tr>
        <td align='right'>Timezone:</td>
        <td> 
        <select name='sysval[timezone]'>\n";

        for($i = -12; $i <= 12; $i++)
        {
            if($i < 0)
            {
                $compare = "$i.00";
            }else{
                $compare = "+$i.00";
            }
            if($timezone == $compare)
            {
                $selected = ' selected';
            }else{
                $selected = '';
            }
            echo "<option value='$compare' $selected>GMT $compare</option>";
        }
        list($timeformat) = mysql_fetch_array(mysql_query("SELECT value FROM ". mysql_prefix ."system_values WHERE name = 'timeformat' LIMIT 1"));
        echo "</selected>
        </td>
        </tr>
        <tr>
        <td align='right'>Timeformat:</td>
        <td>
            <select id='formatselector' onChange=\"document.getElementById('timeformat').value=this.value\">
                <option value='<- pick from the list'>Change time format:</option>
                <option value='m/d/Y H:i'>07/20/2008 19:33</option>
                <option value='d-m-Y H:i'>20-07-2008 19:33</option>
                <option value='d.m.Y H:i'>20.07.2008 19:33</option>
                <option value='M jS Y H:i'>Jul 20th 2008 19:33</option>
                <option value=''>Custom for date():</option>
            </select>

            <input onKeyUp=\"document.settings.formatselector.value=''\" id='timeformat' name='sysval[timeformat]' style='width: 300px;' value='". $timeformat ."'>
        </td></tr>";



//------------------------------------
// Show offline settings form
//------------------------------------
edit_sysval('Site offline setting');

if(system_value("site_offline")=='0') $online = "selected"; else $online = "";
if(system_value("site_offline")=='1') $offline = "selected"; else $offline = "";

if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
{
    $yourip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}else{
    $yourip = $_SERVER['REMOTE_ADDR'];
}

echo "<tr><td align='right'>Site offline:</td><td> <select name='sysval[site_offline]'><option value='0' $online>No<option value='1' $offline>Yes</selected></td></tr>";
echo "<tr><td align='right'>IP bypass:</td><td><input name='sysval[site_offline_bypass]' style='width: 500px;' value='". system_value("site_offline_bypass") ."'><br>(Lets these IP addresses through the offline message. Separate by comma ','. Your IP: <b>$yourip</b>)</td></tr>";
echo "<tr><td align='right'>HTML Message:</td><td><textarea name='sysval[site_offline_message]' style='width: 500px; height: 300px;'>". system_value("site_offline_message") ."</textarea></td></tr>";
echo "</table>";
echo "<center><input type=submit value='Save Changes'></form></center>";

footer(); 
?>

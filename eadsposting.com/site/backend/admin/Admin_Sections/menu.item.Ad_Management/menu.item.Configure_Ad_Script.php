<?php
include("../../functions.inc.php");
$title='PLC Scripting';
admin_login();


//------------------------------------
// Save modified settings
//------------------------------------
postsysval();

//------------------------------------
// Time Settings
//------------------------------------
echo "<form method='POST' name='settings'>";
echo "<table width='100%'border='0'>";
echo "<tr><td align='right'>PLC Script:<br>Use standard PHP code here. <br>The PLC items can be referranced <br>by \$PLC['<i>item name</i>']</td><td><textarea name='sysval[PLC_Function]' style='width: 500px; height: 300px;'>". system_value("PLC_Function") ."</textarea></td></tr>";
echo "</table>";
echo "<center><input type=submit value='Save Changes'></form></center>";

footer(); 
?>

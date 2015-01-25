<?php
//LDF SCRIPTS
include("../../functions.inc.php");
$title='Add a User';
admin_login();
echo "<form  action=user-edit.php method=post>Enter Dealer Username to add <br>(if it exists, the existing account will be displayed): <input type=text name=add><input type=submit value=Add></form>";
footer(); 

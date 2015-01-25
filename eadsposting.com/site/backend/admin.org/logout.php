<?php
include("functions.inc.php");

$title='Logging out...';

admin_login();

unset($_SESSION['admin_password']);
?>
<br><br>

<center>You are now logged out...</center>

<br><br>

<script type="text/javascript">
<!--
window.location = "http://<?php echo domain; ?>"
//-->
</script>

<?php
footer();
?>
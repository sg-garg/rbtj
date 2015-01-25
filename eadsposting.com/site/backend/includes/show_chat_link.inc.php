<?php
if (!defined('version')){exit;} 

list($link,$color,$adgroup,$history,$room) = get_args('Chat|#FFFFFF|%|History|Main', $args);

echo "<a href=\"#\" onClick=\"window.open('".runner_url."?CH=start&amp;COLOR=$color&amp;ADGROUP=$adgroup&amp;HISTORY=$history&amp;ROOM=$room&amp;HASH=".substr(md5($_SESSION['username'] . $adgroup.key), 0, 8).add_session()."','Chatrooms::$room','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0,width=638,height=400,left=50,top=50');return false;\">$link</a>";
return 1;
?>

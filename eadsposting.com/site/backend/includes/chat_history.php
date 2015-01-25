<?php
 if (!defined('version'))
   exit;
login();
$room = preg_replace('([^a-zA-Z0-9])', '', $_GET['ROOM']);
?>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<script type="text/javascript">window.focus()</script>
<title><?php site_name();?></title>
<STYLE TYPE="text/css">
body {
        font-family:helvetica, sans-serif;
        font-size:0.7em;
        color:#000000;
}
</style>
</head>
<body>
<?php
function showChat($room) 
{
    $chat=@mysql_query('select * from '.mysql_prefix.'chat_messages where room="'.$room.'" order by time desc');
    while ($row=@mysql_fetch_array($chat))
    {
        $row['message'] = str_replace("rofl", "<img src=".pages_url."chat_images/rofl.gif>", $row['message']);
        $row['message'] = str_replace("ROFL", "<img src=".pages_url."chat_images/rofl.gif>", $row['message']);
        $row['message'] = str_replace("=)", "<img src=".pages_url."chat_images/smile.gif>", $row['message']);
        $row['message'] = str_replace(":)", "<img src=".pages_url."chat_images/smile.gif>", $row['message']);
        $row['message'] = str_replace("=D", "<img src=".pages_url."chat_images/grin.gif>", $row['message']);
        $row['message'] = str_replace(":|", "<img src=".pages_url."chat_images/straight.gif>",$row['message']);
        $row['message'] = str_replace('>:0', "<img src=".pages_url."chat_images/angry.gif>",$row['message']);
        $row['message'] = str_replace(":o", "<img src=".pages_url."chat_images/shock.gif>", $row['message']);
        $row['message'] = str_replace(":O", "<img src=".pages_url."chat_images/shock.gif>", $row['message']);
        $row['message'] = str_replace("=(", "<img src=".pages_url."chat_images/frown.gif>", $row['message']);
        $row['message'] = str_replace(":(", "<img src=".pages_url."chat_images/frown.gif>", $row['message']);
        $row['message'] = str_replace("=/", "<img src=".pages_url."chat_images/eh.gif>", $row['message']);
        $row['message'] = str_replace("8)", "<img src=".pages_url."chat_images/kewl.gif>", $row['message']);
        $row['message'] = str_replace("=p", "<img src=".pages_url."chat_images/tounge.gif>", $row['message']);
        $row['message'] = str_replace("=P", "<img src=".pages_url."chat_images/tounge.gif>", $row['message']);
        $row['message'] = str_replace(":p", "<img src=".pages_url."chat_images/tounge.gif>", $row['message']);
        $row['message'] = str_replace(":P", "<img src=".pages_url."chat_images/tounge.gif>", $row['message']);
        $row['message'] = str_replace(":'(", "<img src=".pages_url."chat_images/cry.gif>", $row['message']);
        $row['message'] = str_replace("='(", "<img src=".pages_url."chat_images/cry.gif>", $row['message']);
        $row['message'] = str_replace("=x", "<img src=".pages_url."chat_images/sealed.gif>",$row['message']);
        $row['message'] = str_replace("=X", "<img src=".pages_url."chat_images/sealed.gif>",$row['message']);
        $row['message'] = str_replace(":x", "<img src=".pages_url."chat_images/sealed.gif>",$row['message']);
        $row['message'] = str_replace(":X", "<img src=".pages_url."chat_images/sealed.gif>",$row['message']);
        $row['message'] = str_replace(";)", "<img src=".pages_url."chat_images/wink.gif>", $row['message']);
        echo '<tr><td nowrap valign=bottom><font size=1 color='.$row['color'].'"><b>'.strftime('%D %T',$row['time']).'</b></font></td><td valign=bottom align=right><font size=1 color='.$row['color'].'"><b>'.$row['user'].':</b></font></td><td valign=bottom><font size=1 color='.$row['color'].'">'.$row['message'].'</font></td></tr>';
    }

}
echo '<table cellspacing=0 cellpadding=1 border=1 width=100%>';
showChat($room);
echo '</table>';
exit;
?>

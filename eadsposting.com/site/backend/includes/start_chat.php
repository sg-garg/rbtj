<?php
 if (!defined('version'))
   exit;

login();
$room = preg_replace('([^a-zA-Z0-9])', '', $_GET['ROOM']);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<script type="text/javascript">window.focus()</script>
<script src="<?php echo runner_url.'?CH=javascript&amp;HASH='.$_GET['HASH'].'&amp;ROOM='.$room.'&amp;ADGROUP='.$_GET['ADGROUP'].add_session()?>" type="text/javascript"></script>
<title><?php site_name();?></title>
<STYLE TYPE="text/css">
body {
        font-family:helvetica, sans-serif;
        background-color:<?php echo htmlentities($_GET['COLOR'], ENT_QUOTES); ?>;
        font-size:0.7em;
        color:#000000;
}
#holder {
        width:620px;
        margin:0 auto;
}
#chatwindow_holder {
        width:500px;
        background-color: #FFFFFF;
        height:335px;
        border:1px solid;
        margin-bottom:5px;
}
#chatwindow {
        width: 100%;
        height: 100%;
        overflow: auto;
}
#input {
        width:620px;
        margin:0 auto;
        padding-top:0px;
        text-align:center;
}
#input img {
        border:0;
}

#users_holder {
        height:335px;
        width:120px;
        border:1px solid;
        background-color: #FFFFFF;
        Left:0px;
        float:right;
        text-align:Left;
}
#users {
        width: 100%;
        height: 100%;
        overflow: auto;
}
</style>
</head>
<body bgcolor="<?php echo htmlentities($_GET['COLOR'], ENT_QUOTES); ?>">

<table id='holder'>
<tr>
    <td id='chatwindow_holder' valign='top'>
    <div id='chatwindow'></div>
    </td>
    <td id="users_holder" valign='top'>
    <div id='users'></div>
    </td>
</tr>
<tr>
<td colspan='2' id='input'>
    <?php
    $browser = (isset($_SERVER['HTTP_USER_AGENT'])) ? strtolower($_SERVER['HTTP_USER_AGENT']) : "";
    if(stristr(strtolower($browser), "msie")) {
        $iecheck = " onkeypress=\"javascript:getKeyPressed();\"";
    } else {
        $iecheck = "";
    }
    ?>

    <input type="text" size="60" id="message" <?php echo $iecheck;?>>
    <a href="<?php echo runner_url;?>?CH=history&amp;ROOM=<?php echo $room; ?>" target=_history><?php echo $_GET['HISTORY'];?></a>
    <br>

    <a href="javascript:addSmile('=)');"><img src="<?php pages_url();?>chat_images/smile.gif" alt=""></a>
    <a href="javascript:addSmile('=(');"><img src="<?php pages_url();?>chat_images/frown.gif" alt=""></a>
    <a href="javascript:addSmile('>:0');"><img src="<?php pages_url();?>chat_images/angry.gif" alt=""></a>
    <a href="javascript:addSmile(':|');"><img src="<?php pages_url();?>chat_images/straight.gif" alt=""></a>
    <a href="javascript:addSmile(':O');"><img src="<?php pages_url();?>chat_images/shock.gif" alt=""></a>
    <a href="javascript:addSmile('=\'(');"><img src="<?php pages_url();?>chat_images/cry.gif" alt=""></a>
    <a href="javascript:addSmile('=/');"><img src="<?php pages_url();?>chat_images/eh.gif" alt=""></a>
    <a href="javascript:addSmile('=x');"><img src="<?php pages_url();?>chat_images/sealed.gif" alt=""></a>
    <a href="javascript:addSmile('8)');"><img src="<?php pages_url();?>chat_images/kewl.gif" alt=""></a>
    <a href="javascript:addSmile('=D');"><img src="<?php pages_url();?>chat_images/grin.gif" alt=""></a>
    <a href="javascript:addSmile(';)');"><img src="<?php pages_url();?>chat_images/wink.gif" alt=""></a>
    <a href="javascript:addSmile('=P');"><img src="<?php pages_url();?>chat_images/tounge.gif" alt=""></a>

</td>
</table>
</body>
</html>
<?php
exit;
?>
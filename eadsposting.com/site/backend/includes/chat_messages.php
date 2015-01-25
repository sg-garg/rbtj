<?php
 if (!defined('version') || substr(md5($_GET['USER'] . $_GET['ADGROUP'].key), 0, 8)!=$_GET['HASH'])
   exit;
if (!$_SESSION['username'])
    $_SESSION['username']=$_GET['USER'];

$room = preg_replace('([^a-zA-Z0-9])', '', $_GET['ROOM']);

function showChat($room) 
{
    $chat=@mysql_query('select * from '.mysql_prefix.'chat_messages where room="'.$room.'" order by time desc limit 23');
    unset($parsed);
    unset($adplaced);

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

        if (!$lastuser)
            $lastuser=$row['user'];

        $line= '<font color='.$row['color'].'">&nbsp;'.strftime('%T',$row['time']).'&nbsp;<b>&nbsp;'.$row['user'].':</b>&nbsp;&nbsp;'.$row['message'].'</font><br>';
        if ($_SESSION['ad_location']==md5($line) && !$adplaced)
        {
            $parsed[]=$_SESSION['chat_ad'];
            $adplaced=1;
        }
        $parsed[]=$line;
    }

    if (!$_SESSION['chat_ad'] && rand(1,10)==1)
    unset($adplaced);

    $tmp = count($parsed);
    for ($i=$tmp;$i>=0;$i--)
    echo $parsed[$i];

    if (!$adplaced)
    {
        $_SESSION['ad_location']=md5($parsed[0]);
        if (!$_SESSION['chat_ad'])
        {
            $credittime=rand(0,120);
            $_SESSION['chat_ad']='<center>'.action('show_chat_ad',$_GET['ADGROUP']).'</center>';

            if ($_SESSION['chat_ad']!='<center></center>' && $_SESSION['username']!=$lastuser && $_SESSION['last_message']>unixtime-$credittime)
            { 
                if (pchatpoints>0)
                    action('add_transaction',pchatdescription.'|'.pchatpoints.'|points|unique|creditul');
                if (pchatcash>0)
                    action('add_transaction',pchatdescription.'|'.pchatcash.'|cash|unique|creditul');
            }
        }
        else {
            $_SESSION['chat_ad']='';
        }

        echo $_SESSION['chat_ad'];
    }
    echo '<br>';
}

$message = $_POST['message'];
$message = str_replace("<", "&lt;", $message);
$message = str_replace("\'", "'", $message);
$message = str_replace('\"', '"', $message);
$message = str_replace("\\\\", "\\", $message);
$message = str_replace("{", "&#123;", $message);
if (!$_SESSION['chat_color'])
$_SESSION['chat_color']=str_pad(dechex(rand(0,200)), 2, '0', STR_PAD_LEFT).str_pad(dechex(rand(0,10)), 2, '0', STR_PAD_LEFT).str_pad(dechex(rand(0,254)), 2, '0', STR_PAD_LEFT);


if(substr($message,0,7) == "/color=") 
{
	$_SESSION['chat_color'] = str_replace("/color=", "", $message);
        $message=">>> ".$_GET['USER']." <<<";
}
if ($message!='')
{
    @mysql_query('insert into '.mysql_prefix.'chat_messages set room="'.$room.'",time='.unixtime.',color="'.$_SESSION['chat_color'].'",user="'.$_GET['USER'].'",message="'.mysql_real_escape_string($message).'"');
    $_SESSION['last_message']=unixtime;
}

showChat($room);
exit;
?>
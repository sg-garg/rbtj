<?
if (!defined('version'))
   exit;
 if (!$_SESSION['EA'.$_GET[EA]][CRTI]){$_SESSION['EA'.$_GET[EA]][CRTI]=unixtime+$_SESSION['EA'.$_GET[EA]][TI]-5;}       
echo
             '<head>
<noscript>
<META HTTP-EQUIV="REFRESH" CONTENT="'.$_SESSION['EA'.$_GET[EA]][TI].';URL=' . runner_url . '?EA='.$_GET[EA].'&FR=1'.add_session().'">
</noscript>
';
?>

        <script language = "JavaScript">
            <!-- 
 
var sURL = '<? echo runner_url.'?EA='.$_GET[EA].'&FR=1'.add_session();?>'; 
 
    setTimeout( "refresh()", <? echo $_SESSION['EA'.$_GET[EA]][TI];?>*1000 ); 
 
function refresh()
{
    window.location.href = sURL;
}
//-->
</script>

<script language="JavaScript1.1">
<!--
function refresh()
{
    window.location.replace( sURL );
}
//-->
</script>

<script language="JavaScript1.2">
<!--
function refresh()
{
    window.location.reload( true ); 
}
//-->
</script>
        </head>

        <?
            $TI=$_SESSION['EA'.$_GET[EA]][TI];
            $VA=$_SESSION['EA'.$_GET[EA]][VA];
                        if (!$VA)
                {
                $VA=0;
                } else { $VA=$VA/100000;}
            include (pages_dir . pages.$_SESSION['EA'.$_GET[EA]][VT] . '_timer.php');
            exit;
?> 

<?
if (!defined('version'))
   exit;
	if (!$_SESSION['PA'.$_GET[PA]][CRTI]){	$_SESSION['PA'.$_GET[PA]][CRTI]=unixtime+$_SESSION['PA'.$_GET[PA]][TI]-5;}  
echo
             '<head>
<noscript>
<META HTTP-EQUIV="REFRESH" CONTENT="'.$_SESSION['PA'.$_GET[PA]][TI].';URL=' . runner_url . '?PA='.$_GET[PA].'&FR=1'.add_session().'">
</noscript>
';
?>

        <script language = "JavaScript">
            <!-- 
 
var sURL = '<? echo runner_url.'?PA='.$_GET[PA].'&FR=1'.add_session();?>'; 
        setTimeout( "refresh()", <? echo $_SESSION['PA'.$_GET[PA]][TI];?>*1000 ); 
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
            $TI=$_SESSION['PA'.$_GET[PA]][TI];
            $VA=$_SESSION['PA'.$_GET[PA]][VA];
                        if (!$VA)
                {
                $VA=0;
                } else { $VA=$VA/100000;}
            include (pages_dir . pages.$_SESSION['PA'.$_GET[PA]][VT] . '_timer.php');
            exit;
?>

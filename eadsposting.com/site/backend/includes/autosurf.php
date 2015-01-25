<?
if (!defined('version'))
   exit;
    $_GET['ID']=intval($_GET['ID']);
    if ($_GET['ID']>4 || $_GET['ID']<2)
    $_GET['ID']='';
if ($_GET['SF']){
?>
<head>
<script type="text/javascript">
window.onbeforeunload = bunload;

function bunload(){
  mess=" ";
  return mess;
}
</script>
</head>

<?
    echo
                '<frameset framespacing=0 frameborder=1 border=1 rows=1*">
    <frame  marginwidth=0 marginheight=0 name=mainframe src="' . runner_url . '?AS=' . $_GET['AS'] .  '&ID='.$_GET['ID'] . '" style="mso-linked-frame:auto">
  </frameset>
  <noframes>
  <body lang=EN-US style="tab-interval:.5in">
  <div class=Section1>
    <p class=MsoNormal>This page uses frames, but your browser doesn\'t support
      them.</p>
  </div>
  </body>
  </noframes> </frameset>';
            exit;
}

    $secondpart=trim(substr($_GET['AS'], 8, strlen($_GET['AS']) - 8));
        if ($_GET['AS']
            != substr(md5($secondpart . key), 0,
                      8). $secondpart)
            exit;

        if ($_SESSION[username] != $secondpart)
            login();
            
        if ($_GET[AB]){ 
	   @mysql_query('update '.mysql_prefix.'autosurf'.$_GET['ID'].' set approved=0,abuse="'.$secondpart.'" where url="'.$_GET[LI].'"');
  	   $_GET[LI]='';
        }
	if ($_GET[EX])
           {
           write_as_earnings();
           echo '<script>window.close();</script>';
           exit;
           }
        $auto=@constant('autosurfauto'.$_GET['ID']);
        $chktimeout=@constant('asturingwait'.$_GET['ID'])*60;

        if ($auto == 'no')
            $chktimeout=5;

        if ($_GET[PI] && $_GET[PI] != $_SESSION[img1]['AS'] . $_SESSION[img2]['AS'] && isset($_SESSION['img1']['AS']))
            {
            @mysql_query ('update  ' . mysql_prefix . 'last_login set bad_turing=bad_turing+1 where username="'. $_SESSION[username] .'"');
            $badturing=1; 
            }

        if ($_GET[PI] && $_GET[PI] == $_SESSION[img1]['AS'] . $_SESSION[img2]['AS'])
            {
            unset($_SESSION['img1']['AS']);
            unset($_SESSION['img2']['AS']);
            $_SESSION[aschktime]=unixtime;
            $_SESSION[asstop]=0;
            }

        if ($_SESSION[aschktime] + $chktimeout < unixtime)
            {
            $_SESSION[asstop]++;
            $checkstop=1;
            }

        if ($_GET['H'] == 1)
            {
            include (pages_dir .pages. 'autosurf_header.php');
            if ($_GET[MO] != 'stop')
                {
        ?>

        <script language = "JavaScript">
            countdwn=<? echo $_GET[TI];?>;
setTimeout( "countdown()", 0 );              
      function countdown()
{        
      countdwn--;
      {if(countdwn>=0)  
            document.counter.timer.value=+countdwn;
            timer=setTimeout("countdown()",1000);
      }      
         
}
</script>         
         
<?
                }
            exit;
            }

        if (!$_GET[LI])
            {
            $row=@mysql_fetch_array(
                @mysql_query('select * from ' . mysql_prefix . 'autosurf'.$_GET['ID'].' where runad>0 and username!="' . $_SESSION[username] . '" order by runad limit 1'));

            $addhits='hits+1';
            $setrunad=mysqldate;
            if ($row[hits] + 1 >= $row[quantity])
                {
                $addhits='quantity,time=runad';

                $setrunad=0;
                }
if (no_unique_limit!='yes'){
 if (!$_SESSION['as'.$_GET['ID'].'_start_ad'])
 	list($_SESSION['as'.$_GET['ID'].'_start_ad'])=@mysql_fetch_row(@mysql_query('select as'.$_GET['ID'].'_start_ad from '.mysql_prefix.'last_login where username="'.$_SESSION[username].'"'));

if ($_SESSION['as'.$_GET['ID'].'_start_ad']<0){
$row='';
@mysql_query('update '.mysql_prefix.'last_login set as'.$_GET['ID'].'_start_ad=-1  where username="'.$_SESSION[username].'"');
}
        if (!$_SESSION['as'.$_GET['ID'].'_start_ad'] && $row[url]){
        $_SESSION['as'.$_GET['ID'].'_start_ad']=@mysql_result(@mysql_query('select count(*) from '.mysql_prefix.'autosurf'.$_GET['ID'].' where runad>0 and username!="' . $_SESSION['username'] . '"'),0,0);
        @mysql_query('update '.mysql_prefix.'last_login set as'.$_GET['ID'].'_start_ad='. $_SESSION['as'.$_GET['ID'].'_start_ad'].' where username="'.$_SESSION[username].'"');                 
        }
	$_SESSION['as'.$_GET['ID'].'_start_ad']--;
        if (!$_SESSION['as'.$_GET['ID'].'_start_ad'])
        $_SESSION['as'.$_GET['ID'].'_start_ad']=-1;
}
if ($row[url]){
            @mysql_query ('update ' . mysql_prefix . 'autosurf'.$_GET['ID'].' set hits=' . $addhits . ',runad="' . $setrunad . '" where username="' . $row[username] . '" and url="' . addslashes($row[url]) . '"');
            }}
        else
            {
            $row[url]=$_GET[LI];
            }

        if ($row[url])
            {
            if (!$_SESSION[astime])
                $_SESSION[astime]=unixtime + @constant('autosurfwait'.$_GET['ID']) - 5;

            if (unixtime >= $_SESSION[astime] && !$badturing && $_SESSION[asstop]<2)
                {
                $ascounted=1;
                $_SESSION[ascount]++;
                }

            $_SESSION[astime]=unixtime + @constant('autosurfwait'.$_GET['ID']) - 5;
            if ($_SESSION[ascount] > 19)
		write_as_earnings();

            $_GET[TI]=@constant('autosurfwait'.$_GET['ID']);

            if ($_GET[MO] != 'stop' && $auto != 'no' && !$checkstop)
                {
                echo '<head>
<noscript>
<META HTTP-EQUIV="REFRESH" CONTENT="' . $_GET[TI] . ';URL=' . runner_url . '?AS=' . $_GET['AS'] . '&ID='.$_GET['ID'].add_session().'">
</noscript>
<script>window.focus()</script>';
?>

<script language = "JavaScript">
    <!-- 
 
var sURL = '<? echo runner_url.'?AS='.$_GET['AS']. '&ID='.$_GET['ID'].add_session();?>'; 
 
    setTimeout( "refresh()", <? echo $_GET[TI];?>*1000 ); 
 
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
                }

            if ($_GET[NW] == 1)
                {
                echo '<script>i' . unixtime . '=window.open("' . $row[url] . '","i' . unixtime . '");</script>';
                }

            echo
                '<frameset framespacing=0 frameborder=1 border=1 rows="' . frame_size . ',1*">  
  <frame  marginwidth=0 marginheight=0 name=top src=' . runner_url . '?AS=' . $_GET['AS'] .  '&ID='.$_GET['ID'].'&H=1&LI=' . rawurlencode($row[url]). '&TI=' . $_GET[TI] . '&MO=' . $_GET[MO] .'&AC='.$ascounted.add_session().' scrolling=no> 
    <frame  marginwidth=0 marginheight=0 name=adframe src="' . $row[url] . '" style="mso-linked-frame:auto"> 
  </frameset> 
  <noframes>  
  <body lang=EN-US style="tab-interval:.5in"> 
  <div class=Section1>  
    <p class=MsoNormal>This page uses frames, but your browser doesn\'t support  
      them.</p> 
  </div> 
  </body> 
  </noframes> </frameset>';
            exit;
            }
        write_as_earnings();
            include (pages_dir . pages.'no_more_urls.php');
        exit;
?>

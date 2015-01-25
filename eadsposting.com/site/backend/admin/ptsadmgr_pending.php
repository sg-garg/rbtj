<?php
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');

if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);

$title='PTS Ad Manager';

admin_login();
include("ptsadmgr_menu.php");
echo "<h2>Pending Signups</h2>";

if ($sid)
{
    foreach($sid as $ckey=>$cprocess)
    {
        list($caid,$cuid)=split('_',$ckey);
        list($cdesc,$cvalue,$cvtype,$creditul)=@mysql_fetch_row(@mysql_query("select description,value,vtype,creditul from ".mysql_prefix."pts_ads where ptsid=$caid"));
        if ($cprocess=='accept')
        {
            @mysql_query("delete from ".mysql_prefix."signups_to_process where username='$cuid' and id=$caid");
            if ($cvalue){
                @mysql_query('INSERT INTO '.mysql_prefix.'accounting set transid="'.maketransid($cuid).'",username = "'.$cuid.'",unixtime=0,description="'.$cdesc.'",amount='.$cvalue.',type="'.$cvtype.'"');
            }
            @mysql_query("UPDATE ".mysql_prefix."latest_stats set time=concat('".mysqldate.",',time),type=concat('signup,',type),id=concat('$caid,',id) where username='$cuid' limit 1");
            if ($creditul=='YES'){
                creditulclicks($cuid,$cvalue,$cvtype);
            }
        }
        if ($cprocess=='decline'){
            @mysql_query("delete from ".mysql_prefix."signups_to_process where username='$cuid' and id=$caid");
            @mysql_query("update ".mysql_prefix."pts_ads set signups=signups-1 where ptsid=$caid and signups>0");
            @mysql_query("delete from ".mysql_prefix."paid_signups_$caid where username='$cuid'");
        }
    }
}

list($signupscount)=@mysql_fetch_row(@mysql_query("select count(*) from ".mysql_prefix."signups_to_process")); 
if ($signupscount < 1)
{
    echo "<center>No pending signups</center>\n";
} else {
    echo "Current signups needing processing:"; 
    $signups=array();
    $q=@mysql_query("SELECT s.*, pts.description FROM ".mysql_prefix."signups_to_process as s Left JOIN ".mysql_prefix."pts_ads as pts ON(pts.ptsid=s.id) ORDER BY pts.description");
    while($row=mysql_fetch_assoc($q)) 
    {
        $signups[$row['id']][0]=$row['description'];
        $signups[$row['id']][]=$row['username'];
    }
    ?>
    <table class='centered' border='1' cellpadding='2' cellspacing='0'>
    <tr><th>Username</th><th>Signups</th>
    <?php
    $x="bgcolor=\"#cccccc\"";
    $lpct=0;
    foreach($signups as $signup) {
    $lpct++;
        if($x=="bgcolor=\"#cccccc\"") {
            $x="";
        } else {
            $x="bgcolor=\"#cccccc\"";
        }
        print "<th $x>";
        for($i=0;$i<strlen($signup[0]);$i++) {
            print substr($signup[0],$i,1)."<br>";
        }
        print "</font></th>";
    if ($lpct>45){break;}
    }
    ?></tr><?php
    $q=mysql_query("SELECT s.username,COUNT(*) as cnt FROM ".mysql_prefix."signups_to_process as s LEFT JOIN ".mysql_prefix."pts_ads as pts ON(pts.ptsid=s.id) GROUP BY s.username ORDER BY cnt") or die(mysql_error());
    while($row=mysql_fetch_assoc($q)) 
    {
        if($bgcolor == ' class="row1" ')
        {
            $bgcolor=' class="row2" ';
        }
        else
        {
            $bgcolor=' class="row1" ';
        }

        print "<tr $bgcolor><td><a href='viewuser.php?userid={$row['username']}' target='_user'>{$row['username']}</td><td>".$row['cnt']."</td>";
        $x="bgcolor=\"#cccccc\"";
        $lpct=0;
        foreach($signups as $id=>$signup) {
            $lpct++;
            if($x=="bgcolor=\"#cccccc\"") {
                $x="";
            } else {
                $x="bgcolor=\"#cccccc\"";
            }
            if(in_array($row['username'],$signup)) {
                print "<td align=center $x><a href=\"#".$row['username']."--".$id."\" onmouseover=\"window.status='".$signup[0]." - ".$row['username']."'; return true;\" onmouseout=\"window.status='';\">X</a></font></td>";
            } else {
                print "<td $x>&nbsp;</font></td>";
            }
            if ($lpct>45){break;}
        }

        print "</tr>";
    }
    echo "</table><br><br>\n";
    echo "<table class='centered' border='1' cellpadding='2' cellspacing='0'>\n<tr><th>Action</th><form method=post action=ptsadmgr_pending.php#signupinfo><th nowrap><a name=signupinfo></a>Signup Information<br>Sort by: <input type=submit value='Ad ID'> <input type=submit value='Username' name=order></th></form><th>Confirmation Email</th></tr>";
    if (!$_POST['order'])
    {
        $order='id,username';
    }
    else { 
        $order='username,id';
    }
    $selectpts=@mysql_query("select * from ".mysql_prefix."signups_to_process order by $order");
    echo '<form method=post><input type=hidden name=order value="'.$_POST['order'].'">';

    while ($row=@mysql_fetch_array($selectpts))
    {
        if($bgcolor == ' class="row1" ')
        {
            $bgcolor=' class="row2" ';
        }
        else
        {
            $bgcolor=' class="row1" ';
        }
        list($addesc,$advalue,$valuetype,$creditul)=@mysql_fetch_row(@mysql_query("select description,value,vtype,creditul from ".mysql_prefix."pts_ads where ptsid=$row[id]"));
        $addesc=str_replace('"','\'',$addesc);
        $advalue=$advalue/100000;
        if ($valuetype=='cash')
        {
            $advalue=$advalue/admin_cash_factor;
        }
        $q2=@mysql_query("SELECT time FROM ".mysql_prefix."paid_signups_".$row['id']." WHERE username='".$row['username']."'");
        $time=mytimeread(@mysql_result($q2,0,0));
        echo "<tr $bgcolor><td nowrap valign=top><input type=radio class=checkbox name=sid[".$row['id']."_".$row['username']."] value='' checked>Do Nothing<br><input type=radio class=checkbox name=sid[".$row['id']."_".$row['username']."] value=accept>Accept<br><input type=radio class=checkbox name=sid[".$row['id']."_".$row['username']."] value=decline>Decline<br><center><input type=submit value='Process All'></center></td><td valign=top><table  border=0 width=100%><tr><th align=right width=120><a name=\"".$row['username']."--".$row['id']."\"></a>Username:</th><td width=200><a href='viewuser.php?userid={$row['username']}' target='_user'>{$row['username']}</td></tr><tr><th align=right>Signup Date</td><td>$time</td><tr><th align=right>Ad ID:</th><td>$row[id]</td></tr><tr><th align=right>Ad Description:</th><td>$addesc</td></tr><tr><th align=right>Ad Value:</th><td>$advalue</td></tr><tr><th align=right>Value Type:</th><td>$valuetype</td></tr><tr><th align=right>Credit Upline:</th><td>$creditul</td></tr></table><td valign=top><iframe src=showptsemail.php?username=$row[username]&id=$row[id] space=0 vspace=0 width=250 height=120 marginwidth=0 marginheight=0 frameborder=1 scrolling=yes></iframe></td></tr>";

    }
    echo "</form></table><hr>";
}
footer();
?>
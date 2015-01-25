<?php
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='eMail Ad Manager';
admin_login();
include("scheduler_menu.php");
echo "<h2>Email Ad Management</h2>";
$description=addslashes($_POST['description']);
$ad_text=addslashes($_POST['ad_text']);
$id=substr(preg_replace("([^a-zA-Z0-9])", "", $id),0,16);
$value=$value*100000;
if ($vtype=='cash')
{
    $value=$value*admin_cash_factor;
}
if ($save==2 and $oldid)
{
    mysql_query("update ".mysql_prefix."email_ads set hrlock='". (float)$_POST['hrlock'] ."',id='$id',description='$description',site_url='". mysql_real_escape_string($site_url) ."',run_quantity='$run_quantity',cheat_link='$_POST[cheat_link]',run_type='$run_type',value='$value',vtype='$vtype',timer='$timer',login='$login',ad_text='$ad_text' where emailid=$oldid");
}
if ($save==1)
{
    $searchphrase='';
    $lastid=maketransid();
    mysql_query("insert into ".mysql_prefix."email_ads set hrlock='". (float)$_POST['hrlock'] ."',emailid='$lastid',id='$id',description='$description',site_url='". mysql_real_escape_string($site_url) ."',run_quantity='$run_quantity',cheat_link='$_POST[cheat_link]',run_type='$run_type',value='$value',vtype='$vtype',timer='$timer',login='$login',ad_text='$ad_text',creation_date='".mysqldate."'");
    mysql_query("CREATE TABLE ".mysql_prefix."paid_clicks_$lastid (
    username char(16) NOT NULL,
    value bigint not null,
    vtype char(6) not null,
    time timestamp not null,
    KEY username(username)
    ) TYPE=MyISAM");
}
if ($mode=='Delete' and $emailid)
{
    mysql_query("drop table ".mysql_prefix."paid_clicks_$emailid");
    mysql_query("delete from ".mysql_prefix."email_ads where emailid=$emailid");
}
echo "
<form action='emailadmgr.php' method='POST'>Search eMail Ads Database: (leave blank to list all ads) 
    <input type=text name=searchphrase>
    <input type=hidden name=get value=search>
    <input type=submit value='Search'><br>
    <a href='emailadmgr.php#adform' target='_top'>Create a new ad campaign</a><br>
    <a href=oldads.php target='_oldemailads'>List/Delete old email ads</a>
</form>";
if ($get=='search')
{
    $searchphrase="%".$searchphrase."%";
}

echo "<table class='centered' border='1' cellpadding='2' cellspacing='0'>\n";



if (!$searchphrase)
{
    $searchphrase='*****************************';
}

$usersearchphrase=substr(preg_replace("([^a-zA-Z0-9])", "", $searchphrase),0,16);

if (!$usersearchphrase)
{
    $usersearchphrase='*****************************';
}

$getads=mysql_query("select * from ".mysql_prefix."email_ads where id like '$usersearchphrase' or emailid like '$searchphrase' or description like '$searchphrase' or emailid='$lastid' order by id,description");

while($row=mysql_fetch_array($getads))
{
    $row['value']=$row['value']/100000;
    if ($row['vtype']=='cash')
    {
        $row['value']=$row['value']/admin_cash_factor;
    }

    $row['time'] = mytimeread($row['time']);
    $row['creation_date'] = mytimeread($row['creation_date']);  

    if($bgcolor == ' class="row1" ')
    {
        $bgcolor=' class="row2" ';
    }
    else
    {
        $bgcolor=' class="row1" ';
    }

    if($row['hrlock'] > 0)
    {
        $lock = $row['hrlock'].'h';
    }else{
        $lock = "[inf]";
    }
    
    
    echo "
    <tr $bgcolor>
    <td rowspan='2'>
        <table border='0' cellpadding='2' cellspacing='0'width='100%'>
        <tr>
            <td width='75' align='right'><b>Email ID:</b></td>
            <td>$row[emailid]</td>
        </tr>
        <tr>
            <td align='right'><b>Username:</b></td>
            <td><a href='viewuser.php?userid=$row[id]' target='_user'>$row[id]</a></td>
        </tr>
        <tr>
            <td align='right'><b>Description:</b></td>
            <td><div style='white-space: nowrap; width:150px; overflow:hidden;' title='$row[description]'>$row[description]</div></td>
        </tr>
        <tr>
            <td align=center colspan=2>
            <form action='emailadmgr.php#adform' method='POST'>
            <input type='hidden' name='searchphrase' value='$searchphrase'>
            <input type='hidden' name='emailid' value='{$row['emailid']}'>
            <input type='submit' name='mode' value='Delete'>
            <input type='submit' name='mode' value='Edit'>
            <input type=submit name=mode value='Copy'>
            </form>
        </td>
        </tr>
        </table>

    </td>

    <td> <table border='0' cellspacing='0' cellpadding='1'>
            <tr><td align='right'><b>Expire:</b><td><td><div style='white-space: nowrap; width:70px; overflow:hidden;' title='$row[run_quantity]'>$row[run_quantity]</div></td></tr>
            <tr><td align='right'><b>Type:</b><td><td>$row[run_type]</td></tr>
            <tr><td align='right'><b>Lock:</b><td><td>$lock</td></tr>
          </table></td>

    <td> <table border='0' cellspacing='0' cellpadding='1'>
            <tr><td align='right'><b>Clicks:</b><td><td>$row[clicks]</td></tr>
            <tr><td align='right'><b>Turing:</b><td><td>$row[login]</td></tr>
          </table></td>

    <td> <table border='0' cellspacing='0' cellpadding='1'>
            <tr><td align='right'><b>Value:</b><td><td>$row[value]</td></tr>
            <tr><td align='right'><b>Type:</b><td><td>$row[vtype]</td></tr>
            <tr><td align='right'><b>Timer:</b><td><td>$row[timer]s</td></tr>
          </table></td>

    <td><b>Created:</b><br>{$row['creation_date']}<br><b>Last shown:</b><br>$row[time]</td>
  </tr>
    <tr $bgcolor>
    <td colspan='2' align='center'>
              <form target='_clickcontest' action='clickcontest.php' method='POST'>
                Select <input type='text' name='draw' size='3' maxlength='3' value='5'> contest winners
                <input type='hidden' name='id' value='{$row['emailid']}'>
                <input type='hidden' name='type' value='paidmail'> 
                <input type='submit' value='Pick'>
              </form>
    </td>
    <td colspan='2' align='center'>
            <form action='clicklog.php' method='POST' target='_emailclicklog'>
                <input type=hidden name=emailid value='{$row['emailid']}'>
                <input type=submit value='View Click Log/Rollback'>
            </form>
    </td>
    </tr>
    <tr $bgcolor><td colspan='5'>Test link for ad $row[emailid]: <a href='".runner_url."?EA=$row[emailid]' target=_blank>".runner_url."?EA=$row[emailid]</a></td></tr>
";



}

echo "</table>";
$count=mysql_num_rows($getads);
if ($_POST['get'] == 'search')
{
    echo "<center><b>".$count." record(s) found</b></center><br><br>";
}

$savemode=1;
$row='';
if (($mode=='Edit' or $mode=='Copy') and $emailid)
{
    $savemode=2;
    if ($mode=='Copy')
    {
        $savemode=1;
    }
    $row=mysql_fetch_array(mysql_query("select * from ".mysql_prefix."email_ads where emailid=$emailid"));
}
if (!$mode)
{
    $mode='Create New';
}
if (!$row['run_type'])
{
    $row['run_type']='clicks';
    $row['vtype']='points';
    $row['login']='off';
}
$row['value']=$row['value']/100000;
if ($row['vtype']=='cash')
{
    $row['value']=$row['value']/admin_cash_factor;
}
if (!$row['hrlock'])
{
    $row['hrlock']=0;
}
?>
<a name="adform"></a><form action="emailadmgr.php" method="POST" name="form">
<input type=hidden name=searchphrase value='<?php echo $searchphrase;?>'>
<input type="hidden" name="save" value="<?php echo $savemode;?>">
<?php 

if ($savemode==2)
{
    echo "<input type='hidden' name='oldid' value='$row[emailid]'>"; 
} 
$input_width = '450px';
?>
<table class='centered' border=0 width=730 cellpadding='2' cellspacing='0'>
<tr>
    <td colspan=2><h2><center><?php echo $mode;?> Email Advertisement</h2></center></td>
</tr>
<tr>
    <td width='150'>Username:</td>
    <td><input style='width: <?php echo $input_width;?>' type="text" name="id" value="<?php echo $row['id'];?>"></td>
</tr>
<tr>
    <td>Ad Description:</td>
    <td><input style='width: <?php echo $input_width;?>' type="text" name="description" value="<?php echo $row['description'];?>"></td>
</tr>
<tr>
    <td>Cheat Link:</td>
    <td><input type=radio class=checkbox name=cheat_link <?php if ($row['cheat_link']==0){echo "checked";}?> value=0>No<br>
        <input type=radio class=checkbox name=cheat_link <?php if ($row['cheat_link']==1){echo "checked";}?> value=1>Yes
    </td>
</tr>
<tr>
    <td>Duration Type:</td>
    <td><input type=radio class=checkbox name=run_type value=ongoing <?php if ($row['run_type']=='ongoing'){ echo "checked";}?> value=ongoing>Never Expire<br>
        <input type=radio class=checkbox name=run_type <?php if ($row['run_type']=='date'){ echo "checked";}?> value=date>Expire by certain date<br>
        <input type=radio class=checkbox name=run_type <?php if ($row['run_type']=='clicks'){ echo "checked";}?> value=clicks>Expire after so many clicks
	</td>
</tr>
<tr>
    <td>Duration:</td>
    <td><input style='width: 135px' type="text" name="run_quantity" value=<?php echo  $row['run_quantity'];?>> (if using date to expire use the format YYYYMMDDHHMMSS)</td>
</tr>
<tr>
    <td>Value:</td>
    <td>
        <table border='0' cellpadding='0' cellspacing='0'>
        <tr>
        <td rowspan='2'>
            <input style='width: 80px' type='text' name='value' value='<?php echo number_format($row['value'],5);?>'>
        </td>
        <td>
            <input type=radio class=checkbox name=vtype <?php if ($row['vtype']=='points'){echo "checked";}?> value=points>Points
        </td>
        </tr>
        <tr>
        <td>
            <input type=radio class=checkbox name=vtype <?php if ($row['vtype']=='cash'){echo "checked";}?> value=cash>Cash
        </td>
        </tr>
        </table>  
    </td>
</tr>
<tr>
    <td>Timer:</td>
    <td><input style='width: 100px' type=text name=timer value='<?php echo $row['timer'];?>'></td>
</tr>
<tr>
    <td>Hours to lock the ad<br>after it is clicked</td>
    <td><input style='width: 100px' type=text name=hrlock value='<?php echo $row['hrlock'];?>'> (enter 0 to lock the ad forever)
</td>
</tr>
<tr>
    <td>Turing Numbers:</td>
    <td><input type=radio class=checkbox name=login <?php if ($row['login']=='off'){echo 'checked';};?> value=off>Off<br><input type=radio class=checkbox name=login value=on <?php if ($row['login']=='on'){ echo 'checked';}?>>On</td>
</tr>
<tr>
    <td>Site URL:<br></td>
    <td valign='top'><input style='width: <?php echo $input_width;?>' type=text size=40 name=site_url value="<?php echo htmlentities($row['site_url'], ENT_QUOTES);?>"></td>
</tr>
<tr>
    <td colspan='2'>(To place the username in the url put <b>#USERNAME#</b> where you would like it to appear)</td>
<tr>
    <td colspan=2>
    <hr>
	    <h2>Email Advertisement Text</h2>
    <center><textarea name="ad_text" rows=15 cols=80><?php echo safeentities($row['ad_text']);?></textarea></center>
	<center><input type="submit" name="add" value="Save Ad"></center>
</form>

<?php

if ($mode!='Create New')
{
    echo "<hr><h2>Preview</h2><br>";
    $row['ad_text']=str_replace("\n","<br>",safeentities($row['ad_text']));
    echo "$row[ad_text]<br><br><a href=".runner_url."?EA=$row[emailid] target=_blank>".runner_url."?EA=$row[emailid]</a><br><br>&lt;a href=".runner_url."?EA=$row[emailid]&gt;AOL Users&lt;/a&gt;";
}
echo "</td></tr></table>";
footer();

?>
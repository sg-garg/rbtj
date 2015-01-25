<?php
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');

if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);

$title='PTS Ad Manager';

admin_login();
include("ptsadmgr_menu.php");

$description=addslashes($_POST['description']);
$html=addslashes($_POST['html']);
$id=substr(preg_replace("([^a-zA-Z0-9])", "", $id),0,16);
$category=addslashes($_POST['category']);
$alt_text=addslashes($_POST['alt_text']);

$value=$value*100000;
if ($vtype=='cash'){
    $value=$value*admin_cash_factor;
}

if ($save==2 and $oldid)
{
    @mysql_query("update ".mysql_prefix."pts_ads set creditul='$creditul',id='$id',description='$description',image_url='$image_url',img_width='$img_width',img_height='$img_height',site_url='$site_url',html='$html',category='$category',run_quantity='$run_quantity',run_type='$run_type',alt_text='$alt_text',value='$value',vtype='$vtype' where ptsid=$oldid");
}
if ($save==1)
{
    $searchphrase='';
    @mysql_query("insert into ".mysql_prefix."pts_ads set  creditul='$creditul',id='$id',description='$description',image_url='$image_url',site_url='$site_url',html='$html',category='$category',run_quantity='$run_quantity',run_type='$run_type',alt_text='$alt_text',value='$value',vtype='$vtype',img_width='$img_width',img_height='$img_height'");
    $lastid=mysql_insert_id();
    @mysql_query("CREATE TABLE ".mysql_prefix."paid_signups_$lastid (
    username char(16) NOT NULL,
    time timestamp not null,
    unique username(username),
    KEY time(time)
    ) TYPE=MyISAM");
}
if ($mode=='Delete')
{
    @mysql_query("drop table ".mysql_prefix."paid_signups_$ptsid");
    @mysql_query("delete from ".mysql_prefix."signups_to_process where id=$ptsid");
    @mysql_query("delete from ".mysql_prefix."pts_ads where ptsid='$ptsid'");
}

echo "<a name=search></a><h2>Signup Ad Management</h2>

To place ads on your page use the following code. REPLACE: PUT_GROUP_HERE with the ad group name of the PTS ads you 
wish to display on that page: <b>&lt;?php get_pts_ad('PUT_GROUP_HERE');?&gt;</b><br>
<form action=ptsadmgr.php#search method=post>Search PTS Ads Database: (leave blank to list all ads) 
<input type=text name=searchphrase><input type=hidden name=get value=search>
<input type=submit value='Search'><br>
<a href=ptsadmgr.php#adform target=_top>Create a new ad campaign</a>
<br>";

if ($get=='search'){$searchphrase="%".$searchphrase."%";}
echo "<a href=oldptsads.php target=_oldptsads>List/Delete old paid to sign-up ads</a></form>";

if (!$searchphrase){$searchphrase='*****************************';}
$usersearchphrase=substr(preg_replace("([^a-zA-Z0-9])", "", $searchphrase),0,16);
if (!$usersearchphrase){$usersearchphrase='*****************************';}
$getads=@mysql_query("select * from ".mysql_prefix."pts_ads where description!='#PAID-START-PAGE#' and (ptsid like '$searchphrase' or id like '$usersearchphrase' or category like '$searchphrase' or description like '$searchphrase' or ptsid=LAST_INSERT_ID()) order by category,id,description");
echo "<table class='centered' border='1' cellpadding='2' cellspacing='0'>\n";
while($row=@mysql_fetch_array($getads))
{
    if($bgcolor == ' class="row1" ')
    {
        $bgcolor=' class="row2" ';
    }
    else
    {
        $bgcolor=' class="row1" ';
    }
    $row['value']=$row['value']/100000;
    if ($row['vtype']=='cash')
    {
        $row['value']=$row['value']/admin_cash_factor;
    }
    $sur="0";
    if($row['views'])
    {
        $sur=number_format($row['signups']/$row['views'],3)." to 1";
    }
    $row['time']=mytimeread($row['time']);


    echo "
    <tr $bgcolor>
    <td rowspan='2'>
        <table border='0' cellpadding='2' cellspacing='0'width='100%'>
        <tr>
            <td width='75' align='right'><b>PTS ID:</b></td>
            <td>{$row['ptsid']}</td>
        </tr>
        <tr>
            <td align='right'><b>Username:</b></td>
            <td><a href='viewuser.php?userid={$row['id']}' target='_user'>{$row['id']}</a></td>
        </tr>
        <tr>
            <td align='right'><b>Description:</b></td>
            <td><div style='white-space: nowrap; width:150px; overflow:hidden;' title='{$row['description']}'>{$row['description']}</div></td>
        </tr>
        <tr>
            <td align='right'><b>Group:</b></td>
            <td>{$row['category']}</a></td>
        </tr>
        <tr>
            <td align=center colspan=2>
            <form action='ptsadmgr.php#adform' method='POST'>
            <input type='hidden' name='searchphrase' value='$searchphrase'>
            <input type='hidden' name='ptsid' value='{$row['ptsid']}'>
            <input type='submit' name='mode' value='Delete'>
            <input type='submit' name='mode' value='Edit'>
            <input type=submit name=mode value='Copy'>
            </form>
        </td>
        </tr>
        </table>

    </td>

    <td> <table border='0' cellspacing='0' cellpadding='1'>
            <tr><td align='right'><b>Expire:</b><td><td><div style='white-space: nowrap; width:70px; overflow:hidden;' title='{$row['run_quantity']}'>{$row['run_quantity']}</div></td></tr>
            <tr><td align='right'><b>Type:</b><td><td>{$row['run_type']}</td></tr>
            <tr><td align='right'><b>Cr. Upline:</b><td><td>{$row['creditul']}</td></tr>
          </table></td>

    <td> <table border='0' cellspacing='0' cellpadding='1'>
            <tr><td align='right'><b>Views:</b><td><td>{$row['views']}</td></tr>
            <tr><td align='right'><b>Signups:</b><td><td>{$row['signups']}</td></tr>
          </table></td>

    <td> <table border='0' cellspacing='0' cellpadding='1'>
            <tr><td align='right'><b>Value:</b><td><td>$row[value]</td></tr>
            <tr><td align='right'><b>Type:</b><td><td>$row[vtype]</td></tr>
          </table></td>

    <td><b>Signup ratio:</b><br>$sur<br><b>Last shown:</b><br>{$row['time']}</td>
  </tr>
    <tr $bgcolor>
    <td colspan='2' align='center'>
              <form target='_clickcontest' action='clickcontest.php' method='POST'>
                Select <input type='text' name='draw' size='3' maxlength='3' value='5'> contest winners
                <input type='hidden' name='id' value='{$row['ptsid']}'>
                <input type='hidden' name='type' value='pts'> 
                <input type='submit' value='Pick'>
              </form>
    </td>
    <td colspan='2' align='center'>
            <form action='signuplog.php' method='POST' target='_signuplog'>
                <input type=hidden name=ptsid value='{$row['ptsid']}'>
                <input type=submit value='View Signup Log'>
            </form>
    </td>
    </tr>
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
if ($mode=='Edit' or $mode=='Copy'){
$savemode=2;
if ($mode=='Copy'){
$savemode=1;}
$row=@mysql_fetch_array(@mysql_query("select * from ".mysql_prefix."pts_ads where ptsid='$ptsid'"));
}
if (!$mode)
{
    $mode='Create New';
}
$row['value']=$row['value']/100000;
if (!$row['run_type'])
{
    $row['run_type']='signups';
    $row['vtype']='points';    
    $row['creditul']='YES';
}
if ($row['vtype']=='cash')
{
    $row['value']=$row['value']/admin_cash_factor;
}
$input_width = '450px';
?>
<a name="adform"></a><form action="ptsadmgr.php" method="POST" name="form">
<input type=hidden name=searchphrase value='<?php echo $searchphrase;?>'>
<input type="hidden" name="save" value="<?php echo $savemode;?>">
<?php 
if ($savemode==2){
?>
<input type=hidden name=oldid value='<?php echo $row['ptsid'];?>'>
<?php } ?>

<table class='centered' border=0 width=730 cellpadding='2' cellspacing='0'>
<tr>
    <td colspan=2><h2><center><?php echo $mode;?> Signup Advertisement</h2></center></td>
</tr>
<tr>
<td width='150'>Username:</td>
<td><input type="text" style='width: <?php echo $input_width;?>' name="id" value="<?php echo $row['id'];?>"></td>
</tr>
<tr>
<td>Ad Description:</td>
<td><input type="text" style='width: <?php echo $input_width;?>' name="description" value="<?php echo $row['description'];?>"></td>
</tr>
<tr>
<td>Ad Group:</td>
    <?php
    $adgroups_query = mysql_query("SELECT category
                                    FROM `".mysql_prefix."pts_ads`
                                    WHERE 1
                                    GROUP BY category
                                    ORDER BY category ASC");
    $adgroups = "<option value=''>Or pick existing group:</option>\n";
    while(list($adgroup) = mysql_fetch_array($adgroups_query))
    {
        if(!empty($adgroup))
        {
            $adgroups .= "<option value='$adgroup'>$adgroup</option>\n";
        }
    }
    ?>
    <td><input style='width:270px' type="text" name="category" id='adgroup' value="<?php echo $row['category'];?>">
    <?php
        echo "<select onChange=\"document.getElementById('adgroup').value=this.value\">\n$adgroups</select>\n";
    ?>
    </td>
</tr>
<tr>
<td>Duration Type:</td>
<td><input type=radio class=checkbox  name=run_type <?php if ($row['run_type']=='ongoing'){ echo "checked";}?> value=ongoing>Never Expire<br>
    <input type=radio class=checkbox name=run_type <?php if ($row['run_type']=='date'){ echo "checked";}?> value=date>Expire by certain date<br>
    <input type=radio class=checkbox name=run_type <?php if ($row['run_type']=='signups'){ echo "checked";}?> value=signups>Expire after so many sign-up<br>
    <input type=radio class=checkbox name=run_type <?php if ($row['run_type']=='views'){ echo "checked";}?> value=views>Expire after so many exposures
</td>
</tr>
<tr>
<td>Duration:</td>
<td><input type="text" name="run_quantity" value=<?php echo $row['run_quantity'];?>> (if using date to expire use the format YYYYMMDDHHMMSS)</td>
</tr>
<tr>
<td>Value:</td>
<td><input type=text name=value value=<?php echo $row['value'];?>></td>
</tr>
<tr>
<td>Value Type:</td>
<td><input type=radio class=checkbox name=vtype <?php if ($row['vtype']=='points'){echo "checked";}?> value=points>Points<br>
    <input type=radio class=checkbox name=vtype <?php if ($row['vtype']=='cash'){echo "checked";}?> value=cash>Cash
</td>
</tr>
<tr>
<td>Credit Upline:</td>
<td><input type=radio class=checkbox name=creditul <?php if ($row['creditul']=='YES') { echo "checked";}?> value=YES>Yes<br>
    <input type=radio class=checkbox name=creditul <?php if ($row['creditul']!='YES'){ echo "checked";}?> value=NO>No
</td>
</tr>
<tr>
<td colspan=2><hr><h2>Banner Advertisement</h2></td>
</tr>
<tr>
<td>Banner image URL:</td>
<td><input type=text style='width: <?php echo $input_width;?>' name=image_url value=<?php echo $row['image_url'];?>></td>
</tr>
<tr>
<td>Image width:</td>
<td><input type=text style='width: 80px'  name=img_width value=<?php echo $row['img_width'];?>> px</td>
</tr>
<tr>
<td>Image height:</td>
<td><input type=text  style='width: 80px' name=img_height value=<?php echo $row['img_height'];?>> px</td>
</tr>
<tr>
<td>Site URL: <br></td>
<td><input type=text name=site_url style='width: <?php echo $input_width;?>' value='<?php echo $row['site_url'];?>'></td>
</tr>
<tr>
<td colspan='2'>
To place the username in the url put <b>#USERNAME#</b> where you would like it to appear and <b>#PTSID#</b> for the ad id.
</td>
<tr>
<td>Alt Text:</td>
<td><input type=text name=alt_text size=40 value='<?php echo $row['alt_text'];?>'></td>
</tr>
<tr>
<td colspan=2>
<hr>
	<h2>HTML Advertisement:</h2>
<br>To place the username in the HTML ad, put <b>#USERNAME#</b> where you would like it to appear and <b>#PTSID#</b> for the ad id.
<br>
<center><textarea name="html" rows=15 cols=80><?php echo safeentities($row['html']);?></textarea></center><br>
	<input type="submit" name="add" value="Save Ad">
</form><hr>
<?php 
if ($mode!='Create New')
{
    if ($row['image_url'])
    { 
        $width='';
        $height='';

        if ($row['img_width'])
        {
            $width="width={$row['img_width']}";
        }
        if ($row['img_height'])
        {
            $height="height={$row['img_height']}";
        } 
        echo "<table border=0 cellpadding=0 cellspacing=0 bgcolor=ffffff>\n<tr><td><a href='{$row['site_url']}' target=_blank><img src={$row['image_url']} alt='{$row['alt_text']}' $width $height border=0></a>\n</td></tr></table>\n";
    }
    echo "<table border=0 cellpadding=0 cellspacing=0><tr><td>\n{$row['html']}\n</td></tr></table>\n";
}
echo "</td></tr></table>";
footer();


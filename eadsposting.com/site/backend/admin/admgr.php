<?php
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='Rotating Ad Manager';
admin_login();

$description=addslashes($_POST['description']);
$html=addslashes($_POST['html']);
$id=substr(preg_replace("([^a-zA-Z0-9])", "", $id),0,16);
$category=addslashes($_POST['category']);
$text_ad=addslashes($_POST['text_ad']);
$alt_text=addslashes($_POST['alt_text']);
if ($save==2 and $oldid)
{
    mysql_query("update ".mysql_prefix."rotating_ads set id='$id',description='$description',image_url='$image_url',img_width='$img_width',img_height='$img_height',site_url='$site_url',html='$html',category='$category',run_quantity='$run_quantity',run_type='$run_type',text_ad='$text_ad',alt_text='$alt_text',popupurl='$popupurl',popupwidth='$popupwidth',popupheight='$popupheight',popuptype='$popuptype' where bannerid=$oldid");
}
if ($save==1)
{
    $searchphrase='';
    mysql_query("insert into ".mysql_prefix."rotating_ads set id='$id',description='$description',image_url='$image_url',site_url='$site_url',html='$html',category='$category',run_quantity='$run_quantity',run_type='$run_type',text_ad='$text_ad',alt_text='$alt_text',popupurl='$popupurl',popupwidth='$popupwidth',popupheight='$popupheight',popuptype='$popuptype',img_width='$img_width',img_height='$img_height'");
}
if ($mode=='Delete')
{
    mysql_query("delete from ".mysql_prefix."rotating_ads where bannerid='$bannerid'");
}
echo "To place ads on your page use the following code. REPLACE: PUT_GROUP_HERE with the ad group name of the ads you wish to rotate<br><b>&lt;?php action('show_rotating_ad','PUT_GROUP_HERE');?&gt;</b><br>Or if you would like to display your ads on different sites that are not the Cash Crusader software package you can place this code:
<br><b>&lt;iframe src=".runner_url."?IFRAME=1&GA=PUT_GROUP_HERE space=0 vspace=0 width=468 height=60 marginwidth=0 marginheight=0 frameborder=0 scrolling=no&gt;<br>&lt;script language=\"JavaScript\" src=\"".runner_url."?JS=1&GA=PUT_GROUP_HERE\"&gt;<br>&lt;/script&gt;<br>&lt;/iframe&gt;<br></b>
<br><br>If you want to just rotate URLs from one simple link, select the redirect option when creating the ad and you can rotate those URLs from this one link<br><b>
".runner_url."?GA=PUT_GROUP_HERE</b>
<form action=admgr.php method=post>Search Rotating Ads Database: (leave blank to list all ads) 
<input type=text name=searchphrase>
<input type=hidden name=get value=search>
<input type=submit value='Search'><br>
<a href=admgr.php#adform target=_top>Create a new ad campaign</a><br>
<a href=oldrotatingads.php target=_oldrotatingads>List/Delete old rotating ads</a></form>";

if ($get=='search'){$searchphrase="%".$searchphrase."%";}

if (!$searchphrase){$searchphrase='*****************************';}
$usersearchphrase=substr(preg_replace("([^a-zA-Z0-9])", "", $searchphrase),0,16);

if (!$usersearchphrase){$usersearchphrase='*****************************';}

$getads=@mysql_query("select * from ".mysql_prefix."rotating_ads where bannerid like '$searchphrase' or id like '$usersearchphrase' or category like '$searchphrase' or description like '$searchphrase' or bannerid=LAST_INSERT_ID() order by category,id,description");
$count=mysql_num_rows($getads);

if($count > 0)
{
    echo "
<table class='centered' border='1' cellpadding='2' cellspacing='0'><tr><th>Ad ID</th><th>Username</th><th>Ad Description</th><th>Ad Group</th><th>Type</th><th>Expire at</th><th>Views</td><th>Clicks</th><th>CTR</th><th>Last Shown</th><td></td></tr>";
}

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
    $ctr="0";
    if($row['views'])
    {
        $ctr=number_format($row['clicks']/$row['views'],3)." to 1";
    }

    $row['time']=mytimeread($row['time']);
    echo "<form action=admgr.php#adform method=post><input type=hidden name=searchphrase value='$searchphrase'><input type=hidden name=bannerid value='$row[bannerid]'><tr $bgcolor><td>$row[bannerid]</td><td><a href='viewuser.php?userid=$row[id]'>$row[id]</a></td><td>$row[description]</td><td>$row[category]</td><td>$row[run_type]</td><td>$row[run_quantity]</td><td>$row[views]</td><td>$row[clicks]</td><td>$ctr</td><td>$row[time]</td><td><input type=submit name=mode value='Delete'><input type=submit name=mode value='Edit'><input type=submit name=mode value='Copy'></td></tr></form>";
}
if ($searchphrase AND $count > 0){
    echo "</table>";
    echo "<center><b>".$count." record(s) found</b></center><br>";
}
$savemode=1;
$row='';
if ($mode=='Edit' or $mode=='Copy'){
$savemode=2;
if ($mode=='Copy'){
$savemode=1;}
$row=@mysql_fetch_array(@mysql_query("select * from ".mysql_prefix."rotating_ads where bannerid='$bannerid'"));
}
$input_width = '450px';
if (!$mode){$mode='Create New';}
?>
<a name="adform"></a><form action="admgr.php" method="POST" name="form">
<input type=hidden name=searchphrase value='<?php echo  $searchphrase;?>'>
<input type="hidden" name="save" value="<?php echo $savemode;?>">
<?php if ($savemode==2){?><input type=hidden name=oldid value='<?php echo $row['bannerid'];?>'><?php } ?>
	<table class='centered' border=0 width=730>
    <tr><th colspan=2><h2><center><?php echo  $mode;?> Rotating Advertisement</center><h2></th></tr>
    <tr><td width='150'>Username:</td><td><input style='width: <?php echo $input_width;?>' type="text" name="id" value="<?php echo $row['id'];?>"></td></tr>
    <tr><td>Ad Description:</td><td><input style='width: <?php echo $input_width;?>' type="text" name="description" value="<?php echo $row['description'];?>"></td></tr>
    <tr>
    <td>Ad Group:</td>
        <?php
        $adgroups_query = mysql_query("SELECT category
                                        FROM `".mysql_prefix."rotating_ads`
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
    <tr><td>Duration Type:</td><td><select name=run_type><option <?php if ($row['run_type']=='ongoing'){ echo "selected";}?> value=ongoing>Never Expire<option <?php if ($row['run_type']=='date'){ echo "selected";}?> value=date>Expire by certain date<option <?php if ($row['run_type']=='clicks'){ echo "selected";}?> value=clicks>Expire after so many clicks<option <?php if ($row['run_type']=='views'){ echo "selected";}?> value=views>Expire after so many exposures</select></td></tr>
    <tr><td>Duration:</td><td><input type="text" name="run_quantity" value=<?php echo  $row['run_quantity'];?>> (if using date to expire use the format YYYYMMDDHHMMSS)</td></tr>
    <tr><td colspan=2><hr><h2>Banner Advertisement</h2></td></tr>
    <tr><td>Banner image URL:</td><td><input type=text style='width: <?php echo $input_width;?>' name=image_url value=<?php echo $row['image_url'];?>></td></tr>
    <tr><td>Image width:</td><td><input type=text name=img_width value=<?php echo  $row['img_width'];?>> px</td></tr>
    <tr><td>Image Height</td><td><input type=text name=img_height value=<?php echo  $row['img_height'];?>> px</td></tr>
    <tr><td>Site URL:<br></td><td><input style='width: <?php echo $input_width;?>' type=text name=site_url value='<?php echo $row['site_url'];?>'></td></tr>
    <tr><td colspan='2'>To place the username in the url put <b>#USERNAME#</b> where you would like it to appear</td></tr>
    <tr><td>Alt Text:</td><td><input style='width: <?php echo $input_width;?>' type=text name=alt_text value='<?php echo $row['alt_text'];?>'></td></tr>
    <tr><td>Text Ad:</td><td><input style='width: <?php echo $input_width;?>' type=text name=text_ad value="<?php echo  safeentities($row['text_ad']);?>"></td></tr>
    <tr><td colspan=2><hr>
    <h2>Iframe/Popunder/Popup/Redirect Advertisement</h2>
    Popup's and Popunders ads will never expire if the duration type is set to expire after so many clicks.</td></tr>
    <tr><td>Site URL:<br></td><td><input style='width: <?php echo $input_width;?>' type=text name=popupurl size=40 value='<?php echo $row['popupurl'];?>'></td></tr>
    <tr><td colspan='2'>To place the username in the url put <b>#USERNAME#</b> where you would like it to appear</td></tr>
    <tr><td>Window Width:</td><td><input type=text name=popupwidth value=<?php echo  $row['popupwidth'];?>> px</td></tr>
    <tr><td>Window Height:</td><td><input type=text name=popupheight value=<?php echo  $row['popupheight'];?>> px</td></tr>
    <tr><td>Window Type:</td><td><select name=popuptype><option <?php if ($row['popuptype']=='iframe'){ echo "selected";}?> value=iframe>Iframe<option <?php if ($row['popuptype']=='popunder'){ echo "selected";}?> value=popunder>Pop-Under<option <?php if ($row['popuptype']=='popup'){ echo "selected";}?> value=popup>Pop-Up<option <?php if ($row['popuptype']=='redirect'){ echo "selected";}?> value=redirect>Redirect</select></td></tr>
    <tr><td colspan=2><hr>
	<h2>HTML Advertisement</h2><br>
    To place the username in the url put <b>#USERNAME#</b> where you would like it to appear.<br>
    HTML Ads will never expire if the duration type is set to expire after so many clicks.<br>
    <center><textarea name="html" rows=15 cols=80><?php echo safeentities($row['html']);?></textarea><br>
	<input type="submit" name="add" value="Save Ad"><center>
</form><hr>
<?php
if ($mode!='Create New')
{
if ($row['image_url']){
    $width='';
    $height='';
    if ($row['img_width']){
    $width="width=$row[img_width]";}
    if ($row['img_height']){
    $height="height=$row[img_height]";} 
    echo "<table  border=0 cellpadding=0 cellspacing=0 bgcolor=ffffff><tr><td><a href=$row[site_url] target=_blank><img src=$row[image_url] alt='$row[alt_text]' $width $height border=0></a></td></tr></table>";
}
echo "<table border=0 cellpadding=0 cellspacing=0 $width $height>
<tr><td><a href=$row[site_url] target=_blank>$row[text_ad]</a></td></tr><table>";
echo "<table  border=0 cellpadding=0 cellspacing=0><tr><td>$row[html]</td></tr></table>";
if ($row['popupurl']){
$width='';
$height='';
if ($row['popupwidth']){
$width="width=$row[popupwidth],";}
if ($row['popupheight']){
$height="height=$row[popupheight],";}
$thetime="i".unixtime;
if ($row['popuptype']!='iframe'){
?>
<SCRIPT language='JavaScript'><!--
var iMyWidth = '<?php echo $width;?>';
var iMyHeight = '<?php echo $height;?>';
var iMyURL='<?php echo  $row['popupurl'];?>';
var iMyPopUp='<?php echo  $row['popuptype'];?>'; 
<?php echo  $thetime;?>=window.open(iMyURL,"<?php echo  $thetime;?>",iMyWidth + iMyHeight +"left=0,top=0,toolbars=0, scrollbars=0, location=0, statusbars=0, menubars=0, resizable=0");
if (iMyPopUp=='popunder'){
<?php echo  $thetime;?>.blur()
window.focus()
}
//-->
</SCRIPT>

<?php
}
else {
echo '<iframe src='.runner_url.'?REDIRECT='.rawurlencode($row['popupurl']).'&hash='.md5($row['popupurl'].key).' space=0 vspace=0 '.$width.' '.$height.' 
marginwidth=0 marginheight=0 frameborder=0 scrolling=no></iframe>';
}
}
}
echo "</td></tr></table>";
footer();

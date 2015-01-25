<?php
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='Auto/Manual Surf #'.$_GET['AS'].' Manager';
admin_login(); 
echo '<form method=get>You are currently viewing URLS for Auto/Manual Surf #'.$_GET['AS'].'<br>Switch to Auto/Manual Surf #<select name=AS><option value=1>1<option value=2>2<option value=3>3<option value=4>4</select> <input type=submit value=go></form>'; 

if($_GET['AS']==1)
    $_GET['AS']='';

$url=addslashes(rawurldecode($_POST['url']));
$id=substr(preg_replace("([^a-zA-Z0-9])", "", $id),0,16); 

if ($save==2 and $oldid and $oldurl)
{ 
    $oldurl=addslashes(rawurldecode($_POST['oldurl']));
    @mysql_query("update ".mysql_prefix."autosurf".$_GET['AS']." set url='$url',quantity='$quantity',active=$active,approved=$approved where username='$oldid' and url='$oldurl'");
} 

if ($save==1)
{ 
    $searchphrase='';
    @mysql_query("insert into ".mysql_prefix."autosurf".$_GET['AS']." set  quantity='$quantity',username='$id',url='$url',active='$active',approved='$approved'");
} 
if ($mode=='Approve')
{ 
    @mysql_query("update ".mysql_prefix."autosurf".$_GET['AS']." set approved=1 where username='$id' and url='$url'");
} 
if ($mode=='Delete')
{ 
    @mysql_query("delete from ".mysql_prefix."autosurf".$_GET['AS']." where username='$id' and url='$url'"); 
    @mysql_query("optimize table ".mysql_prefix."autosurf".$_GET['AS']); 
} 
$getads=@mysql_query("select * from ".mysql_prefix."autosurf".$_GET['AS']." where approved=0 order by username,url");
$count=mysql_num_rows($getads);

if($count > 0)
{
    echo "<b>Pending/Abuse URLs waiting review</b>"; 
    echo "<table cellpadding='2' cellspacing='0' class='centered' border='1'><tr><th>Username</th><th>Abuse reported by</th><th></th></tr>"; 
}else{
    echo "<b>No pending/abuse URLs waiting review</b>";
}
while($row=@mysql_fetch_array($getads))
{ 
    if($bgcolor == ' class="row1" ')
        $bgcolor=' class="row2" ';
    else
        $bgcolor=' class="row1" ';

    echo "<form method=post><input type=hidden name=id value='$row[username]'><input type='hidden' name=url value='".rawurlencode($row['url'])."'><tr $bgcolor><td><a href=viewuser.php?userid=$row[username] target=_viewuser>$row[username]</a></td><td><a href=viewuser.php?userid=$row[abuse] target=_viewuser>$row[abuse]</a></td><td align=center><input type =submit name=mode value=Approve><input type=submit name=mode value=Delete></td></tr><tr $bgcolor><td colspan=3><a href=astest.php?testurl=".rawurlencode($row['url'])." target=_autosurftest>$row[url]</a></td></tr></form>";

} 
if($count > 0)
{
    echo "</table><hr>"; 
}
?> 
<form method=post>Search Auto/Manual Surf Database: (leave blank to list all URLs) 
<input type=text name=searchphrase>
<input type=hidden name=get value=search>
<input type=submit value='Search'>
</form><br>
<a href=#adform target=_top>Add a URL</a><br>
<?php
if ($get=='search'){$searchphrase="%".$searchphrase."%";} 

if (!$searchphrase)
{
    $searchphrase='*****************************';
} 
$usersearchphrase=substr(preg_replace("([^a-zA-Z0-9])", "", $searchphrase),0,16); 
if (!$usersearchphrase)
{
    $usersearchphrase='*****************************';
}
 
$getads=@mysql_query("select * from ".mysql_prefix."autosurf".$_GET['AS']." where url like '$searchphrase' or username like '$usersearchphrase' or (username='$id' and url='$url') order by username,url"); 
$count=mysql_num_rows($getads);

if($count > 0)
    echo "<table cellpadding='2' cellspacing='0' class='centered' border='1'><tr><th>Username</th><th>Active</th><th>Approved</th><th>Hits</th><th>Expire</th><th>Last Shown</td><th></th></tr>"; 


while($row=@mysql_fetch_array($getads))
{ 
    if($bgcolor == ' class="row1" ')
        $bgcolor=' class="row2" ';
    else
        $bgcolor=' class="row1" ';

    if ($row['runad']>1){ 
    $row['time']=$row['runad'];} 
    if ($row['time']==0){ 
    $row['time']='00000000000000';} 
    $row['time']=mytimeread($row['time']);
    $active='No'; 
    $approved='No'; 
    if ($row['active']){ 
    $active='Yes';} 
    if ($row['approved']){ 
    $approved='Yes';} 
    echo "<form action=#adform method=post><input type=hidden name=searchphrase value='$searchphrase'><input type=hidden name=id value='$row[username]'><input type='hidden' name=url value='".rawurlencode($row['url'])."'><tr $bgcolor><td><a href=viewuser.php?userid=$row[username] target=_viewuser>$row[username]</a></td> 
    </td><td>$active</td><td>$approved</td><td>$row[hits]</td><td>$row[quantity]</td><td>$row[time]</td><td><input type=submit name=mode value=Edit><input type=submit name=mode value=Delete></td></tr><tr $bgcolor><td colspan=7><a href=astest.php?testurl=".rawurlencode($row['url'])." target=_autosurftest>$row[url]</a></td></tr></form>"; 

} 

 
if ($count > 0)
{
    echo "</table>"; 
    echo "<center><b>".$count." record(s) found</b></center><br>";
}

$savemode=1; 
$row=''; 
if ($mode=='Edit')
{ 
    $savemode=2; 
    $row=@mysql_fetch_array(@mysql_query("select * from ".mysql_prefix."autosurf".$_GET['AS']." where username='$id' and url='$url'")); 
} 
if (!$mode){$mode='Create New';} 
?> 
<a name="adform"></a><form method="POST" name="form"> 
<input type=hidden name=searchphrase value='<?php echo $searchphrase;?>'> 
<input type="hidden" name="save" value="<?php echo $savemode;?>"> 
<?php if ($savemode==2){?><input type=hidden name=oldid value='<?php echo $row['username'];?>'><input type=hidden name=oldurl value='<?php echo  rawurlencode($row['url']);?>'><?php } ?> 
	<table  border=0 width=400><tr><th colspan=2><?php echo  $mode;?> URL</th></tr><tr><td>Username:</td><td><input type="text" name="id" value="<?php echo $row['username'];?>"> 
        </td></tr><tr><td>URL:</td><td><input type="text" name="url" size=40 value="<?php echo $row['url'];?>"> 
        </td></tr><tr><td>Expires:</td><td><input type=text name=quantity value=<?php echo  $row['quantity'];?>> 
        </td></tr><tr><td>Active:</td><td><select name=active><option <?php if ($row['active']==1){ echo "selected";}?> value=1>Yes<option <?php if ($row['active']==0){ echo "selected";}?> value=0>No</select> 
        </td></tr><tr><td>Approved:</td><td><select name=approved><option <?php if ($row['approved']==1){echo "selected";}?> value=1>Yes<option <?php if ($row['approved']==0){echo "selected";}?> value=0>No</select> 
</td></tr><tr><td colspan=2>	<input type="submit" name="add" value="Save URL"> 
</form> 
<?php  
echo "</td></tr></table>";
footer(); 

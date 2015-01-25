<?php
include("../../functions.inc.php");

if(!empty($_POST)) extract($_POST,EXTR_SKIP);

$title='PLC Ad Selection Manager';
admin_login();
$item_name=addslashes($_POST['item_name']);
$default_value=addslashes($_POST['default_value']);

if ($mode=='Up 1'){
list($changeto)=@mysql_fetch_row(@mysql_query("select id from ".mysql_prefix."PLC_Images where id<$id order by id desc limit 1"));
@mysql_query("update ".mysql_prefix."PLC_Images set id=0 where id=$changeto");
@mysql_query("update ".mysql_prefix."PLC_Images set id=$changeto where id=$id");
@mysql_query("update ".mysql_prefix."PLC_Images set id=$id where id=0");
$id=$changeto;
}
if ($mode=='Up 10'){
for ($upcount=0;$upcount<10;$upcount++){
list($changeto)=@mysql_fetch_row(@mysql_query("select id from ".mysql_prefix."PLC_Images where id<$id order by id desc limit 1"));
@mysql_query("update ".mysql_prefix."PLC_Images set id=0 where id=$changeto");
@mysql_query("update ".mysql_prefix."PLC_Images set id=$changeto where id=$id");
@mysql_query("update ".mysql_prefix."PLC_Images set id=$id where id=0");
$id=$changeto;
}}
if ($mode=='Dn 1'){
list($changeto)=@mysql_fetch_row(@mysql_query("select id from ".mysql_prefix."PLC_Images where id>$id order by id asc limit 1"));
@mysql_query("update ".mysql_prefix."PLC_Images set id=0 where id=$changeto");
@mysql_query("update ".mysql_prefix."PLC_Images set id=$changeto where id=$id");
@mysql_query("update ".mysql_prefix."PLC_Images set id=$id where id=0");
$id=$changeto;
}
if ($mode=='Dn 10'){
for ($dncount=0;$dncount<10;$dncount++){
list($changeto)=@mysql_fetch_row(@mysql_query("select id from ".mysql_prefix."PLC_Images where id>$id order by id asc limit 1"));
@mysql_query("update ".mysql_prefix."PLC_Images set id=0 where id=$changeto");
@mysql_query("update ".mysql_prefix."PLC_Images set id=$changeto where id=$id");
@mysql_query("update ".mysql_prefix."PLC_Images set id=$id where id=0");
$id=$changeto;
}}
if ($changeto){
$jumpto=$id;
$jumptorow=@mysql_query("select id from ".mysql_prefix."PLC_Images where id<$id order by id desc limit 4");
while ($row=@mysql_fetch_row($jumptorow)){
$jumpto=$row[0];
}}


if ($save==2 and $oldid)
{
	mysql_query("update ".mysql_prefix."PLC_Images set item_type='$item_type',item_name='$item_name',default_value='$default_value',size='$size' where id=$oldid")  or die ("Query error");
}  
if ($save==1)
{
	mysql_query("insert into ".mysql_prefix."PLC_Images set item_type='$item_type',item_name='$item_name',default_value='$default_value',size='$size'")  or die ("Query error");
}
if ($mode=='Delete')
{
	mysql_query("delete from ".mysql_prefix."PLC_Images where id='$id'")  or die ("Query error");
}
?>
<table class='centered' border='1' cellpadding='2' cellspacing='0'>
<tr>
    <th>Move</th>
    <th>Item Type</th>
    <th>Item Name</th>
    <th>Default Value</th>
    <th>Size</th>
    <th>Action</th>
</tr>                                                                             
<?php
$rowcounter=0;
$getads=mysql_query("select * from ".mysql_prefix."PLC_Images order by id asc"); 
while($row=mysql_fetch_array($getads))
{
    if($bgcolor == ' class="row1" ')
    {
        $bgcolor=' class="row2" ';
    }
    else
    {
        $bgcolor=' class="row1" ';
    }
$rowcounter++;	
if ($row[id]==$id)		
	echo "<tr bgcolor='black'><th colspan=6><br></th></tr>";

	echo "
        <tr $bgcolor>
        <td align=center nowrap>";

if ($row[id]==$jumpto)
echo "<a name='MOVE'></a>";

	echo "<form action='#MOVE' method='post'><b>Line #$rowcounter</b><br><input type=hidden name=id value=$row[id]><input type=submit name=mode value='Up 1'><input type=submit name=mode value='Up 10'><br><input type=submit name=mode value='Dn 1'><input type=submit name=mode value='Dn 10'></form></td>
        <td>$row[item_type]</td><td>$row[item_name]</td>
        <td>$row[default_value]</td>
        <td>$row[size]</td>
        <td>
            <form action='#EDIT' method='post'>
            <input type=hidden name=id value='$row[id]'>
            <input type=submit name=mode value='Delete'>
            <input type=submit name=mode value='Edit'>
            <input type=submit name=mode value='Copy'>
            </form>
        </td>
        </tr>\n\n";
		if ($row[id]==$id)		
	echo "<tr bgcolor='black'><th colspan=6><br></th></tr>";
}

$count=mysql_num_rows($getads);
    echo "</table>\n";
    echo "<center><b>".$count." record(s) found</b></center><br>\n";
$savemode=1;
$row='';
if ($mode=='Edit' or $mode=='Copy')
{
	$savemode=2;
	if ($mode=='Copy'){
	$savemode=1;}
	$row=mysql_fetch_array(mysql_query("select * from ".mysql_prefix."PLC_Images where id='$id'"));
}
if ($mode!='Edit' && $mode!='Copy'){$mode='Create New';}


?>
<a name="EDIT"></a><form action="#EDIT" method="POST" name="form">
<input type="hidden" name="save" value="<?php echo $savemode;?>">
<?php

if ($savemode==2)
{?>
	<input type=hidden name=oldid value='<?php echo $row['id'];?>'>
	<?php 
} ?>
    <h2><?php echo $mode;?> PLC Item:</h2>
	<table border=0 width='600'>
    <tr>
        <td>Item Type:</td>
        <td>
        <select name='item_type'>

        <?php
        $mtrow=array('Display Table','Display Table Line Break','Sample Image','Input Check Box','Check Box Item');
        for($counter=0;$counter<count($mtrow);$counter++)
            {                
 
            echo '<option value="'.$mtrow[$counter].'" ';
            if (strtolower($row['item_type'])==strtolower($mtrow[$counter])) echo 'selected';

            echo '>'.$mtrow[$counter].'</option>';
            }                

        ?>
        </select>
        </td>
        </tr>
    <tr>
        <td>Item Name:</td>
        <td><input type="text" name="item_name" value="<?php echo htmlentities($row['item_name']);?>"></td>
    </tr>
    <tr>
        <td>Item Value:</td>
        <td><input type='TEXT' name='default_value' value="<?php echo htmlentities($row[default_value]);?>" /></td>
    </tr>
    <tr>
        <td>Size:</td>
    <td><input type="text" name="size" value=<?php echo round($row['size']);?>></td>
    </tr>
    <tr>
        <td colspan=2><input type="submit" name="add" value="Save PLC Item">

    </td>
    </tr>
    </table>
  </form>
<?php
footer();
?>

<?php
include("../../functions.inc.php");

$mtrow=array('Display Table','Display Table Line Break','Display Text','Input Selection','Selection Item','Input TRUE/FALSE','Input Text','Input Price');

if(!empty($_POST)) extract($_POST,EXTR_SKIP);

$title='PLC Layout Manager';
admin_login();
$item_name=addslashes($_POST['item_name']);
$default_value=addslashes($_POST['default_value']);

if ($mode=='Up 1'){
@mysql_query("update ".mysql_prefix."PLC_Layout set sort_order=sort_order-15 where id=$id");
$sort_order=$sort_order-15;
}
if ($mode=='Up 10'){
@mysql_query("update ".mysql_prefix."PLC_Layout set sort_order=sort_order-105 where id=$id");
$sort_order=$sort_order-105;
}
if ($mode=='Dn 1'){
@mysql_query("update ".mysql_prefix."PLC_Layout set sort_order=sort_order+15 where id=$id");
$sort_order=$sort_order+15;
}
if ($mode=='Dn 10'){
@mysql_query("update ".mysql_prefix."PLC_Layout set sort_order=sort_order+105 where id=$id");
$sort_order=$sort_order+105;
}


if ($_POST['mode']=='Update' && $_POST['id'])
{
	mysql_query("update ".mysql_prefix."PLC_Layout set item_type='$item_type',item_name='$item_name',default_value='$default_value',size='$size' where id=".$_POST['id'])  or die ("Query error");
}  
if ($_POST['mode']=='Insert Row' && $_POST['id'])
{
    $sort_order=$sort_order+5;
	@mysql_query("insert into ".mysql_prefix."PLC_Layout set sort_order=$sort_order,item_type='$item_type',item_name='$item_name',default_value='$default_value',size='$size'")  or die ("Query error");
	$id=mysql_insert_id();
}
if ($_POST['mode']=='New')
{
	mysql_query("insert into ".mysql_prefix."PLC_Layout set sort_order=999999999,item_type='$item_type',item_name='$item_name',default_value='$default_value',size='$size'")  or die ("Query error");
	$id=mysql_insert_id();
}
if ($_POST['mode']=='Delete')
{
	mysql_query("delete from ".mysql_prefix."PLC_Layout where id='$id'")  or die ("Query error");
}

if ($_POST['mode']){
$jumpto=$id;
$jumptorow=@mysql_query("select id from ".mysql_prefix."PLC_Layout where sort_order<$sort_order order by sort_order desc limit 4");
while ($row=@mysql_fetch_row($jumptorow)){
$jumpto=$row[0];
}}

?>
<table class='centered' border='1' cellpadding='2' cellspacing='0' width=100%>
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
$getads=mysql_query("select * from ".mysql_prefix."PLC_Layout order by sort_order asc"); 
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
$sort_order=$rowcounter*10;
mysql_query('update PLC_Layout set sort_order='.$sort_order.' where id='.$row['id']);

if ($row[id]==$id)		
	echo "<tr bgcolor='black'><th colspan=6><br></th></tr>";

	echo "
        <tr $bgcolor>
        <td align=center nowrap>";

if ($row[id]==$jumpto)
echo "<a name='MOVE'></a>";

	echo "<form action='#MOVE' method='post'><b>Line #$rowcounter</b><br><input type=hidden name=id value=$row[id]><input type=hidden name=sort_order value=$sort_order><input type=submit name=mode value='Up 1'><input type=submit name=mode value='Up 10'><br><input type=submit name=mode value='Dn 1'><input type=submit name=mode value='Dn 10'></td>
        <td align=center><select name='item_type'>";

        for($selectcounter=0;$selectcounter<count($mtrow);$selectcounter++)
            {                
 
            echo '<option value="'.$mtrow[$selectcounter].'" ';
            if (strtolower($row['item_type'])==strtolower($mtrow[$selectcounter])) echo 'selected';

            echo '>'.$mtrow[$selectcounter].'</option>';
            }                

        echo "</select>
        </td>		
		<td align=center><input type='TEXT' size=20 name='item_name' value='".safeentities($row[item_name])."'</td>
        <td align=center><input type='TEXT' size=20 name='default_value' value='".safeentities($row[default_value])."' /></td>
        <td align=center><input type='TEXT' size=3 name='size' value='".round($row['size'])."'></td>
        <td align=center>
			<input type=submit name=mode value='Update'>
            <input type=submit name=mode value='Insert Row'>
			<input type=submit name=mode value='Delete'>
            </form>
        </td>
        </tr>\n\n";

		if ($row[id]==$id)		
	echo "<tr bgcolor='black'><th colspan=6><br></th></tr>";
}

$count=mysql_num_rows($getads);
    echo "</table>\n";
    echo "<center><b>".$count." record(s) found</b></center><br>\n";

$row='';

?>
<a name="EDIT"></a><form action="#EDIT" method="POST" name="form">
<input type="hidden" name="mode" value="New">

    <h2>Create New PLC Item:</h2>
	<table border=0 width='600'>
    <tr>
        <td>Item Type:</td>
        <td>
        <select name='item_type'>

        <?php
        
        for($counter=0;$counter<count($mtrow);$counter++)
            {                
 
            echo '<option value="'.$mtrow[$counter].'" ';
            echo '>'.$mtrow[$counter].'</option>';
            }                

        ?>
        </select>
        </td>
        </tr>
    <tr>
        <td>Item Name:</td>
        <td><input type="text" name="item_name" value="<?php echo safeentities($row['item_name']);?>"></td>
    </tr>
    <tr>
        <td>Item Value:</td>
        <td><input type='TEXT' name='default_value' value="<?php echo safeentities($row[default_value]);?>" /></td>
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

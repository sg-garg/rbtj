<?php
include("../../functions.inc.php");

if(!empty($_POST)) extract($_POST,EXTR_SKIP);

$title='PLC Data Set Manager';
admin_login();

$items_checked=0;
$item_name=addslashes($_POST['item_name']);

 if ($_POST['default_value'])
    {
        reset($_POST['default_value']);
        $default_value='||';
        while (list($key, $value) = each($_POST['default_value']))
        {
            $value=strtolower(addslashes($value));
            $default_value=$default_value.$value.'||';
	    }
	}

if ($save==2 and $oldid)
	mysql_query("update ".mysql_prefix."PLC_Datasets set item_name='$item_name',default_value='$default_value' where id=$oldid")  or die ("Query error");
  
if ($save==1)
	mysql_query("insert into PLC_Datasets set item_name='$item_name',default_value='$default_value'")  or die ("Query error");

if ($mode=='Delete')
	mysql_query("delete from ".mysql_prefix."PLC_Datasets where id='$id'")  or die ("Query error");

?>
<table width=100% class='centered' border='1' cellpadding='2' cellspacing='0'>
<tr>
    <th>Data Set</th>
    <th>Action</th>
</tr>                                                                             
<?php

$getads=mysql_query("select * from ".mysql_prefix."PLC_Datasets order by item_name asc"); 
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

	if ($row[id]==$id)		
		echo "<tr bgcolor='black'><th colspan=2><br></th></tr>";

	echo "
        <tr $bgcolor>
        <td>$row[item_name]</td>
        <td align=center>
            <form action='#EDIT' method='post'>
            <input type=hidden name=id value='$row[id]'>
            <input type=submit name=mode value='Delete'>
            <input type=submit name=mode value='Edit'>
            <input type=submit name=mode value='Copy'>
            </form>
        </td>
        </tr>\n\n";
		
	if ($row[id]==$id)		
		echo "<tr bgcolor='black'><th colspan=2><br></th></tr>";
		
}

$count=mysql_num_rows($getads);
    echo "</table>\n";
    echo "<center><b>".$count." record(s) found</b></center><br>\n";
$savemode=1;
$row='';
if ($mode=='Edit' or $mode=='Copy')
{
	$savemode=2;
	
	if ($mode=='Copy')
	$savemode=1;
	
	$row=mysql_fetch_array(mysql_query("select * from PLC_Datasets where id='$id'"));
	$dataset=$row['default_value'];
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
    <h2><?php echo $mode;?> Data Set:</h2>
	<table border=0 width='600'>
    <tr>
        <td>Data Set Name:</td>
        <td><input type="text" name="item_name" value="<?php echo $row['item_name'];?>"></td>
    </tr>
    <tr><td colspan=2>Select the fields for this data set</td></tr>
    </table>
<div align=center>
<table border='0'>
<tr>
    <?php 
    $getkeys=@mysql_query("select item_name from PLC_Layout where (item_type like '%Input%' or item_type like '%Selection%') and item_type!='Input Selection' order by id asc");
    while($row=@mysql_fetch_row($getkeys))
    {     
    $PLC[$row[0]]='Set';
    }
    
	eval(system_value('PLC_Function'));

    if ($PLC)
    {
	    $PLC=array_keys($PLC);
	    $rows=(count($PLC)+1)/5;
	    natcasesort($PLC);
        reset($PLC);
        $default_value='||';
		echo "<td nowrap valign='top'>";
        while (list($key, $value) = each($PLC))
    	{
        $line++;
        echo "\n<input type='checkbox' class='checkbox' name='default_value[$idx]' value=\"".safeentities($value)."\" ";
        check_dataset_item($value,"checked");
        echo ">$value<br>";
        $idx++;
        if ($line>$rows)
        {   
            echo "</td><td nowrap valign='top'>"; 
            $line=0;
        }
    	}
	}

	
    ?>
</tr>
</table>
  When this dataset was opened there were <? echo $items_checked;?> items selected<br /> 
  <input type="submit" name="add" value="Save Data Set"></div>

  </form>
<?php

function check_dataset_item($what = 'blank', $d = 'word')
{
    global $dataset;
	global $items_checked;

    if ($dataset)
    {
        if (strpos('.'.strtolower($dataset),
        '||' . strtolower($what). '||'))
        {
            if ($d == 'checked')
            {
                echo "checked";
				$items_checked++;
                return;
            }
            
            if ($d == 'selected')
            {
                echo "selected";
                return;
            }

            if ($d == 'word')
            {
                echo $what;
            }

            if ($d == 'return')
            {
                return (1);
            }
        }
    }

    return (0);
}

footer();
?>

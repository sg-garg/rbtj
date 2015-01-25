<?php
include("../../functions.inc.php");
$title='Download Data Sets';

if ($_POST['image_id'])
$noheader=1;
 
admin_login();


if ($_POST['image_id'])
	{
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-disposition: attachment; filename='.$_POST['image_name'].'.csv;');

	@mysql_query('update PLC_Images set required="'.$_POST['required'].'",data_set="'.$_POST['dataset'].'" where id="'.$_POST['image_id'].'"');
	$users=@mysql_query('select * from Dealer_Ad_Selection left join Dealer_PLCs on (Dealer_PLCs.username=Dealer_Ad_Selection.username) where Dealer_Ad_Selection.item_name="'.$_POST['image_name'].'" and Dealer_PLCs.modified_date is not Null group by Dealer_PLCs.username');
    list($dataset)=@mysql_fetch_array(@mysql_query('select default_value from PLC_Datasets where id="'.$_POST['dataset'].'"'));
	$dataset=$dataset.'||_1||';

	//Select the PLC layout 
	$plcrows=@mysql_query("Select * from PLC_Layout order by sort_order asc");

	while ($userrows=@mysql_fetch_array($users))
	{
		$PLC='';
		$PRICE='';

//Start loop that will process each line of the PLC layout 
	while ($row=@mysql_fetch_array($plcrows))
		{

//Clean up any extra white spaces from the items default value
	$row['default_value']=trim($row['default_value']);

//Check users PLC and get value for this row if they have one and set it as the default value

		if (strpos('.'.$row['item_type'],'Input'))
			{
    		list($plcvalue,$found)=@mysql_fetch_row(@mysql_query('select item_value,username from Dealer_PLCs where username="'.$userrows['username'].'" and item_name="'.$row['item_name'].'"'));
			if ($found && !strpos('.'.$row['item_type'],'Selection'))
				$row['default_value']=$plcvalue;
				$row['default_value']=str_replace('"','“',$row['default_value']);
				$plcvalue=str_replace('"','“',$plcvalue);
			}


	
 		if ($row['item_type']=='Input TRUE/FALSE')
			{
			$PLC[$row['item_name']]=$row['default_value'];
			}	
		
		elseif ($row['item_type']=='Input Selection')
			{
				for ($selectcount=0;$selectcount<$row['size'];$selectcount++)
				{
				$select=@mysql_fetch_array($plcrows);
			    
				if ($plcvalue==$select['item_name'])
					$selected='TRUE';
				else
					$selected='FALSE';
				
      	    	$PLC[$select['item_name']]=$selected;
				}
		
			}	
			
		elseif ($row['item_type']=='Input Text')
			{
			$PLC[$row['item_name']]=$row['default_value'];
			}
		
			elseif ($row['item_type']=='Input Price')
			{
			$PLC[$row['item_name']]=$row['default_value'];
			$PRICE[$row['item_name']]='$';
			}
			
		}
		
    @mysql_data_seek($plcrows,0);
    @eval(system_value('PLC_Function'));

	$delimiter='';
    $PLC['_1']=$userrows['username'];
	if (!$header_shown)
	{
	reset($PLC);
	foreach($PLC as $key=>$value)
		{
		if (strpos('.'.strtolower($dataset),
        '||' . strtolower($key). '||'))
			{
			echo $delimiter.'"'.$key.'"';
			$delimiter=',';
			}
		}
    $header_shown=1;
	echo "\n";
	}
	
	$delimiter='';
	reset($PLC);
	foreach($PLC as $key=>$value)
		{
		if (strpos('.'.strtolower($dataset),
        '||' . strtolower($key). '||'))
			{
			echo $delimiter.'"'.$PRICE[$key].$value.'"';
			$delimiter=',';
			}
		}
	echo "\n";
	}
	exit;
	}	

?>
<table width=100% class='centered' border='1' cellpadding='2' cellspacing='0'>
<tr>
	<th>Status</th>
    <th>Item Name</th>
    <th>Description</th><th>Required</th>
    <th>Data Set</th>
    <th>Total</th>
    <th>Action</th>
</tr>                                                                             
<?php

$nl="\n";
$adimages=@mysql_query('select * from PLC_Images where item_type="Check Box Item" order by id');
$datasets=@mysql_query('select id,item_name from PLC_Datasets order by id');

$count=0;

while ($row=@mysql_fetch_array($datasets))
	{
	$ds[$count]['id']=$row['id'];
	$ds[$count]['name']=$row['item_name'];
	$count++;
	}

while ($row=@mysql_fetch_array($adimages))
	{
	
	    if($bgcolor == ' class="row1" ')
        	$bgcolor=' class="row2" ';	
    	else
        	$bgcolor=' class="row1" ';
	
	echo '<form method=post><tr '.$bgcolor.'><td>'.$row['status'].'</td><td>'.$row['item_name'].'</td>';
	echo '<td>'.$row['default_value'].'</td>'.$nl;
    $checked='';
	if ($row['required'])
	$checked='checked';
	echo '<td align=center><input type=checkbox value=1 name=required '.$checked.'></td>'.$nl;
	echo '<td align=center><input type=hidden name="image_id" value="'.$row['id'].'">';
	echo '<input type=hidden name="image_name" value="'.$row['item_name'].'">';
	echo '<select name="dataset">';

	for ($i=0;$i<count($ds);$i++)
		{
		
		$selected='';
		
		if ($row['data_set']==$ds[$i]['id'])
		$selected='selected';
		
		echo '<option value="'.$ds[$i]['id'].'" '.$selected.'>'.$ds[$i]['name'];
		}
		
	echo '</select>';
	echo '</td>'.$nl;
	$total=@mysql_num_rows(@mysql_query('select * from Dealer_Ad_Selection left join Dealer_PLCs on (Dealer_PLCs.username=Dealer_Ad_Selection.username) where Dealer_Ad_Selection.item_name="'.$row['item_name'].'" and Dealer_PLCs.modified_date is not Null group by Dealer_PLCs.username'));
	echo '<td align=center>'.$total.'</td>';
	echo '<td align=center><input type=submit value="Assign Data Set and Download"></td></tr></form>'.$nl.$nl;
	
	}
echo "</table>";

footer(); 
?>

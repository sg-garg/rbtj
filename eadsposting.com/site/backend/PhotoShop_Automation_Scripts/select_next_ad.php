<?php
include("../conf.inc.php");
include("../functions.inc.php");
        mysql_query('delete from Dealer_Ads_To_Process where modified_date<now()-interval 1 week');
	$query='select * from Dealer_Ads_To_Process where item_name like "%.psd" and modified_date<now()-interval 6 hour limit 1';
        $simplepsd=@mysql_fetch_array(@mysql_query($query));
        if ($simplepsd['item_name']){
        @mysql_query('update Dealer_Ads_To_Process set modified_date=now() where item_name="'.mysql_real_escape_string($simplepsd['item_name']).'"');
        @mysql_query("replace into system_values set name='last_plc_time',value='".unixtime."'");
        @mysql_query("replace into system_values set name='last_plc_image',value='".mysql_real_escape_string(basename($simplepsd['item_name'],'.psd'))."'");

        $users=@mysql_query('select * from Dealer_Ads_To_Process where item_name="'.mysql_real_escape_string($simplepsd['item_name']).'"'); 
        @mysql_query('replace into system_values set name="last_plc_count",value="'.@mysql_num_rows($users).'"');

        exec('/usr/bin/grep -a varName '.pages_dir.'../Temp/PSDs/'.$simplepsd['item_name'],$vars);
        $dataset='|_1|';
        $plcrows[0]='_1';
        $counter=1;
        for ($a=0;$a<count($vars);$a++){
        list($junk,$var)=explode('"',$vars[$a]);
        if (!strpos('.'.$dataset,'|'.$var.'|')){
        $dataset='|'.$var.'|'.$dataset;
        $plcrows[$counter]=$var;
        $counter++; 
	}}
        $total=round(@mysql_num_rows($users));
	$psd=$simplepsd['item_name'];
	$pathname=str_replace('/','-',str_replace('_','-',$psd));
    copy (pages_dir.'../Temp/PSDs/'.$psd,pages_dir.'../Temp/PSDs/current_build_temp/'.$pathname);
	echo 'Processing ad: '.$psd.'<br>';
        echo 'Dealers needing this ad: '.$total.'<br>';        
        while ($userrows=@mysql_fetch_array($users))
        {
        $PLC='';
	for($a=0;$a<count($plcrows);$a++){
          list($plcvalue,$found)=@mysql_fetch_row(@mysql_query('select item_value,username from Dealer_PLCs where username="'.$userrows['username'].'" and item_name="'.$plcrows[$a].'" and Path="'.mysql_real_escape_string($simplepsd['item_name']).'"'));
          $PLC[$plcrows[$a]]=$plcvalue;
          }
          $PLC['_1']=$userrows['Campaign_ID'];
        $delimiter='';
        if (!$header_shown)
        {
        reset($PLC);
        foreach($PLC as $key=>$value)
                {
                        $file.=$delimiter.'"'.$key.'"';
                        $delimiter=',';
                }
         $header_shown=1;
	$file.= "\n";
	}
        $delimiter='';
        reset($PLC);
        foreach($PLC as $key=>$value)
                {
                        $file.= $delimiter.'"'.$value.'"';
                        $delimiter=',';
                }
        $file.="\n";
        }
   
        $PLC['_1']='ZZZZZZZZ_PROCESS_COMPLETE_TRIGGER';
        $delimiter='';
        reset($PLC);
        foreach($PLC as $key=>$value)
                {
                        $file.= $delimiter.'"'.$value.'"';
                        $delimiter=',';
                }
        $file.="\n";
        file_put_contents('../../Temp/build_dataset.csv',$file);
	echo '<a href=file:///PSDs/current_build_temp/'.$pathname.'>'.$pathname.'</a>';
	exit;
 	}
	
        $query='select * from Dealer_Ads_To_Process left join PLC_Images on (PLC_Images.item_name=Dealer_Ads_To_Process.item_name) where Dealer_Ads_To_Process.item_name is not null and PLC_Images.item_name is not null and PLC_Images.item_type="Check Box Item" group by Dealer_Ads_To_Process.item_name order by last_process asc';
    $adimages=@mysql_query($query);
	$row=@mysql_fetch_array($adimages);
    $psd=trim($row['item_name']);
    @mysql_query('update PLC_Images set last_process="'.unixtime.'" where item_name="'.$psd.'"');
	$file='';
	@mysql_query("replace into system_values set name='last_plc_time',value='".unixtime."'");
	@mysql_query("replace into system_values set name='last_plc_image',value='".mysql_real_escape_string($psd)."'");
	
	$users=@mysql_query('select * from Dealer_Ads_To_Process left join Dealer_PLCs on (Dealer_PLCs.username=Dealer_Ads_To_Process.username) where Dealer_Ads_To_Process.item_name="'.$row['item_name'].'" and Dealer_PLCs.modified_date is not Null group by Dealer_PLCs.username');
        //list($dataset)=@mysql_fetch_array(@mysql_query('select default_value from PLC_Datasets where id="'.$row['data_set'].'"'));

        exec('/usr/bin/grep -a varName '.pages_dir.'../Temp/PSDs/Ads/'.$psd.'.psd',$vars);
	$dataset='||_1||';
        for ($a=0;$a<count($vars);$a++){
        list($junk,$var)=explode('"',$vars[$a]);
        if (!strpos('.'.$dataset,'|'.$var.'|')){
        $dataset=$dataset.$var.'||';
        }}


	//$dataset=$dataset.'||_1||';
	@mysql_query('replace into system_values set name="last_plc_count",value="'.@mysql_num_rows($users).'"');
        echo 'Processing ad: '.$psd.'<br>';
	$total=round(@mysql_num_rows($users));
	echo 'Dealers needing this ad: '.$total.'<br>';
	//Select the PLC layout 
	$plcrows=@mysql_query("Select * from PLC_Layout order by sort_order asc");

	while ($userrows=@mysql_fetch_array($users))
	{
		$PLC='';
		$PRICE='';
        $PLC['_1']='unset';
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
			    if (!$plcvalue && $selectcount===0)
				    $selected='TRUE';
				elseif ($plcvalue==$select['item_name'])
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
			$file.=$delimiter.'"'.$key.'"';
			$delimiter=',';
			}
		}
    $header_shown=1;
	$file.= "\n";
	}
	
	$delimiter='';
	reset($PLC);
	foreach($PLC as $key=>$value)
		{
		if (strpos('.'.strtolower($dataset),
        '||' . strtolower($key). '||'))
			{
			$file.= $delimiter.'"'.$PRICE[$key].$value.'"';
			$delimiter=',';
			}
		}
	$file.="\n";
	}
    
	$PLC['_1']='ZZZZZZZZ_PROCESS_COMPLETE_TRIGGER';
	$delimiter='';
	reset($PLC);
	foreach($PLC as $key=>$value)
		{
		if (strpos('.'.strtolower($dataset),
        '||' . strtolower($key). '||'))
			{
			$file.= $delimiter.'"'.$PRICE[$key].$value.'"';
			$delimiter=',';
			}
		}
	$file.="\n";
	if ($total){
	@mysql_query('update PLC_Images set status="Failed" where status="Processing"');
    @mysql_query('update PLC_Images set status="Processing" where item_name="'.$psd.'"');
	file_put_contents('../../Temp/build_dataset.csv',$file);
	echo '<a href=file:///PSDs/Ads/'.$psd.'.psd>Ads/'.$psd.'.psd</a>';
	}
?>

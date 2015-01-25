<?


$cronjobs[]=array('classname'=>'cc_admin_plc_photoshop_triggers');

class cc_admin_plc_photoshop_triggers 

	{
	

var $class_name='cc_admin_plc_photoshop_triggers';
var $minutes=4;

	function cronjob()
		{

		$found_jpg=0;
		$found_psd=0;
		$started_psd=0;
		$started_jpg=0;
		foreach (glob('../../Temp/Temp_JPGs/*.jpg') as $filename) 
			{
    		$started_jpg=1;
			}


		if (!$started_jpg && @file_exists('../../Temp/Triggers/Make_JPGs/trigger'))
			{
			@rt('Import_Dataset');
			@rt('Make_JPGs');
			
			echo 'Touching Make JPGs Trigger<br>';
			st('Make_JPGs');
			}


		foreach (glob('../../Temp/Temp_PSDs/*.psd') as $filename) 
			{
    		$started_psd=1;
			}
		
		if (!$started_psd && @file_exists('../../Temp/Triggers/Import_Dataset/trigger'))
			{
			@rt('Import_Dataset');
			@rt('Make_JPGs');
			
			echo 'Touching Import Dataset Trigger<br>';
			st('Import_Dataset');
			}
		
		foreach (glob('../../Temp/Temp_PSDs/ZZZZZZZZ_PROCESS_COMPLETE_TRIGGER_*') as $filename) 
			{
    		$found_psd=1;
			}

		if ($found_psd && !@file_exists('../../Temp/Triggers/Make_JPGs/trigger'))
			{
			echo 'Touching Make JPGs Trigger<br>';
			@rt('Import_Dataset');
			st('Make_JPGs');
			}
			
		foreach (glob('../../Temp/Temp_JPGs/ZZZZZZZZ_PROCESS_COMPLETE_TRIGGER_*.jpg') as $filename) 
			{
    		$found_jpg=1;
			unlink($filename);
			}
			
		if ($found_jpg)
			{
			
			@rt('Import_Dataset');
			@rt('Make_JPGs');
			
			foreach (glob('../../Temp/Temp_PSDs/*.psd') as $filename) 
				{
    		unlink($filename);
				}
				
			foreach (glob('../../Temp/Temp_JPGs/*.psd') as $filename) 
				{
    		unlink($filename);
				}
		    foreach (glob('../../Temp/Temp_JPGs/*.jpg') as $filename) 
				{
			$basename=basename($filename,'.jpg');
			list($username,$imagename)=explode('_',$basename);
			if (!$width) {
			list($width, $height, $type, $attr) = getimagesize("$filename");
			@mysql_query('replace into PLC_Image_Sizes set width='.$width.',height='.$height.',item_name="'.$imagename.'"');
			}
			
			
			@mysql_query('update PLC_Images set status="Completed" where item_name="'.$imagename.'"');
			cronjob_query('delete from Dealer_Ads_To_Process where item_name="'.$imagename.'" and username="'.$username.'"');
	                cronjob_query('delete from Dealer_Ads_To_Process where longname like "'.$imagename.'.psd" and Campaign_ID="'.$username.'"');
			$adddir='To_Review';
	                if (substr($username,-3)=='-PC'){
                        $adddir='To_Publish'; 
                        $basename=$username.'/'.$imagename;
			rename($filename,'../../Temp/Temp_JPGs/'.$adddir.'/'.$basename.'.jpg');}
			else {
			imageCompression($filename,800,'../../Temp/Temp_JPGs/'.$adddir.'/'.$basename.'.jpg');
			unlink($filename);}
				}
			echo 'Touching Import Dataset Trigger<br>';
			st('Import_Dataset');	
					
			}
			
			if ( (!@file_exists('../../Temp/Triggers/Make_JPGs/trigger') && !@file_exists('../../Temp/Triggers/Import_Dataset/trigger')) || (@file_exists('../../Temp/Triggers/Make_JPGs/trigger') && !$found_psd))
			{
			@rt('Import_Dataset');
			@rt('Make_JPGs');
			
			
			echo 'Touching Import Dataset Trigger<br>';
			st('Import_Dataset');
			
			}
		
		}

	}
function st($trigger)
	{
	touch('../../Temp/Triggers/'.$trigger.'/trigger');
	touch('../../Temp/Triggers/'.$trigger.'/trigger.start.action');
	}


function rt($trigger) 
	{
	foreach (glob('../../Temp/Triggers/'.$trigger.'/trigger*') as $filename) 
			{
			@unlink($filename);
			}
	}		
	
return;
?>

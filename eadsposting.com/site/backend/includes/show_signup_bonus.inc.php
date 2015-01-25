<?
if (!defined('version')){
exit;} 


$a=get_args('$|2|.| and | point',$args);

	if (cashsignbonus>0 and pointsignbonus>0){
		echo $a[0].number_format(cashsignbonus,$a[1],$a[2],'').$a[3].number_format(pointsignbonus,0).$a[4].' '.sbdescription; 
	}
	else {
		if (cashsignbonus>0)
			echo $a[0].number_format(cashsignbonus,$a[1],$a[2],'').' '.sbdescription;
		else
			echo number_format(pointsignbonus,0).$a[4].' '.sbdescription;
	}
return 1;
?>

<?
if (!defined('version')){
exit;} 

$a=get_args('$|2|.| and | point',$args);

	if (cashreferbonus>0 and pointreferbonus>0){
		echo $a[0].number_format(cashreferbonus,$a[1],$a[2],'').$a[3].number_format(pointreferbonus,0).$a[4].' '.rbdescription; 
	}
	else {
		if (cashreferbonus>0)
			echo $a[0].number_format(cashreferbonus,$a[1],$a[2],'').' '.rbdescription;
		else
			echo number_format(pointreferbonus,0).$a[4].' '.rbdescription;
	}
return 1;
?>

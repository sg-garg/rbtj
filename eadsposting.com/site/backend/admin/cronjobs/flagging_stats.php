<?

$cronjobs[]=array('classname'=>'cc_admin_flagging_stats');

class cc_admin_flagging_stats 

	{
	

var $class_name='cc_admin_flagging_stats';
var $minutes=10;

	function cronjob()
		{
        $cities=@mysql_query('select City_Code from LDF_Flagging_Data.Exclude_List');
		while ($row=@mysql_fetch_array($cities)){
	    $query='select count(*) from LDF_Flagging_Data.URLs where Flagged=127 and URL like "%'.$row['City_Code'].'%" and Timestamp<"'.mysqldate.'"';
		list($total)=@mysql_fetch_row(@mysql_query($query));
		$query='insert into LDF_Flagging_Data.Stats set Flag_Type="Removed",City="'.$row['City_Code'].'",Total='.$total;
		@mysql_query($query);
		$query='select count(*) from LDF_Flagging_Data.URLs where Flagged>100 and Flagged<126 and URL like "%'.$row['City_Code'].'%" and Timestamp<"'.mysqldate.'"';
		list($total)=@mysql_fetch_row(@mysql_query($query));
		$query='insert into LDF_Flagging_Data.Stats set Flag_Type="Best Of",City="'.$row['City_Code'].'",Total='.$total;
		@mysql_query($query);
		}
		@mysql_query('delete from LDF_Flagging_Data.URLs where Flagged=127 and Timestamp<"'.mysqldate.'"');
		@mysql_query('update LDF_Flagging_Data.URLs set Flagged=126 where Flagged<126 and Flagged>100 and Timestamp<"'.mysqldate.'"');
		}

	}
	

return;
?>

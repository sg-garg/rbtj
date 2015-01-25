<?
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');

$cronjobs[]=array('classname'=>'cc_admin_level_count');

class cc_admin_level_count {

var $class_name='cc_admin_level_count';
var $minutes=5;

function cronjob(){

$result=cronjob_query('select username from '.mysql_prefix.'users where rebuild_stats_cache=1 and account_type!="canceled" order by signup_date'); 

$levelcount=substr_count(pointclicks,',')+1; 
	
	if (substr_count(cashclicks,',')+1>$levelcount) 
		$levelcount=substr_count(cashclicks,',')+1; 

	if (substr_count(sales_comm,',')+1>$levelcount)
		$levelcount=substr_count(sales_comm,',')+1; 

	while($row=@mysql_fetch_array($result)){ 
		$uplinecheck=''; 
		$username=$row['username']; 
		$upline=$username; 

		cronjob_query('delete from '.mysql_prefix.'levels where username="'.$username.'"'); 

		for ($idx=0;$idx<$levelcount;$idx++){ 
		list($upline,$thisuser)=@mysql_fetch_row(@cronjob_query('select upline,username from '.mysql_prefix.'users where username="'.$upline.'"')); 

		if ($upline){ 
                list($account_type)=@mysql_fetch_row(@cronjob_query('select account_type from '.mysql_prefix.'users where username="'.$upline.'"'));


		if (!$uplinecheck[$upline]){ 
			$uplinecheck[$upline]=1; 
		}
		else {  
			cronjob_query('update '.mysql_prefix.'users set upline="" where username="'.$thisuser.'"');  
			break;
		} 
		
		while ($account_type=='canceled' && $upline){
                list($upline,$thisuser)=@mysql_fetch_row(@cronjob_query('select upline,username from '.mysql_prefix.'users where username="'.$upline.'"'));
                if (!$uplinecheck[$upline]){
                        $uplinecheck[$upline]=1;
                }
                else {
                        cronjob_query('update '.mysql_prefix.'users set referrer="",upline="" where username="'.$thisuser.'"');
                        break;
                }
 

		list($account_type)=@mysql_fetch_row(@cronjob_query('select account_type from '.mysql_prefix.'users where username="'.$upline.'"'));
		}
 
                if ($upline)
		cronjob_query('insert into '.mysql_prefix.'levels set upline="'.$upline.'",username="'.$username.'",level='.$idx); 

		} 
		}
                cronjob_query('update '.mysql_prefix.'users set rebuild_stats_cache=1 where upline="'.$username.'"');
		cronjob_query('update '.mysql_prefix.'users set rebuild_stats_cache=0 where username="'.$username.'"'); 

} 
mysql_free_result($result); 

}

}

return;
?>

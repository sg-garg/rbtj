<?
if (!defined('version')){
exit;} 

list($show_redeem,$s,$botton,$pointslb,$pointstail,$cashlb,$cashtail,$f,$type,$class)=get_args('auto|<hr>|Request Redemption| ~ Cost: | Points| ~ Cost: $||100|||',$args);

        if ($show_redeem=='auto'){
        $show_redeem='and "'.user('account_type',1).'" like mtype';
        }
	else {
	if ($show_redeem=='show_all')
        $show_redeem='';
	else
        $show_redeem='and "'.$show_redeem.'" like mtype';
        }


        if ($class)
        $class='class='.$class;


        $cash=@mysql_result(
            @mysql_query('select sum(amount) from ' . mysql_prefix . 'accounting where type="cash" and username="'.$_SESSION[username].'"'),0,0);
        $points=@mysql_result(
            @mysql_query('select sum(amount) from ' . mysql_prefix . 'accounting where type="points" and username="'.$_SESSION[username].'"'),0,0);

        if ($points<0)
            $points=0;

        if ($cash<0)
            $cash=0;

        if ($type)
            $type="and type='$type'";

        $redeemtypes
                =@mysql_query("select * from " . mysql_prefix . "redemptions where amount $show_redeem $type order by group_order,sub_group_order,type,amount");

        while ($row=@mysql_fetch_array($redeemtypes))
            {
            $value=$row[amount] / 100000;

            $t=$pointslb;
            $dispvalue=$value;
            $tail=$pointstail;
            if ($row[type] == 'cash')
                {
                $t=$cashlb;
                $tail=$cashtail;
                $dispvalue=$dispvalue / $f;
                $value=$value / admin_cash_factor;
                }

            if (!$t)
                $dispvalue="";
            if ($row['group_order'].$row['sub_group_order']!=$lastgroup && $lastgroup)
		echo $s;

	    $lastgroup=$row['group_order'].$row['sub_group_order'];

            if ((($cash >= $row['amount'] && $row['type'] == 'cash') ||
                ($points >= $row['amount'] && $row['type'] == 'points')) && $_GET['redemption_id']==$row['id']){
                echo '<a name="ORDER"></a><br><b>'.$row[description] .$t.$dispvalue.$tail."</b><table border=0 cellpadding=0 cellspacing=0><form method=post><tr><td><input type=hidden name=user_form value=email><input type=hidden name=userform[subject] value='Redemption Request: ".$row['description']."'><input type=hidden name=userform[redemption_id] value=$row[id]>
<input type=hidden name=redirect value='redemption_request_sent.php'>$row[special]<input $class type=submit value='".safeentities($botton)."'></td></tr></form></table><br>";
                     }
                else {
                  if (($cash >= $row['amount'] && $row['type'] == 'cash') ||
                ($points >= $row['amount'] && $row['type'] == 'points'))
                  echo '<a href='.$_SERVER['PHP_SELF'].'?redemption_id='.$row['id'].'#ORDER>'.$row[description].'</a>'; 
		  else
                  echo $row['description'];

		echo $t.$dispvalue.$tail.'<br>';
                     
               }
           }


return 1;
?>

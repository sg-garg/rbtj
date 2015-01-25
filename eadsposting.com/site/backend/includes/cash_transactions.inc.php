<?
if (!defined('version')){
exit;} 

    $type='cash'.$t;
    if (!$_GET['startpos'][$type])
            $_GET['startpos'][$type]=0;
    $backpos=$_GET['startpos'][$type];


    if ($_GET['userid'])
         $adduserid='&userid='.$_GET['userid'];

        if ($t == 'credits')
            $t='and amount > 0';
        elseif ($t == 'debits') 
            $t='and amount < 0';
        else 
		$t='';

        $results
            =@mysql_query('select * from ' . mysql_prefix . 'accounting where username="'.$_SESSION['username'].'" and type="cash" '.$t.' order by time '.$o.' limit '.$_GET['startpos'][$type].','.$limit);

        while ($row=@mysql_fetch_array($results))
            {
            $row[amount]=$row[amount] / 100000 / $f;
            $_GET['startpos'][$type]++;


            if (strtolower($date) == 'yes')
                $showdate=mytimeread($row[time]). $ds;
                

            if ($t == 'and amount < 0')
                $row[amount]=$row[amount] * -1;
                

            echo $L . $showdate . $row[description] . $M . number_format(
                $row[amount], $d);
	        echo  $R;
            }
        
            if ($_GET['startpos'][$type]){
	    $page=$backpos / $limit + 1;
            echo '<tr><td>&nbsp;';
            if ($backpos - $limit >= 0)
                {
                $backpos=$backpos - $limit;
                echo '<a href='.$_SERVER[PHP_SELF].'?startpos['.$type.']='.$backpos.$adduserid.'>'.$back.'</a>';
                }

            if (strtolower($date)=='yes')
            echo '</td><td>';
            echo '</td><td align=right>';      
      
            if ($_GET[startpos][$type] / $limit == intval($_GET[startpos][$type] / $limit))
                echo '<a href='.$_SERVER[PHP_SELF].'?startpos['.$type.']='.$_GET[startpos][$type].$adduserid.'>'.$forward.'</a>';
                echo '&nbsp;</td></tr>';
	    }                  

return 1;
?>

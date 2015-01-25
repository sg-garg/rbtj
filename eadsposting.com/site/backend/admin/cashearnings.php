<?php
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='Cash Earnings';
admin_login();

$age=(int)$_POST['age'];

if (!$to)
	$to=10000;
if (!$from && payout>0)
	$from=payout;
if (!$from)
	$from=-10000;
	
if ($payconid)
{
    if ($payconid!=$paymd5time)
    {
        echo "<form method=post>Invalid confirmation ID. Please try again!";
        echo "<input type=hidden name=paymd5time value=$paymd5time><br><br>Once you have successfully completed the mass payment enter this confirmation ID: <b>$paymd5time</b> in the box below and click on submit to post the payment information to the members accounts<br><input type=text name=payconid><input type=submit value=Post></form>";
        footer();
    }
    $results=@mysql_query("select * from ".mysql_prefix."masspaytmp");
    while ($row=@mysql_fetch_array($results))
    {
        $row[amount]=0-(($row[amount]+$row[process_fee])*100000*admin_cash_factor);
        @mysql_query('insert into '.mysql_prefix.'accounting set transid="'.maketransid($row[username]).'",username="'.$row[username].'",unixtime='.unixtime.',description="'.system_value("paydesc").'",type="cash",amount="'.$row[amount].'"');
        echo "Payment completed for $row[username]<br>";
    }
}
?>
<table border=0>
    <tr><form method=post>
    <td>Membership Type:</td>
    <td><select name=account_type>
              <option value=''>All Membership Types</option>
              <option value='advertiser'>Advertiser</option>
              <option value='custom'>Custom with Free Refs or Extra Commission</option>
<?php
   $getkeys=@mysql_query("select ".mysql_prefix."member_types.description from ".mysql_prefix."member_types
                            left join ".mysql_prefix."users on ".mysql_prefix."member_types.description=".mysql_prefix."users.account_type
                            where ".mysql_prefix."users.account_type is not null group by ".mysql_prefix."users.account_type
                            order by ".mysql_prefix."users.account_type");

        while ($row=@mysql_fetch_row($getkeys))
            {
            echo '<option value="'.strtolower($row[0]).'">'.$row[0].'</option>';
            }
   
echo "</select>
    </td>
    </tr>
    <tr>
        <td>Payment Type:</td>
        <td><select name=payment_type>
        <option value=''>All Payment Types</option>
        ";

$getkeys=@mysql_query('select pay_type from '.mysql_prefix.'users where pay_type!="" group by pay_type');
while ($row=@mysql_fetch_row($getkeys))
{
    echo '<option value="'.strtolower($row[0]).'">'.$row[0].'</option>';
}

echo "
        </select>
        </td>

    </tr>
        <td>From:</td>
        <td><input type=text name=from value=$from></td>
    </tr>
    <tr>
        <td>To:</td>
        <td><input type=text name=to value=$to></td>
    </tr>
    <tr>
        <td>Transaction age:</td>
        <td><input type='text' name='age' value='$age'> days (<i>exclude transactions younger than this</i>)</td>
    </tr>

    <tr>
        <td>Count negative transactions</td>
        <td><select name='count_neg'>
            <option value='yes'>Yes</option>
            <option value='all'>All (ignore age setting)</option>
            <option value='no'>No</option>
        </td>
    </tr>

    <tr>
        <td>Show total click counters</td>
        <td><input type='checkbox' name='counter_total' value='1'></td>
    </tr>


    </table>
    <input type=submit name=report value=Report></form>";
if ($report)
{
    $f=$from*100000*admin_cash_factor;
    $t=$to*100000*admin_cash_factor;
    echo "Mass Pay options will be listed at the bottom of the report<br>";
    mysql_query("drop table ".mysql_prefix."tmpcashtbl");
    mysql_query("drop table ".mysql_prefix."masspaytmp");


    mysql_query("CREATE TABLE ".mysql_prefix."masspaytmp (
                    username char(16),
                    amount decimal(10,2) not null,
                    account char(64),
                    currency char(16),
                    process_fee decimal(10,2) not null
                    ) TYPE=MyISAM");
    mysql_query("create table ".mysql_prefix."tmpcashtbl (
                    username char(16) not null, 
                    amount bigint not null, 
                    counter_cash bigint not null, 
                    counter_total bigint not null, 
                     key amount(amount))");

    //------------------------------------------------------
    // Age and negative options/filtering -- CC221
    //------------------------------------------------------
    $filter = '';

    if($age > 0)
        $filter .= " AND (time < NOW() - INTERVAL $age day  ";  //continues below

    if($_POST['count_neg'] == 'all' AND $age > 0)
        $filter .= " OR amount < 0 ) ";
    else if($age > 0)
        $filter .=               " ) ";

    if($_POST['count_neg'] == 'no')
        $filter .= " AND amount > 0 ";

    mysql_query("insert into ".mysql_prefix."tmpcashtbl (username,amount) select username,sum(amount) from `".mysql_prefix."accounting` where type='cash' $filter group by username");
    mysql_query("UPDATE `last_login`, `tmpcashtbl`  
                    SET
                     `tmpcashtbl`.`counter_total` = `last_login`.`cash_clicks` + `last_login`.`points_clicks`, 
                     `tmpcashtbl`.`counter_cash` = `last_login`.`cash_clicks`
                    WHERE
                     `last_login`.`username` = `tmpcashtbl`.`username`");


    if ($_POST['account_type']=='custom')
        $account_type='and account_type="" and (free_refs>0 or commission_amount>0)';
    elseif ($_POST['account_type']) 
        $account_type='and account_type="'.addslashes($_POST['account_type']).'"';
    
    if ($_POST['payment_type'])
        $payment_type='and pay_type="'.addslashes($_POST['payment_type']).'"';
    
    $report= mysql_query("SELECT ".mysql_prefix."tmpcashtbl.amount,counter_cash,counter_total,
                                 ".mysql_prefix."users.username,pay_type,pay_account,currency,process_fee 
                          FROM ".mysql_prefix."tmpcashtbl,
                               ".mysql_prefix."users 
                          LEFT JOIN ".mysql_prefix."payment_types 
                          ON ".mysql_prefix."users.pay_type=".mysql_prefix."payment_types.type 
                          WHERE ".mysql_prefix."users.username=".mysql_prefix."tmpcashtbl.username and account_type!='canceled' and account_type!='suspended' and amount!=0 and amount>=$f and amount<=$t $account_type $payment_type 
                          ORDER BY amount DESC");
    echo "<br>";
    echo "<table border='1' cellpadding='2' cellspacing='0' class='centered'>
            <tr><td><b>Username</b></td><td><b>Amount</b></td><td><b>Pay Type/Link</b></td><td><b>Pay Account</b><td><b>Currency</b></td><td><b>Fee</b></td>";

    //------------------------------------------------------
    // Add click counter headers if chosen
    //------------------------------------------------------
    if($counter_cash)
        echo "<td><b>Cash clicks</b></td>";
    if($counter_total)
        echo "<td><b>Total clicks</b></td>";

    echo "</tr>";
    while($row=@mysql_fetch_array($report))
    {
        if($bgcolor==' class="row2"')
            {$bgcolor=' class="row1"';}
        else
            {$bgcolor=' class="row2"';}

        echo "<tr $bgcolor>
                <td><a href=viewuser.php?userid=$row[username]>$row[username]</a></td>
                <td align=right>".number_format($row['amount']/100000/admin_cash_factor,5)."</td><td align='center'>";

        $paylinkamount=number_format(floor($row['amount']/100000)/admin_cash_factor-$row['process_fee'],2);
        $masspaymembers=$masspaymembers+1;
        $masspaytotal=$masspaytotal+$paylinkamount;
        $returnurl=scripts_url."admin/transactions.php".urlencode("?save=1&formusername=$row[username]&description=".system_value("paydesc")."&type=cash&amount=-".number_format(floor($row[amount]/100000)/admin_cash_factor,2));
        @mysql_query("insert into ".mysql_prefix."masspaytmp set username='$row[username]',amount=$paylinkamount,account='$row[pay_account]',currency='$row[currency]',process_fee='$row[process_fee]'");
        if (strtolower($row['pay_type'])=='paypal' and $row['pay_account'])
        {
            echo "<a href='https://www.paypal.com/xclick/business=$row[pay_account]&item_name=".urlencode(system_value("paydesc"))."&amount=$paylinkamount&currency_code=$row[currency]&rm=2&return=$returnurl'>PayPal</a>";
        }
        elseif (strtolower($row['pay_type'])=='egold' or strtolower($row['pay_type'])=='e-gold')
        {
            echo "<a href=https://www.e-gold.com/sci_asp/payments.asp?PAYEE_NAME=$row[username]&PAYMENT_URL=$returnurl&NOPAYMENT_URL=".scripts_url."admin/cashearnings.php&BAGGAGE_FIELDS=DESCRIPTION&DESCRIPTION=".urlencode(system_value("paydesc"))."&SUGGESTED_MEMO=".urlencode(system_value("paydesc"))."&PAYEE_ACCOUNT=$row[pay_account]&PAYMENT_AMOUNT=$paylinkamount&PAYMENT_UNITS=1&PAYMENT_METAL_ID=0>e-Gold</a>";
        }
        elseif (strtolower($row['pay_type'])=='alertpay')
        {
            /* Appears to require upgraded AlertPay account on receivers end, disabled for now [CC215]
            echo "
            <form name='pay$row[username]' style='margin:0px; padding:0px;' method='POST'  action='https://www.alertpay.com/PayProcess.aspx'>

            <input type=hidden name='ap_itemname' value='". system_value("paydesc")."'>
            <input type=hidden name='ap_purchasetype' value='service'>
            <input type=hidden name='ap_merchant' value='$row[pay_account]'>
            <input type=hidden name='ap_currency' value='$row[currency]'>
            <input type=hidden name='ap_amount' value='$paylinkamount'>
            <input type=hidden name='ap_returnurl' value='". scripts_url."admin/transactions.php?save=1&formusername=$row[username]&description=".system_value("paydesc")."&type=cash&amount=-".number_format(floor($row[amount]/100000)/admin_cash_factor,2) ."'>
            <input type=hidden name='ap_cancelurl' value='".scripts_url."admin/cashearnings.php'>
            <a href='#' onClick='javascript:document.forms[\"pay$row[username]\"].submit();'>AlertPay</a>
            </form>\n\n";
            */
            echo "AlertPay";
        }
        else 
        { 
            echo $row['pay_type'];
        }
        echo "</td><td>$row[pay_account]</td><td>$row[currency]</td><td>$row[process_fee]</td>";

        //------------------------------------------------------
        // View click counters if desired
        //------------------------------------------------------
        if($counter_cash)
            echo "<td>$row[counter_cash]</td>";
        if($counter_total)
            echo "<td>$row[counter_total]</td>";

        echo "</tr>";
    }
    echo "</table>";
    echo "<form method=post>This report lists <b>$masspaymembers</b> member(s)<br>With a total balance of <b>$masspaytotal</b> in cash<br>";
    echo "<a href=masspay.php>Download tab delimited mass payment file</a><br><i>Format: Account   Amount   Currency   Username</i><br>";
    $paymd5time=substr(md5(unixtime),0,6);
    echo "<input type=hidden name=paymd5time value=$paymd5time><br><br>Once you have successfully completed the mass pay enter this confirmation ID: <b>$paymd5time</b> in the box below and click on submit to post the payment information to the members accounts<br><input type=text name=payconid><input type=submit value=Post></form>";
}
mysql_query("drop table ".mysql_prefix."tmpcashtbl");
footer();

?>

<?php
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='Transaction Manager';
admin_login();

$formusername = substr(preg_replace("([^a-zA-Z0-9])", "", $formusername),0,16);
$description = mysql_real_escape_string($_POST['description']);
if (!$description)
{
    $description = mysql_real_escape_string($_GET['description']);
}
if ($type=='cash')
{
	$amount=$amount*100;
}
$amount = $amount*100000;
$amount = number_format($amount,0,'',''); // Circumvent PHP5 bug http://bugs.php.net/bug.php?id=43053
if ($save==2 and $oldid)
{
	@mysql_query("update ".mysql_prefix."accounting set username='$formusername',description='$description',type='$type',amount='$amount' where transid='$oldid'");
}  
if ($save==1)
{
	if (!$_POST['postonlycomm'])
    {
        $searchphrase='';
        $pickedtransid=maketransid($formusername);
        @mysql_query('insert into '.mysql_prefix.'accounting set transid="'.$pickedtransid.'",username="'.$formusername.'",unixtime='.unixtime.',description="'.$description.'",type="'.$type.'",amount="'.$amount.'"');
    }
    if ($_POST['postcomm'] || $_POST['postonlycomm'])
    {
        creditul($_POST['formusername'], $amount, $_POST['type'], $_POST['comm'], $_POST['commdesc']);
    }
}
if ($mode=='Delete')
{
	@mysql_query("delete from ".mysql_prefix."accounting where transid='$transid'");
}
if ($get=='search')
{
    $searchphrase="%".$searchphrase."%";
}
?>
<h2>Transaction Ledger</h2>
<form action='transactions.php' method='POST'>
<table class='centered' border='0' cellspacing='0' cellpadding='2'>
<tr>
<td  align='right'>
    Search Phrase:
</td>
<td>
    <input type='text' name='searchphrase'>
</td>
<td>
    <select name='fields'>
    <option value='all'>From all fields</option>
    <option value='username'>Only username</option>
    <option value='description'>Only description</option>
    <option value='transid'>Only transaction id</option>
    </select>
    <i>(leave blank to list all transactions)</i>
</td>
</tr>
<tr>
    <td align='right'>Date between:</td>
    <td colspan='2'><input type='text' name='trans_from' value='0000-00-00 00:00:00'> - <input type='text' name='trans_to' value='<?php echo gmdate("Y-m-d G:i:s",unixtime);?>'> <i>(yyyy-mm-dd hh:mm:ss)</i></td>
</tr>
<tr>
<td align='right'>
Results:
</td>
<td colspan='2'>
    <input type='text' style='width:50px' name='limit' value='100'>
    ordered by
    <select name='orderby'>
    <option value='time_desc'>Time (descending)</option>
    <option value='time_asc'>Time (ascending)</option>
    <option value='username_desc'>Username (descending)</option>
    <option value='username_asc'>Username (ascending)</option>
    <option value='desc_desc'>Description (descending)</option>
    <option value='desc_asc'>Description (ascending)</option>
    </select>
</td>
</tr>
<tr>
<td align='right'>
Hide trans.id:
</td>
<td colspan='2'>
    <input type='checkbox' checked name='hidetransid' value='1' >
</td>
</tr>
<tr>
<td colspan='3' align='center'>
    <input type='hidden' name='get' value='search'>
    <input type='submit' value='Search'>
</td>
</tr>
</table>

</form>
<br>

<?php   
$slimit = '';                                                                           
if ($limit)
{
    $slimit = "LIMIT $limit";
}
if ($formusername and $type)
{
	$usersearch = $formusername;
	$transtype = $type;
}
if ($searchphrase and !$usersearch)
{
	$usersearchphrase="%".substr(preg_replace("([^a-zA-Z0-9])", "", $searchphrase),0,16)."%";
    //--------------------------------------
    // Order by mod -- CC215
    //--------------------------------------
    switch ($_POST['orderby'])
    {
        case "time_desc":
            $orderby = " ORDER BY time DESC ";
            break;
        case "time_asc":
            $orderby = " ORDER BY time ASC ";
            break;
        case "username_desc":
            $orderby = " ORDER BY username DESC ";
            break;
        case "username_asc":
            $orderby = " ORDER BY username ASC ";
            break;
        case "desc_desc":
            $orderby = " ORDER BY description DESC ";
            break;
        case "desc_asc":
            $orderby = " ORDER BY description ASC ";
            break;
        default:
            $orderby = " ORDER BY time DESC ";
            break;
    }
    //----------------------------------------
    // Transaction between 'date' mod -- CC215
    //----------------------------------------
    $timeline = '';
    if(!empty($_POST['trans_from']) AND !empty($_POST['trans_from']))
    {
        $timeline = " AND time BETWEEN '$_POST[trans_from]' AND '$_POST[trans_to]' ";
    }

    //----------------------------------------
    // Restricted fields search mod -- CC215
    //----------------------------------------
    switch ($_POST['fields'])
    {
        case "all":
            $fields = " (transid LIKE '$searchphrase' OR username LIKE '$usersearchphrase' OR description LIKE '$searchphrase' OR transid='$pickedtransid') ";
            break;
        case "username":
            $fields = " username LIKE '$usersearchphrase' ";
            break;
        case "description":
            $fields = " description LIKE '$searchphrase' ";
            break;
        case "transid":
            $fields = " (transid LIKE '$searchphrase' OR transid='$pickedtransid') ";
            break;
        default:
            $fields = " (transid LIKE '$searchphrase' OR username LIKE '$usersearchphrase' OR description LIKE '$searchphrase' OR transid='$pickedtransid') ";
            break;
    }
    $query = "SELECT * FROM ".mysql_prefix."accounting WHERE $fields $timeline $orderby $slimit";
	$getads=@mysql_query($query); 
} else 
{
	$getads=@mysql_query("SELECT * FROM ".mysql_prefix."accounting WHERE username='$usersearch' AND type='$transtype' ORDER BY time DESC");
}
$count=@mysql_num_rows($getads);
if($count > 0)
{
    echo "<a href='transactions.php#transform' target='_top'>Create a new transaction</a>";
    echo "
        <center><b>$count record(s) found</b></center>
        <table class='centered' border='1' cellpadding='2' cellspacing='0'>
        <tr>\n";
    if(!$_POST['hidetransid']) echo "<th>Transaction ID</th>\n";
    echo "  <th>Username</th>
            <th>Description</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Date Posted</th>
            <td>&nbsp;</td>
        </tr>
        ";

    while($row=@mysql_fetch_array($getads))
    {
        $row['time']=mytimeread($row['time']);
        
        if ($row['type']=='cash')
        {
            $row['amount']=$row['amount']/100;
        }
        $row['amount']=$row['amount']/100000;
        $user=@mysql_fetch_row(@mysql_query("select username from ".mysql_prefix."users where username='$row[username]' and account_type!='canceled'"));
        $usrmsg='';
        if (!$user[0])
        {
            $usrmsg="<br><FONT COLOR=RED><b>Canceled</b>";
        }
        else 
        {
            $user=@mysql_fetch_row(@mysql_query("select sum(amount) from ".mysql_prefix."accounting where username='$row[username]' and type='$row[type]'"));
            if ($user[0]<0)
            {
                $usrmsg="<br><FONT COLOR=RED><b>Account holds a NEGATIVE balance</b>";
            }
        }
        if(!$_POST['hidetransid'])
        {
            $transidp = "<td>$row[transid]</td>";
        }
        if($bgcolor == 'class="row2"')
        {
            $bgcolor='class="row1"';
        }else{
            $bgcolor='class="row2"';
        }
        echo "\n<form action='transactions.php#transform' method='POST'>\n  <input type=hidden name=transtype value='$transtype'>\n  <input type=hidden name=usersearch value='$usersearch'>\n  <input type=hidden name=limit value=$limit>\n  <input type=hidden name=searchphrase value='$searchphrase'>\n  <input type=hidden name=transid value='$row[transid]'>\n<tr $bgcolor>$transidp<td><a href='viewuser.php?userid=$row[username]' target='_viewuser'>$row[username]</a>$usrmsg</td>\n<td>$row[description]</td><td>$row[type]</td><td align=right>".number_format($row[amount],5)."</td><td>$row[time]</td><td>\n  <input type=submit name=mode value='Delete'>\n  <input type=submit name=mode value='Edit'>\n  <input type=submit name=mode value='Copy'></td></tr>\n</form>\n\n";
    }
    echo "</table><br><center><a href='#top'>Back to top</a></center>";
}
$savemode=1;
$row='';
if ($mode=='Edit' or $mode=='Copy')
{
	$savemode=2;
	if ($mode=='Copy')
	{
		$savemode=1;
	}
	$row=mysql_fetch_array(mysql_query("select * from ".mysql_prefix."accounting where transid='$transid'"));
}
if (!$mode || $mode=='Delete')
{
    $mode='Create New';
}

if ($row['type']=='cash')
{
	$row['amount']=$row['amount']/100;
}

$row['amount']=$row['amount']/100000;
if (!$row['username'])
{
    $row['username']=$usersearch;
}
?>
<a name="transform"></a><form action="transactions.php" method="POST" name="form">
<input type=hidden name=searchphrase value='<?php echo $searchphrase;?>'>
<input type=hidden name=usersearch value='<?php echo $usersearch;?>'>
<input type=hidden name=transtype value='<?php echo $transtype;?>'>
<input type="hidden" name="save" value="<?php echo $savemode;?>">
<input type=hidden name=usersearch value='<?php echo $usersearch;?>'>
<?php if ($savemode==2){?><input type=hidden name=oldid value='<?php echo $row['transid'];?>'><?php } ?>

<h2><?php echo $mode;?> Transaction</h2>
<table border='0' width='500'>
<tr>
    <td>Username:</td>
    <td><input type="text" name="formusername" value="<?php echo $row['username'];?>"></td>
</tr>
<tr>
    <td>Description:</td>
    <td><input type="text" maxlength=32 name="description" value="<?php echo $row['description'];?>"></td>
</tr>
<tr>
    <td>Type:</td>
    <td><select name=type><option <?php if ($row['type']=='cash'){ echo "selected";}?> value=cash>Cash<option <?php if ($row['type']=='points'){ echo "selected";}?> value=points>Points</select></td>
</tr>
<tr>
    <td>Amount:</td>
    <td><input type="text" name="amount" value=<?php echo number_format($row['amount'],5,".","");?>></td>
</tr>
<tr>
<td colspan='2'>
<?php 
if ($mode=='Create New')
{
	?><input type=checkbox class=checkbox name=postcomm>Post upline commissions<br>
	<input type=checkbox class=checkbox name=postonlycomm>Do not post this transaction, only post the upline commissions for it<br>
	<br><b>Sales Commissions:</b><br>
	Example: if your site pays 15% for direct referral sales, 10% for second level referral sales and 5% for third level referral sales you would enter: <b>15,10,5</b>
	<br><input type=text name=comm value="<?php echo sales_comm;?>">
	<br>Description:<br>
	<input type=text name=commdesc value="<?php echo sales_desc;?>"><br>
	<br>
	<?php 
}?>
<input type="submit" name="add" value="Save Transaction">
</form>
<?php echo "\n</td></tr></table>";
footer();

?>

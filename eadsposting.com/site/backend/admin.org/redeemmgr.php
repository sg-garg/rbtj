<?php
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');

if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='Redemption Manager';
admin_login();


if ($show_redeem)
{
	mysql_query("replace into ".mysql_prefix."system_values set name='show_redeem',value='$show_redeem'") or die ("Query error");
} 
$description=addslashes($_POST['description']);
$special=addslashes($_POST['special']);
$phpcode=addslashes($_POST['phpcode']);
if ($type=='cash')
{
	$amount=$amount*100;
}
$amount=$amount*100000;
$amount = number_format($amount,0,'',''); // Circumvent PHP5 bug http://bugs.php.net/bug.php?id=43053

if ($save==2 and $oldid)
{
	mysql_query("update ".mysql_prefix."redemptions set upgrade_acct='$upgrade', upgrade_autoexpire='$upgrade_autoexpire',mtype='$mtype',group_order='$group_order',sub_group_order='$sub_group_order',phpcode='$phpcode',special='$special',description='$description',auto='$auto',type='$type',amount='$amount' where id=$oldid")  or die ("Query error");
}  
if ($save==1)
{
	$searchphrase='';
	mysql_query("insert into ".mysql_prefix."redemptions set upgrade_acct='$upgrade', upgrade_autoexpire='$upgrade_autoexpire',mtype='$mtype',group_order='$group_order',sub_group_order='$sub_group_order',phpcode='$phpcode',auto='$auto',special='$special',description='$description',type='$type',amount='$amount'")  or die ("Query error");
}
if ($mode=='Delete')
{
	mysql_query("delete from ".mysql_prefix."redemptions where id='$id'")  or die ("Query error");
}
?>

To list the redemption types available to a member place the following code in your html<br>
<b>&lt;?php action('show_redemptions');?&gt;</b>

<form action='redeemmgr.php' method='post'>
    Search Redemption Database: (leave blank to list all redemption types) 
    <input type='text' name='searchphrase'>
    <input type='hidden' name='get' value='search'>
    <input type='submit' value='Search'><br>
    <a href='redeemmgr.php#transform' target='_top'>Create a new redemption type</a>
</form>
<br>

<?php if ($get=='search' OR $mode == 'Edit'  OR $mode == 'Copy' OR $save > 0 OR $mode=='Delete'){ ?>

<table class='centered' border='1' cellpadding='2' cellspacing='0'>
<tr>
    <th>Sort Group</th>
    <th>Membership Type</th>
    <th>Description</th>
    <th>Type</th>
    <th>Amount</th>
    <th>Auto Deduct</th>
    <th>Action</th>
</tr>                                                                             

<?php
}
if ($get=='search')
{
    $searchphrase="%".$searchphrase."%";
}
if (!$searchphrase){$searchphrase='*****************************';}
if ($limit){$limit="limit $limit";}
$getads=mysql_query("select * from ".mysql_prefix."redemptions where id like '$searchphrase' or description like '$searchphrase' or id=LAST_INSERT_ID() order by group_order,sub_group_order,type,amount"); 
while($row=mysql_fetch_array($getads))
{
    if($bgcolor == ' class="row1" ')
    {
        $bgcolor=' class="row2" ';
    }
    else
    {
        $bgcolor=' class="row1" ';
    }

	if ($row['type']=='cash')
	{
		$row['amount']=$row['amount']/100;
	}
	$row['amount']=$row['amount']/100000;
	$dmtype=$row['mtype'];
	if ($dmtype=='')
		$dmtype='Free and Custom Members';
	
	if ($dmtype=='%')
		$dmtype='All Membership Types';
		
	echo "
        <tr $bgcolor>
        <td align=center>$row[group_order]$row[sub_group_order]</td>
        <td>$dmtype</td><td>$row[description]</td>
        <td>$row[type]</td>
        <td align=right>".number_format($row['amount'],5)."</td>
        <td>$row[auto]</td>
        <td>
            <form action='redeemmgr.php#transform' method='post'>
            <input type=hidden name=searchphrase value='$searchphrase'>
            <input type=hidden name=id value='$row[id]'>
            <input type=submit name=mode value='Delete'>
            <input type=submit name=mode value='Edit'>
            <input type=submit name=mode value='Copy'>
            </form>
        </td>
        </tr>\n\n";
}

$count=mysql_num_rows($getads);
if ($get=='search'  OR $mode == 'Edit'  OR $mode == 'Copy' OR $save > 0 OR $mode=='Delete')
{
    echo "</table>\n";
    echo "<center><b>".$count." record(s) found</b></center><br>\n";
}
$savemode=1;
$row='';
if ($mode=='Edit' or $mode=='Copy')
{
	$savemode=2;
	if ($mode=='Copy'){
	$savemode=1;}
	$row=mysql_fetch_array(mysql_query("select * from ".mysql_prefix."redemptions where id='$id'"));
}
if (!$mode){$mode='Create New';}
if ($row['type']=='cash')
{
	$row['amount']=$row['amount']/100;
}
$row['amount']=$row['amount']/100000;


?>
<a name="transform"></a><form action="redeemmgr.php" method="POST" name="form">
<input type=hidden name=searchphrase value='<?php echo $searchphrase;?>'>
<input type="hidden" name="save" value="<?php echo $savemode;?>">
<?php

if ($savemode==2)
{?>
	<input type=hidden name=oldid value='<?php echo $row['id'];?>'>
	<?php 
} ?>
    <h2><?php echo $mode;?> Redemption Type:</h2>
	<table border=0 width='600'>
    <tr>
        <td width='180'>Sort Group</td>
        <td><select name='group_order'>
            <?php grouplist($row['group_order']);?></select>
            <select name='sub_group_order'>
            <?php grouplist($row['sub_group_order']);?></select>
    <tr>
        <td>Qualifying Membership Type:</td>
        <td>
        <select name='mtype'>
         <option value='%' <?php if ($row['mtype']=='%') echo 'selected'?>>All Membership Types</option>
         <option value='' <?php if ($row['mtype']=='') echo 'selected'?>>Free and Custom Members</option>
	     <option value='advertiser' <?php if ($row['mtype']=='advertiser') echo 'selected'?>>Advertiser</option>

        <?php
        $getkeys=mysql_query("select description from ".mysql_prefix."member_types order by description"); 
        while ($mtrow=mysql_fetch_row($getkeys))
            {                
 
            echo '<option value="'.$mtrow[0].'" ';
            if (strtolower($row['mtype'])==strtolower($mtrow[0])) echo 'selected';

            echo '>'.$mtrow[0].'</option>';
            }                

        ?>
        </select>
    <tr>
        <td>Description:</td>
        <td><input type="text" name="description" value="<?php echo $row['description'];?>"></td>
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
    <td>Change this account type to:</td>
    <td>
        <select name='upgrade'>
        <option value='' <?php if ($row['upgrade_acct']=='') echo 'selected'?>>Do not change account type</option>
	    <option value='advertiser' <?php if ($row['upgrade_acct']=='advertiser') echo 'selected'?>>Advertiser</option>
    
            <?php
            $getkeys=mysql_query("select description from ".mysql_prefix."member_types order by description"); 
            while ($mtrow=mysql_fetch_row($getkeys))
            {
                echo '<option value="'.$mtrow[0].'" ';
    
                if (strtolower($row['upgrade_acct'])==strtolower($mtrow[0])) echo 'selected';
    
                echo '>'.$mtrow[0].'</option>';
            }                
    
            ?>
        </select>
    </td>
    </tr>
    <!-- Auto expire upgrade -->
    <tr>
    <td>Upgrade expires in:</td>
    <td>
        <select name='upgrade_autoexpire'>
        <option value='' <?php if ($row['upgrade_autoexpire']=='') echo 'selected'?>>Never</option>
        <option value='1 day' <?php if ($row['upgrade_autoexpire']=='1 day') echo 'selected'?>>1 day</option>
        <option value='2 day' <?php if ($row['upgrade_autoexpire']=='2 day') echo 'selected'?>>2 days</option>
        <option value='3 day' <?php if ($row['upgrade_autoexpire']=='3 day') echo 'selected'?>>3 days</option>
        <option value='4 day' <?php if ($row['upgrade_autoexpire']=='4 day') echo 'selected'?>>4 days</option>
        <option value='5 day' <?php if ($row['upgrade_autoexpire']=='5 day') echo 'selected'?>>5 days</option>
        <option value='6 day' <?php if ($row['upgrade_autoexpire']=='6 day') echo 'selected'?>>6 days</option>
        <option value='1 week' <?php if ($row['upgrade_autoexpire']=='1 week') echo 'selected'?>>1 week</option>
        <option value='2 week' <?php if ($row['upgrade_autoexpire']=='2 week') echo 'selected'?>>2 weeks</option>
        <option value='3 week' <?php if ($row['upgrade_autoexpire']=='3 week') echo 'selected'?>>3 weeks</option>
        <option value='1 month' <?php if ($row['upgrade_autoexpire']=='1 month') echo 'selected'?>>1 month</option>
        <option value='2 month' <?php if ($row['upgrade_autoexpire']=='2 month') echo 'selected'?>>2 months</option>
        <option value='3 month' <?php if ($row['upgrade_autoexpire']=='3 month') echo 'selected'?>>3 months</option>
        <option value='4 month' <?php if ($row['upgrade_autoexpire']=='4 month') echo 'selected'?>>4 months</option>
        <option value='5 month' <?php if ($row['upgrade_autoexpire']=='5 month') echo 'selected'?>>5 months</option>
        <option value='6 month' <?php if ($row['upgrade_autoexpire']=='6 month') echo 'selected'?>>6 months</option>
        <option value='7 month' <?php if ($row['upgrade_autoexpire']=='7 month') echo 'selected'?>>7 months</option>
        <option value='8 month' <?php if ($row['upgrade_autoexpire']=='8 month') echo 'selected'?>>8 months</option>
        <option value='9 month' <?php if ($row['upgrade_autoexpire']=='9 month') echo 'selected'?>>9 months</option>
        <option value='10 month' <?php if ($row['upgrade_autoexpire']=='10 month') echo 'selected'?>>10 months</option>
        <option value='11 month' <?php if ($row['upgrade_autoexpire']=='11 month') echo 'selected'?>>11 months</option>
        <option value='1 year' <?php if ($row['upgrade_autoexpire']=='1 year') echo 'selected'?>>1 year</option>
        <option value='2 year' <?php if ($row['upgrade_autoexpire']=='2 year') echo 'selected'?>>2 years</option>
        <option value='3 year' <?php if ($row['upgrade_autoexpire']=='3 year') echo 'selected'?>>3 years</option>
        <option value='4 year' <?php if ($row['upgrade_autoexpire']=='4 year') echo 'selected'?>>4 years</option>
        <option value='5 year' <?php if ($row['upgrade_autoexpire']=='5 year') echo 'selected'?>>5 years</option>
        </select> (if changing account type)
    </td>
    </tr>
    <tr>
        <td>Automaticly deduct amount from users account when they redeem:</td><td><select name=auto><option <?php if ($row[auto]=='no'){ echo "selected";}?> value=no>No<option <?php if ($row[auto]=='yes'){ echo "selected";}?> value=yes>Yes</select></td>
    </tr>
    <tr>
        <td colspan=2><h2>Special HTML:</h2>If you would like to add special HTML code right before the submit button like a textbox where they can put in their ad, you can use this example:<br>
        <br><i>&lt;textarea name='userform[ad_info]' rows='10' cols='30'&gt;Type your ad here&lt;/textarea&gt;&lt;br&gt;</i><br>
        <textarea name="special" rows=20 cols=70><?php echo safeentities($row[special]);?></textarea>
        <br><h2>PHP Code:</h2>Do not use this unless you know PHP. You can enter here any PHP scripting you would like to take place when someone selects this redemption. If you use the option to auto deduct, the script you enter here will run before the transaction is entered into the accounting table.    
        <br>
        <textarea name="phpcode" rows=20 cols=70><?php echo safeentities($row[phpcode]);?></textarea>
        </td>
    </tr>
    <tr>
        <td colspan=2><input type="submit" name="add" value="Save Redemption">

    </td>
    </tr>
    </table>
  </form>
<?php
footer();

function grouplist($group)
{
	$grouplist=explode('|','A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z');
        for ($i=0;$i<26;$i++)
	{
        	echo '<option value="'.$grouplist[$i].'"';
        	
		if ($grouplist[$i]==$group)
        		echo ' selected';
		
		echo '>'.$grouplist[$i];
	}
}
?>

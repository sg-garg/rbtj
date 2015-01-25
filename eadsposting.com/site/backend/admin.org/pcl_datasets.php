<?php
include("functions.inc.php");
    if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='Send eMail To Members';
admin_login();

include("scheduler_menu.php");

echo "<h2>Massmailer</h2>\n\n";

if(empty($_POST['headerset']))
{
    $_POST['headerset'] = 'default';
}

$curdate=gmdate("Y-m-d",unixtime);
$emailsubject=$_POST['emailsubject'];
$emailtosend=$_POST['emailtosend'];
if ($numtosend<1 && strtolower($numtosend)!='auto')
	$numtosend=1;
$emailsubject=$highcheck.$emailsubject;
if ($mode=='Delete')
{
    mysql_query("delete from ".mysql_prefix."mass_mailer where massmailid=$id");
}
if ($mode=='Send')
{
    mysql_query("update ".mysql_prefix."mass_mailer set current=1 where massmailid=$id and current=0");
    mysql_query('replace into '.mysql_prefix.'system_values (name,value) select "mailqueue",count(*) from '.mysql_prefix.'mass_mailer where current>0');
}

if ($_POST['send'] == "mail")
{
    $hicount=0;

    if ($_POST['keyword'])
    {
        reset($_POST['keyword']);
        $interests='||';
        while (list($key, $value) = each($_POST['keyword']))
        {
            $value=strtolower(addslashes($value));
            $interests=$interests.$value.'||';
            if ($value)
            {
                if (substr($value,0,2)!="g:" and substr($value,0,2)!="c:" and substr($value,0,2)!="s:")
                {
                    $words=$words.$and." keywords like '%||$value||%'";
                    $and=" and";
                }
                else 
                {
                    if (substr($value,0,2)=="s:"){
                    $value=str_replace("s:","",$value);
                    $states=$states.$sor." state='$value'";
                    $sor=" or";}
                    if (substr($value,0,2)=="c:"){ 
                    $value=str_replace("c:","",$value);
                    $countries=$countries.$cor." country='$value'";
                    $cor=" or";}
                    if (substr($value,0,2)=="g:")
                    { 
                        $value=str_replace("g:","",$value);
                        if ($value=='inactive')
                        {
                            $mailinactive=1;
                            continue;
                        }
                        if ($value=='inactives_only')
                        {
                            $inactives_only=1;
                            continue;
                        }
                        if (empty($value))
                        {
                            continue;
                        }
                        if ($value=='free'){$value='';}
                        $membership=$membership.$mor." account_type='$value'";
                        $mor=" or";
                    }
                }
            }
        }
    }
    if ($words or $countries or $states or $membership)
    {
        if ($words){ 
        $words='and '.$words;
        }
        if ($states && $countries){
        $states='and ('.$states.' or ';
        $countries=$countries.')';
        }
        else {
        if ($states){
        if ($sor){
        $states='and ('.$states.')';}
        else {
        $states='and '.$states;}}
        if ($countries){
        if ($cor){
        $countries='and ('.$countries.')';}
        else {
        $countries='and '.$countries;}}
        }
        if ($membership){
        if ($mor){
        $membership='and ('.$membership.')';}
        else {
        $membership='and '.$membership;}}
        $states=strtolower($states);
        $countries=strtolower($countries);
        $membership=strtolower($membership);

        if (!isset($mailinactive) && defined('nocreditdays') && nocreditdays>0)
        {
            $activepart1="LEFT JOIN ".mysql_prefix."last_login ON (".mysql_prefix."users.username=".mysql_prefix."last_login.username) ";
            $activepart2='`time`>'.gmdate("YmdH",unixtime-(nocreditdays*86400)).' and';
        }
        if ($inactives_only == 1 && defined('nocreditdays') && nocreditdays>0)
        {
            $activepart1="LEFT JOIN ".mysql_prefix."last_login ON (".mysql_prefix."users.username=".mysql_prefix."last_login.username) ";
            $activepart2=' (`time`<'.gmdate("YmdH",unixtime-(nocreditdays*86400)).' OR `time` is null ) AND ';
        }
        list($hicount)=@mysql_fetch_row(@mysql_query("
                                    SELECT count(*) FROM ".mysql_prefix."users
                                    $activepart1 
                                    LEFT JOIN ".mysql_prefix."interests ON (".mysql_prefix."users.username=".mysql_prefix."interests.username) 
                                    WHERE $activepart2 email_setting>=0 and account_type!='canceled' and vacation<'$curdate' $words $states $countries $membership"));

        if ($hicount<$numtosend && strtolower($numtosend)!='auto')
        {
            $numtosend=$hicount;
        }
    }
    if (strtolower($numtosend)=='auto')
    {
        $plines = explode('</PM>', $emailtosend);
                        for ($pidx = 0; $pidx < count($plines); $pidx++) 
                        {
                            $getpmid = explode('<PM>', $plines[$pidx]);
                            if ($getpmid[1]) 
                            $getad = @mysql_fetch_array(cronjob_query("select * from ".mysql_prefix."email_ads where emailid=$getpmid[1]"));
                            
                            if ($getad['run_quantity']>$largest && $getad['run_type']=='clicks') 
                            $largest=$getad['run_quantity'];
                        }
        if ($largest)
	        $numtosend=$largest;
        else
	        $numtosend=$hicount;
    }
    if(!empty($_POST['headerset']))
    {
        list($header, $separator, $footer) = mysql_fetch_array(mysql_query("SELECT `header`, `separator`, `footer` FROM ".mysql_prefix."scheduler_emailsets WHERE title = '". mysql_real_escape_string($_POST['headerset'])."'"));
        $emailtosend = $header . $emailtosend . $footer;
    }
    mysql_query('insert into '.mysql_prefix.'mass_mailer set charset="'.addslashes($_POST['charset']).'",time="'.mysqldate.'",subject="'.addslashes($emailsubject).'",keywords="'.$interests.'",inboxonly="'.$inboxonly.'",is_html="'.$is_html.'",stop='.$numtosend.',ad_text="'.addslashes($emailtosend).'"');
}
list($numtosend)=@mysql_fetch_array(@mysql_query("select count(*) from ".mysql_prefix."users where email_setting>=0 and account_type!='canceled' and vacation<'$curdate'"));
$getmessages=@mysql_query("select * from ".mysql_prefix."mass_mailer order by time desc");
?>

<a href='delemails.php'>Delete old emails</a><br>
Current messages in queue:<br>

<table class='centered' border='1' cellpadding='2' cellspacing='0'>
<tr>
    <th>Subject</th>
    <th>Total</th>
    <th>Current</th>
    <th>Date</th>
    <th>&nbsp;</th>
</tr>

<?php
while ($messages=@mysql_fetch_array($getmessages))
{
    if($bgcolor == 'class="row1"')
    {$bgcolor = 'class="row2"';}
    else
    {$bgcolor = 'class="row1"';}
    if ($messages['current']>$messages['stop'])
	    $messages['current']="Send Complete";
    
    if (!$messages['current'])
	    $messages['current']="<input type='submit' name='mode' value='Send'>";
	    
    echo "
        <tr $bgcolor>
            <td>". safeentities($messages['subject'])."</td>
            <td>". $messages['stop'] ."</td>
            <td><form action='massmail.php' method='POST'><input type='hidden' name='id' value='".$messages['massmailid']."'>". $messages['current'] ."</form></td>
            <td>". mytimeread($messages['time'])."</td>
            <td><form action='massmail.php' method='POST'><input type='hidden' name='id' value='".$messages['massmailid']."'><input type='submit' name='mode' value='Delete'><input type='submit' name='mode' value='Copy'></form></td>
        </tr>\n\n";
}
echo "</table>\n\n";
list($is_html, $inbox, $charset) = mysql_fetch_array(mysql_query("SELECT `html`, `inbox`, `charset` FROM ".mysql_prefix."scheduler_emailsets WHERE title = '". $_POST['headerset'] ."'"));
if(empty($charset))
{
    $charset=emailcharset;
}
if($inbox == 'Y')
{
    $inboxonly = 'checked';
}else{
    $inboxonly = '';
}
$interests='none';
$emailtosend='';
$username='';

if ($mode=='Copy')
{
    $message=mysql_fetch_array(mysql_query("select * from ".mysql_prefix."mass_mailer where massmailid=$id"));
    $emailsubject = $message['subject'];
    
    if ($message['inboxonly']==1)
        $inboxonly='checked';
    
    $interests=$message['keywords'];
    $numtosend=$message['stop'];
    $emailtosend=$message['ad_text'];
    $username="MMID:$id";
    $is_html=$message['is_html'];
    $charset=$message['charset'];
}
	
?>

Please enter your <b>ADVERTISEMENT EMAIL</b> and how many users you want to send it to.<br>
<br>
You can personalized the email by using these codes: <br>
<span class='warning'>NOTE: Personalized emails send very slowly and increases mail server load.<br>
Not recommended if you have time sensitive emails to send out.</span><br>
<br>
<b>&lt;OWED&gt;</b>, <b>&lt;CASH_BALANCE&gt;</b>, <b>&lt;POINT_BALANCE&gt;</b><br>
<b>&lt;FIRSTNAME&gt;</b>, <b>&lt;LASTNAME&gt;</b>, <b>&lt;EMAIL&gt;</b><br>
<b>&lt;USERNAME&gt;</b>, <b>&lt;ENCRYPTEDPW&gt;</b>, <b>&lt;SIGNUPDATE&gt;</b><br>
<b>&lt;SIGNUPIPPROXY&gt;</b><br>
<br>
To place your paid email ads into the email type <b>&lt;PM&gt;AD_ID#&lt;/PM&gt;</b> replace AD_ID# with the id of the email advertisement.

<?php
if (ini_get('allow_url_fopen'))
{
	echo '<br><br>To place content from any 3rd party script into your email type <b>&lt;INCLUDE&gt;PUT_URL_HERE&lt;/INCLUDE&gt;</b> replace PUT_URL_HERE with the URL of the 3rd party script';
}

?>

<form action="massmail.php" method="POST" name="form">
<input type=hidden name=admin_password value='<?php echo $_SESSION['admin_password'];?>'>
<input type="hidden" name="send" value="mail">
<table border='0' cellpadding='0' cellspacing='2'>
<tr>
<td align='right'>
    Total emails to send:
</td>
<td>
    <input type="text" name="numtosend" value="Auto">
</td>
</tr>
<?php 
if ($mode!='Copy')
{
    //------------------------------------------
    // Headerset selection for new mails - CC211
    //------------------------------------------
    echo "<tr><td align='right'>\nHeaderset:</td><td><select name='headerset'>\n";

    $selected = '';
    if($_POST['headerset'] == 'default')
    {
        $selected = 'selected';
    }
    echo "<option value='default' $selected>default</option>\n";


    $headersets = mysql_query("SELECT * FROM `". mysql_prefix ."scheduler_emailsets` WHERE title != 'default' ORDER BY title ASC");
    while($headerset = mysql_fetch_array($headersets))
    {
        $selected = '';
        if($headerset['title'] == $_POST['headerset'])
        {
            $selected = 'selected';
        }
        echo "<option value='". htmlentities($headerset['title'], ENT_QUOTES) ."' $selected>". htmlentities($headerset['title'], ENT_QUOTES) ."</option>\n";
    }


    echo "</select>  <input type='submit' name='action' value='Fetch Settings for this email set'>  </td></tr>\n";

}
?>
<tr>
<td align='right'>
    Send as:
</td>
<td>
    <select name='is_html'><option value='N' <?php if ($is_html!='Y'){ echo "selected";}?>>plain text email<option value='Y' <?php if ($is_html=='Y'){ echo "selected";}?>>HTML email</select>
</td>
</tr>
<tr>
<td align='right'>
    Character Set:
</td>
<td>
    <input type='text' name='charset' value='<?php echo $charset; ?>'>
</td>
</tr>

<?php 
if (substr($emailsubject,0,2)=="! ")
{
    $emailsubject=substr($emailsubject,2,strlen($emailsubject)-2);
    $highcheck="checked";
}?>

<tr>
<td align='right'>
    Subject of email:
</td>
<td>
    <input type="text" name="emailsubject" value="<?php echo safeentities($emailsubject);?>">
</td>
</tr>

<tr>
<td align='right'>
    High Priority:
</td>
<td>
    <input type='checkbox' class='checkbox' <?php echo $highcheck;?> name='highcheck' value="! ">
</td>
</tr>

<tr>
<td align='right'>
    Internal inboxes only:
</td>
<td>
    <input type='checkbox' class='checkbox' <?php echo $inboxonly;?> name='inboxonly' value='1'>
</td>
</tr>
</table>

	Email:<br>
<iframe src='emailidlist.php' style='width:98%; height: 300px'></iframe><br>
<textarea name="emailtosend" rows='30' cols='90'><?php echo safeentities($emailtosend);?></textarea><br>
<b>Membership Types:</b><br>
<?php
$idx=0;
echo "\n<input type='checkbox' class='checkbox' name='keyword[$idx]' value=\"g:advertiser\" ";
interests("g:advertiser","checked");
echo ">Advertiser ";$idx++;
echo "\n<input type='checkbox' class='checkbox' name='keyword[$idx]' value=\"g:suspended\" ";        
interests("g:suspended","checked");
echo ">Suspended ";$idx++;  
echo "\n<input type='checkbox' class='checkbox' name='keyword[$idx]' value=\"g:free\" "; 

if ($interests=='none')
    echo 'checked';
else 
    interests("g:free","checked");
echo ">Custom ";$idx++;    

$getkeys=@mysql_query("select ".mysql_prefix."member_types.description from ".mysql_prefix."member_types left join ".mysql_prefix."users on ".mysql_prefix."member_types.description=".mysql_prefix."users.account_type where ".mysql_prefix."users.account_type is not null group by ".mysql_prefix."users.account_type");
while($row=@mysql_fetch_row($getkeys))
{
    echo "\n<input type='checkbox' class='checkbox' name='keyword[$idx]' value=\"g:$row[0]\" ";
    if ($interests=='none')
    {
        echo 'checked';
    }else{
        interests("g:".$row[0],"checked");
    }
    echo ">$row[0] ";
    $idx++; 
}
if (defined('nocreditdays') && nocreditdays>0)
{
    //---------------------------------
    // Active members only selection
    //---------------------------------
    echo "<br><br>\n<input type='radio' name='keyword[$idx]' value=''";
    if (!interests("g:inactive","return") AND !interests("g:inactives_only","return") AND $interests!='none')
    {
        echo 'checked'; 
    }
    echo ">Active members only (last login within ".nocreditdays." days) from the above selected groups";

    //---------------------------------
    // In+active members selection
    //---------------------------------
    echo "<br>\n<input type='radio' name='keyword[$idx]' value=\"g:inactive\" ";
    if ($interests=='none')
    {
        echo 'checked'; 
    }
    else  
    {
        interests("g:inactive","checked");
    }
    echo ">Both active and inactive members";

    //---------------------------------
    // Inactive members only selection
    //---------------------------------
    echo "<br>\n<input type='radio' name='keyword[$idx]' value=\"g:inactives_only\" ";

    if ($interests!='none')
    {
        interests("g:inactives_only","checked");
    }
    echo ">Inactive members only (last login over ".nocreditdays." days ago or more)";

    $idx++;
}
?> 

<br>
<br>
Targeting: (Do not select any targeting if you want to send to everyone in the selected membership type(s))
<table border='0'>
<tr>
<td valign='top'>
    <b>Keywords:</b><br>
    <?php 
    $getkeys=@mysql_query("select keyword from ".mysql_prefix."keywords where keycount>0 order by keyword");
    while($row=@mysql_fetch_row($getkeys))
    {
        $line++;
        echo "\n<input type='checkbox' class='checkbox' name='keyword[$idx]' value=\"".safeentities($row[0])."\" ";
        interests($row[0],"checked");
        echo ">$row[0]<br>";
        $idx++;
        if ($line>30)
        { 
            echo "</td><td valign='top'><br>"; 
            $line=0;
        }
    }
    
    ?>
</td>
<td valign='top'>
    <b>Countries:</b><br>
    <?php $getkeys=mysql_query("select ".mysql_prefix."countries.country from ".mysql_prefix."countries left join ".mysql_prefix."users on ".mysql_prefix."countries.country=".mysql_prefix."users.country where ".mysql_prefix."users.country is not null group by ".mysql_prefix."users.country");
    $line=0;
    while($row=mysql_fetch_row($getkeys))
    {
        echo "\n<input type='checkbox' class='checkbox' name='keyword[$idx]' value=\"c:$row[0]\" ";
        interests("c:".$row[0],"checked");
        echo ">$row[0]<br>";
        $idx++; 
        $line++;
        if ($line>30)
        { 
            echo "</td><td valign=top><br>"; 
            $line=0;
        }
    }
    ?>
</td>
<td valign='top'>
    <b>States:</b><br>
    <?php $getkeys=mysql_query("select ".mysql_prefix."states.state from ".mysql_prefix."states left join ".mysql_prefix."users on ".mysql_prefix."states.state=".mysql_prefix."users.state where ".mysql_prefix."users.state is not null group by ".mysql_prefix."users.state"); 
    $line=0;
    while($row=mysql_fetch_row($getkeys))
    {
        echo "\n<input type='checkbox' class='checkbox' name='keyword[$idx]' value=\"s:$row[0]\" "; 
        interests("s:".$row[0],"checked");
        echo ">$row[0]<br>";$idx++; $line++;  
        if ($line>30)
        { 
            echo "</td><td valign='top'><br>"; 
            $line=0;
        } 
    }?> 

</td>
</tr>
</table>
<input type="submit" name="add" value="Save Email">
</form>
<?php footer(); ?>

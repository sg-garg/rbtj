<?php
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');

if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);

$title='Cheat Link Clicks';
admin_login();

list($avg)=@mysql_fetch_row(@mysql_query('select avg(cheat_links) from '.mysql_prefix.'last_login where cheat_links>0'));

if ($connum)
{
    if ($connum!=$md5time)
    {
        echo "Invalid confirmation ID. Counters not reset";
    }
    else { @mysql_query("update ".mysql_prefix."last_login set cheat_links=0");}
}


$md5time=substr(md5(unixtime),0,6);
?>

On average, a member clicked on <?php echo number_format($avg);?> cheat links.<br>

<br>
To reset Cheat click counters for all accounts<br>
enter this confirmation ID: <b><?php echo $md5time;?></b><br>
<br>
<a href="backup.php">Download a gzipped copy of your Mysql data</a><br>
<br>

<table border="0" cellpadding="2" cellspacing="0">
<tr>
    <td align="center" colspan="3">
    <b>Cheat click counter reset</b>
    </td>
</tr>
<tr>
    <td align="right">Set</td>
    <td><form style="margin:0px;" method="POST">
        <input style="width: 100px;" type="text" name="reset_username">'s counter to <input onClick="javascript: this.value='';" style="width: 23px;" type="text" value="0" name="reset_value">
    </td>
    <td align="center"><input style="width:80px" type="submit" value="Set"></form></td>
</tr>

<?php
//-------------------------------------
// Check if counter update was requested
//-------------------------------------
if (!empty($_POST['reset_username']))
{
    $username = preg_replace("([^a-zA-Z0-9])", "", $_POST['reset_username']);
    list($email)=@mysql_fetch_row(@mysql_query('SELECT email FROM '.mysql_prefix.'users where username = "'.$username.'"'));

    //-------------------------------------
    // User exists? -> update counter
    //-------------------------------------
    if(!empty($email))
    {
        $resetquery = "UPDATE ".mysql_prefix."last_login SET cheat_links=".(int)$_POST['reset_value']." WHERE username = '$username'";
    
        echo '<tr><td align="center" colspan="3">Update ';
        $result = mysql_query($resetquery);
        if (!$result)
        {
            $msg  = '<font color="red"><b>failed</b></font> for username '.$username.'<br><b>Invalid query:</b> ' . mysql_error() . '<br><br><b>Query:</b> ' . $resetquery;
            die($msg);
        }else{
            echo '<font color="green"><b>done</b></font> for username '.$username.'<br>';
        }
        echo '</td></tr>';
    }
    else
    {
        echo '<tr><td align="center" colspan="3"><font color="red"><b>'.$username.' - no such username in database</b></font></td></tr>';
    }
}
?>

<tr>
    <td align="center" colspan="3">
    <form style="margin:0px;" method="POST">
    <input type="hidden" name="md5time" value="<?php echo $md5time;?>">
    <br><b>THIS WILL ZERO ALL COUNTERS FOR ALL ACCOUNTS</b>
    </td>
</tr>
<tr>
    <td align="right">Confirmation ID:</td>
    <td><input style="width: 170px;" type="text" name="connum"></td>
    <td align="center"><input style="width:80px" type=submit value='Reset All'></form></td>
</tr>
</table>

<?php
$report = @mysql_query("select ".mysql_prefix."users.username,cheat_links from ".mysql_prefix."last_login,".mysql_prefix."users where ".mysql_prefix."users.username=".mysql_prefix."last_login.username and account_type!='canceled' and account_type!='suspended' and cheat_links>0 order by cheat_links desc");

echo "<br>
        <table class='centered' border='1' cellpadding='2' cellspacing='0'>
        <tr>
        <th><b>Username</b></th>
        <th><b>Total Cheat Clicks</b></th>
     </tr>";
while($row=@mysql_fetch_array($report))
{
    if($bgcolor == ' class="row1" ')
    {
        $bgcolor=' class="row2" ';
    }
    else
    {
        $bgcolor=' class="row1" ';
    }

    echo "<tr $bgcolor><td><a href=viewuser.php?userid=$row[username] target=_viewuser>$row[username]</a></td><td align=right>$row[cheat_links]</td></tr>";

}
echo "</table>";
footer();

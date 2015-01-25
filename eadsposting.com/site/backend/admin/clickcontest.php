<?php
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');

if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);

$title='Select contest winners';
admin_login();

//------------------------------
// Credit Winners -- CC222
//------------------------------
if($_POST['action'] == 'Credit')
{
    echo "<h3>Contest Winner rewards</h3><hr></center>\nFollowing users have been rewarded:<br><br>\n<table border='0'>";

    foreach($_POST['credit'] as $username => $key)
    {

        //------------------------------
        // Format the amount for database
        //------------------------------
        $amount = $_POST['reward'][$username] * 100000;
        if ($_POST['type'][$username] == 'cash')
        {
            $amount = $amount*100;
        }
        $amount = number_format($amount,0,'',''); // Circumvent PHP5 bug http://bugs.php.net/bug.php?id=43053

        //------------------------------
        // Create transaction
        //------------------------------
        $addtran = @mysql_query('UPDATE ' . mysql_prefix . 'accounting SET amount=amount+'.$amount.' WHERE type="'.mysql_real_escape_string($_POST['type'][$username]).'" AND username = "'.$username.'" AND description="'.mysql_real_escape_string($_POST['transaction']).'" LIMIT 1'); 
        if (!@mysql_affected_rows($addtran))
        {
            $addtran=@mysql_query('INSERT INTO ' . mysql_prefix . 'accounting set transid="'.maketransid($username).'",username = "'.$username.'",unixtime=0,description="'.mysql_real_escape_string($_POST['transaction']).'",amount='.$amount.',type="'.$_POST['type'][$username].'"');
        }


        echo "<tr><td><a href='viewuser.php?userid=$username' target='_viewuser'>$username</a></td><td>". $_POST['reward'][$username] ."</td><td>".$_POST['type'][$username]."</td></tr>\n";
    }
    echo "</table>\n\n";
    footer();
}




echo "<h3>Contest Winners for ".strtoupper($type)." $id</h3><hr></center><br>
";
if ($type=='ptc')
{
    $id = " where id='$id' ";
} else
{
    $id="_$id";
}
$paidclicks="paid_clicks";
if ($type=='pts')
{
    $paidclicks="paid_signups";
}

if ($type=='review')
{
    $paidclicks="paid_reviews";
}
if ($draw and $id and $type){
    $report=@mysql_query("select * from ".mysql_prefix."$paidclicks$id order by rand() limit $draw");

    if(@mysql_num_rows($report) > 0)
    {
        ?>
        <form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='POST'>

        <?php
        echo "<table class='centered' border=1 cellpadding='2' cellspacing='0'>\n<tr><td colspan='2' align='right'><b>Username</b></td><td><b>Date</b></td><td><b>Reward</b></td></tr>\n";
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

            $row['time']=mytimeread($row['time']);
            echo "<tr $bgcolor><td><input type='checkbox' checked name='credit[$row[username]]' value='1'></td><td><a href=viewuser.php?userid=$row[username] target=_viewuser>$row[username]</a></td><td>$row[time]</td><td><input type='text' name='reward[$row[username]]' value='0.000'>\n<select name='type[$row[username]]'><option value='cash'>cash</option><option value='points'>points</option></select></tr>\n\n";

        }
        ?>
        <tr>
        <td colspan='3' align='right'>
            Transaction title: </td><td> <input style='width: 90%' name='transaction' value='Click contest prizes' type='text'>
        </td>
        </tr>
        <tr>
        <td colspan='4' align='center'>
            <input name='action' value='Credit' type='submit'> selected users with above prizes
        </td>
        </tr>

        </table>
        </form>
        <?php
    }
    else {
    ?>
        <center><b>No entries on the ad log.</b><br><br><small>Notice that ads, which are re-clickable, have their logs cleared to allow re-clicking of the ad.</small></center>
    <?php
    }
}

footer();


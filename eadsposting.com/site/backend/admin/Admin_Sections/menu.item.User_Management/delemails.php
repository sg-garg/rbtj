<?php
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='Delete Old eMails';
admin_login();

if (!isset($days))
{
    $days=15;
}
$days = (int)$days;

echo "
    <form method='POST'>
    <table border='0' cellspacing='0' cellpadding='0'>
    <tr>
    <td>

        List massmails that were sent more than
    </td>
    <td>
        <input type='text' name='days' value='$days'> days ago (<b>0</b> to pick all)
    </td>
    </tr>
    <tr>
    <td>
        Pre-select massmails that have not been sent:
    </td>
    <td>
        <input type='checkbox' name='notsent' value='true'>
    </td>
    </tr>
    <tr>
    <td colspan='2' align='center'>
        <input type='submit' name='report' value='Report'>
    </td>
    </tr>
    </table>
    </form>\n";

if (!empty($connum))
{
    if ($connum!=$md5time)
    {
        echo "Invalid confirmation ID. Nothing deleted";
        footer();
    }
    if ($_POST['uid'])
    {
        foreach ($_POST['uid'] as $key => $value)
        {
            mysql_query("delete from  ".mysql_prefix."mass_mailer where massmailid='$key'");
            echo "Deleted: $key<br>";
        }
        footer();
    }
}
if (!empty($report))
{
    $report = mysql_query("select massmailid,subject,current,time from ".mysql_prefix."mass_mailer where time <= DATE_SUB(NOW(), INTERVAL $days DAY) order by time desc ");

    if(mysql_num_rows($report) == 0)
    {
        echo "No massmails older than <b>$days</b> days found<br>";
        footer();
    }

    $md5time=substr(md5(unixtime),0,6);
    ?>
    <form method='POST'>
    <input type=hidden name=md5time value=<?php echo $md5time;?>><br>
    <br>
    To delete all checked emails below<br>
    enter this confirmation ID: <b><?php echo $md5time;?></b><br>
    <br>
    <a href="backup.php">Download a gzipped copy of your Mysql data</a><br>
    <br>
    <b>THIS WILL DELETE ALL CHECKED EMAILS BELOW. ONLY DO THIS IF YOU ARE SURE</b><br>
    Confirmation ID: <input type text name="connum"><input type=submit value='Delete Now'><br>
    <br>
    <table border="1" cellspacing='0' cellpadding='2'>
    <tr>
        <td><b>Delete</b></td>
        <td><b>Subject</b></td>
        <td><b>Total Sent</b></td>
        <td><b>Date</b></td>
    </tr>
    <?php
    while($row=mysql_fetch_array($report))
    {
        if($bgcolor == ' class="row2" ')
            $bgcolor=' class="row1" ';
        else
            $bgcolor=' class="row2" ';
        

        if ($row['current'] < 1 AND !isset($_POST['notsent']))
        {
            $checked='';
        } 
        else 
        {
            $checked='checked';
        }
        echo "<tr $bgcolor>
            <td><input type=checkbox class=checkbox name=\"uid[$row[massmailid]]\" value=1 $checked></td>
            <td>$row[subject]</td><td>$row[current]</td>
            <td>".mytimeread($row['time'])."</td></tr>\n\n";
    }
    echo "\n</table></form>\n\n";
}
footer();
?>

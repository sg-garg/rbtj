<?php
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
$title='Abuse Reports';
admin_login();

//-----------------------------------------
// Delete Message?
//-----------------------------------------
$_POST['id'] = (int)$_POST['id'];
if(!empty($_POST['id']))
{
    mysql_query("DELETE FROM `". mysql_prefix ."abuse` WHERE id='{$_POST[id]}' LIMIT 1") or die ("Oops, MySQL Query error");
}

//-----------------------------------------
// Read messages and show them
//-----------------------------------------
$query = mysql_query("SELECT * FROM `".mysql_prefix."abuse` WHERE 1 ORDER BY time ASC");
echo "
    <table style='width:100%' border='1' cellpadding='3' cellspacing='0'>
    <tr>
        <td align='center' nowrap><b>Submitted</b></td>
        <td align='center' nowrap><b>By</b></td>
        <td align='center'><b>Ad</b></td>
        <td align='center' style='width:90%' nowrap><b>Reason</b></td>
        <td align='center' nowrap>&nbsp;</td>
    </tr>";

while($row = mysql_fetch_array($query))
{
    if($row['type'] == 'PTC')
    {
        $edit = "
            <form action='ptcadmgr.php#adform' method='POST'>
            <input type='hidden' name='searchphrase' value='|||'>
            <input type='hidden' name='ptcid' value='{$row['ad']}'>
            <input type='submit' name='mode' value='Edit'>
            </form>
            ";
        list($url) = mysql_fetch_array(mysql_query("SELECT site_url FROM `".mysql_prefix."ptc_ads` WHERE ptcid='{$row['ad']}' LIMIT 1"));
    }

    if($row['type'] == 'email')
    {
        $edit = "
            <form action='emailadmgr.php#adform' method='POST'>
            <input type='hidden' name='searchphrase' value='|||'>
            <input type='hidden' name='emailid' value='{$row['ad']}'>
            <input type='submit' name='mode' value='Edit'>
            </form>
            ";
        list($url) = mysql_fetch_array(mysql_query("SELECT site_url FROM `".mysql_prefix."email_ads` WHERE emailid='{$row['ad']}' LIMIT 1"));
    }

    if($bgcolor == 'class="row1"')
    {$bgcolor = 'class="row2"';}
    else
    {$bgcolor = 'class="row1"';}
        
    echo "
    <tr $bgcolor>
        <td nowrap rowspan='2'>". mytimeread($row['time'])."</td>
        <td nowrap align='center'><a href='viewuser.php?userid={$row['username']}' target='_user'>{$row['username']}</a></td>
        <td nowrap align='center'>{$row['type']} / {$row['ad']}</td>
        <td>". nl2br(htmlentities($row['reason'], ENT_QUOTES)) ."</td>
        <td align='center' rowspan='2' nowrap><form action='abusereports.php' method='POST'><input type='hidden' name='id' value='{$row['id']}'><input type='submit' name='mode' value='Delete'></form></td>
    </tr>
    <tr $bgcolor>
        <td>$edit</td>
        <td colspan='2'><a href='" .scripts_url."admin/astest.php?testurl=". htmlentities($url, ENT_QUOTES) ."' target='_blank'>". htmlentities($url, ENT_QUOTES) ."</a></td>
    
    ";
}

?>
</table>
<br>
<br>


<?php
footer();
?>

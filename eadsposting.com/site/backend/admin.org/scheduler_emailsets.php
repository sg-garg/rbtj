<?php
include("../conf.inc.php");
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
$title='eMail Scheduler';
admin_login();



//------------------------------------
// Save email
//------------------------------------
if (!empty($_POST['title']))
{
    $title = mysql_real_escape_string($_POST['title']);
    $footer = mysql_real_escape_string($_POST['footer']);
    $header = mysql_real_escape_string($_POST['header']);
    $separator = mysql_real_escape_string($_POST['separator']);

    mysql_query("REPLACE INTO ".mysql_prefix."scheduler_emailsets 
            (`title`,`header`,`separator`,`footer`) VALUES 
            ('$title','$header','$separator','$footer')");

}


//------------------------------------
// Fetch set to edit
//------------------------------------
list($header,$footer,$separator) = mysql_fetch_array(mysql_query("SELECT `header`, `footer`, `separator` FROM ".mysql_prefix."scheduler_emailsets WHERE title = '$title'"));

//------------------------------------
// Show offline settings form
//------------------------------------
echo "<form method='POST'>";
echo "<table width='100%'border='0'>";
echo "<tr><td align='right'>Set name:</td><td><input maxlength='80' type='text' value='$title' name='title' style='width: 500px;'></td></tr>";
echo "<tr><td align='right'>Header:</td><td><textarea name='header' style='width: 500px; height: 300px;'>$header</textarea></td></tr>";
echo "<tr><td align='right'>Separator:</td><td><textarea name='separator' style='width: 500px; height: 100px;'>$separator</textarea></td></tr>";
echo "<tr><td align='right'>Footer:</td><td><textarea name='footer' style='width: 500px; height: 300px;'>$footer</textarea></td></tr>";
echo "</table>";
echo "<center><input type=submit value='Save Changes'></form></center>";

footer(); 
?>

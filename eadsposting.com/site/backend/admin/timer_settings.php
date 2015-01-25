<?php

include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
$title='Ad Timer Settings';
admin_login();

if ($_POST['sysval'])
{
    reset($_POST['sysval']);
    while (list($key, $value) = each($_POST['sysval']))
    {
        if(!$value)    
            $value=' ';
        $value = system_value($key,$value);
        @mysql_query("replace into `".mysql_prefix."system_values` set name='$key',value='".trim($value)."'");
    }
}
$timerbar_location = system_value("timerbar_location");
?>
<form method='POST'>
<table border='0'>
<tr>
<td>
    These settings will affect how the advertisement timer will look on your site
    <table border=0>
    <tr>
        <td>Timerbar Height</td>
        <td><input type=text name=sysval[frame_size] value='<?php print system_value("frame_size");?>'></td>
    </tr>
    <tr>
        <td>Timerbar Location</td>
        <td>
            <select name=sysval[timerbar_location]>
                <option value='0' <?php if(!$timerbar_location) echo 'selected';?> >Top</option>
                <option value='1' <?php if($timerbar_location == 1) echo 'selected';?> >Bottom</option>
                <option value='2' <?php if($timerbar_location == 2) echo 'selected';?> >Random</option>


            </select>
        </td>
    </tr>
    <tr>
        <td>Popup Width</td>
        <td><input type=text name=sysval[popup_width] value='<?php print system_value("popup_width");?>'></td>
    </tr>
    <tr>
        <td>Popup Height</td>
        <td><input type=text name=sysval[popup_height]  value='<?php print system_value("popup_height");?>'></td>
    </tr>
    </table>
</td>
</tr>
</table>
<input type=submit value='Save Changes'></form>
<?php
footer();
?>
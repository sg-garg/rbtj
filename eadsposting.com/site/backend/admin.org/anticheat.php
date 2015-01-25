<?php
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='Anti-Cheat Settings';
admin_login();

if ($_POST[sysval])
{
    reset($_POST[sysval]);
    while (list($key, $value) = each($_POST[sysval]))
    {
        if(!$value)    
        $value=' ';
        $value=system_value($key,$value);
        @mysql_query("replace into ".mysql_prefix."system_values set name='$key',value='".trim($value)."'");
    }
}
?>
<form method='POST'>
<b>
<center>Verify Referrer when processing sign-ups</center>

<table width=100% border=0 cellpadding=0 cellspacing=0>
<tr>
    <td>Make sure that the referrer entered on the sign-up page exists when processing sign-ups</td>
    <td><select name=sysval[verifyref]><option value=YES>Yes</option>   
        <option value=NO <?php if (system_value('verifyref')=="NO"){ echo "selected";}?>>No</option></select>
    </td>
</tr>
<tr>
    <th colspan=2><br>Turing Number Settings</th>
</tr>
<tr>
    <td>Use Turing number check on paid ads every 15 minutes</td>
    <td><select name=sysval[checkturing]>
        <option value='NO' <?php if (system_value('checkturing')=="NO"){ echo "selected";}?>>No</option>   
        <option value='YES' <?php if (system_value('checkturing')=="YES"){ echo "selected";}?>>Yes</option>
        </select>
     </td>
</tr>

<tr>
    <td>Turing Number link size (percent)</td>
    <td><input size=3 name='sysval[turinglinksize]' value='<?php echo system_value('turinglinksize');?>'></td>
</tr>

<?php
if (function_exists('imagetypes'))
{
    if (imagetypes() & IMG_JPG){
    echo '<tr><th colspan=2><br>Your server supports the below setting options.<br><br></th></tr>';
    }
} else 
{
    echo '<tr><th colspan=2><br>Your server does not have GD with JPEG support installed in php. The Turing setting options below will have no effect<br><br></th></tr>';
}?>
<tr>
    <td>Paid ad Turing number size (height & width)</td>
    <td><input type=text size=3 name=sysval[paturingsize] value=<?php echo system_value('paturingsize');?>></td>
</tr>
<tr>
    <td>Color variance (1=B&W - 255=Full Spectrum)</td>
    <td><input type=text size=3 name=sysval[turingcolors] value=<?php echo system_value('turingcolors');?>></td>
</tr>
<tr>
    <td>Pattern density (1=dense - 30=sparse)</td>
    <td><input type=text size=3 name=sysval[turinglines] value=<?php echo system_value('turinglines');?>></td>
</tr>
<?php 
if (function_exists('imagetypes')) 
{ ?>
    <tr>
        <td>Disable GD support:<br>(only do this if you are having a problems with images)</td>
        <td><select name=sysval[gdoff]><option value=NO>No<option <?php if (system_value('gdoff')=='YES') echo 'selected';?> value=YES>Yes</select></td>
    </tr>

<?php 
if (system_value('gdoff')!='YES')
{
    if (imagetypes() & IMG_JPG)
    {
        echo '
        <tr><th colspan=2><br>System-1 through System-5 fonts will work on all servers. <br>Support for the bitmap fonts (.gdf) will very from server to server. '; 
        if (function_exists('imagettftext')){
        echo '<br>True Type Fonts (.ttf) are supported by your server.';}
        else { 
        echo '<br>Your server does not have FreeType installed in php. True Type Fonts (.ttf) are not supported'; 
        }
        echo '<br><br></th></tr>';
        ?>
        <tr>
            <td>Turing number font</td>
            <td>
        <select name=sysval[turingfont]>
        <?php
        if (is_dir('../fonts')) 
        {
            if ($dh = opendir('../fonts')) 
            {
                while (($file = readdir($dh)) !== false) 
                {
                    if ($file{0}=='.' || $file=='index.html')
                    continue;
                    echo '<option value='.$file;
                    if (system_value('turingfont')==$file)
                    {
                        echo ' selected';
                    }
	                echo  '>'.$file. "\n";
                }
                closedir($dh);
            }
        }
    }
}
}
?>
</select>
<tr>
    <td colspan=2><hr><center><iframe src='turingtest.php' style="width:400px; height: 150px;" frameborder='1' scrolling=yes></iframe>
        <hr><b>Other Settings</b></center>
    </td>
</tr>
<tr>
    <td>Allow accounts with negative balances to be canceled</td>
    <td><select name=sysval[negcancel]>
        <option value=NO <?php if (system_value('negcancel')=="NO"){ echo "selected";}?>>No</option>
        <option value=YES <?php if (system_value('negcancel')=="YES"){ echo "selected";}?>>Yes</option>
        </select></td>
</tr>
<tr>
    <td>Prevent user from opening more then one timed ad at a time</td>
    <td><select name=sysval[timer_lock]>
        <option value=NO <?php if (system_value('timer_lock')=="NO"){ echo "selected";}?>>No</option>
        <option value=YES <?php if (system_value('timer_lock')=="YES"){ echo "selected";}?>>Yes</option>
        </select></td>
</tr>
<tr>
    <td colspan=2>Prevent multiple signups from the same CLASS C network block within the same <input type=text name=sysval[iplock] size=3 value=<?php echo system_value('iplock');?>> hour period</td>
</tr>
</table>

    <input type=submit value='Save Changes'>

</form>
<?php
footer();
?>
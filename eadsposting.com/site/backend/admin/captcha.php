<?php
//LDF SCRIPTS
include("functions.inc.php");

if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='Captcha Settings';
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


<table width=100% border=0 cellpadding=0 cellspacing=0>

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
    <td>Captcha size (height & width)</td>
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
   
    </td>
</tr>

</table>

    <input type=submit value='Save Changes'>

</form>
<?php
footer();
?>
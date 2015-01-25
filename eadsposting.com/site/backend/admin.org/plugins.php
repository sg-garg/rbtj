<?php
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');

$title='Plugins';
admin_login();
load_plugins();
?>
<br><b>Q: What is a Plugin?</b><br>
A: A plugin is an add-on package that can be written by any programmer and placed in the <?php echo scripts_dir.'plugins/';?> directory. Plugins add more features to the Cash Crusader software package.<br><br>
<B>Q: Will I lose these add-ons every time I update my Cash Crusader scripts?</b><br> 
A: No. All plugins will continue to work. You will not need to reinstall any plugin after updating your Cash Crusader scripts<br><br>
<b>Q: Where can I get these plugins?</b><br>
A: Currently there are over 60 plug-ins available. <a target="_blank" href="http://cashcrusadersoftware.com/plugins.php">Click here.</a> 
<br><br><center><table border=1>
<tr>
<td align="center">Plugin</td>
<td align="center">Author</td>
<td align="center">Date</td>
<td align="center">Action</td>
</tr>
<?php
$plugins_array = array();
$plugins_names = array();
if ($plugin_classes)
{
    //-----------------------------
    // Sort plugin list - CC223
    //_----------------------------
    foreach($plugin_classes as $value)
    {
            array_push($plugins_array, $value);
            array_push($plugins_names, $value->name);
    }
    array_multisort($plugins_names, $plugins_array);

    foreach($plugins_array as $value)
    {
            echo "<tr>
            <td align=\"left\"><a href=pload.php?p_load=$value->class_name>$value->name</a></td>
            <td align=\"left\"><a href=$value->web target=_blank>$value->author</a></td>
            <td align=\"left\">$value->date</td>
            <td>
            <a href=readme.php?plugin=".str_replace("/plugin.php","",$value->file_name)." target=_blank>Instructions</a>
            </td>
            </tr>\n";
    }
}
?>
</table>
</center>
<?php footer(); ?>

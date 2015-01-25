<?php
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');

if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);

$title='Keyword Selections';
admin_login();

if ($_POST['sysval'])
{
    reset($_POST['sysval']);
    while (list($key, $value) = each($_POST['sysval']))
    {
        if(!$value)    
            $value=' ';

        $value=system_value($key,$value);
        @mysql_query("replace into ".mysql_prefix."system_values set name='$key',value='".trim($value)."'");
    }
}
if ($delete)
{
    @mysql_query("DELETE FROM ".mysql_prefix."keywords where keyword='".mysql_real_escape_string($delete)."' LIMIT 1");
}
if ($add)
{
    @mysql_query("INSERT INTO ".mysql_prefix."keywords set keyword='".mysql_real_escape_string($add)."'");
} 
if ($preselect)
{
    mysql_query("UPDATE ".mysql_prefix."keywords SET preselected='". (int)$_POST['newvalue'] ."' WHERE keyword ='" .mysql_real_escape_string($_POST['preselect']) ."' LIMIT 1");
} 

//---------------------------------
// Keyword force select -- CC222
//---------------------------------
if($forceselect)
{
    $query = "UPDATE `".mysql_prefix."interests` SET `keywords` = concat(`keywords`, '". mysql_real_escape_string(strtolower($forceselect)) ."||') WHERE keywords NOT LIKE '%||". mysql_real_escape_string(strtolower($forceselect)) ."||%'";
    //echo "<hr>$query<hr>";
    mysql_query($query);
    echo "<b>Keyword selection for <i>". htmlentities($forceselect, ENT_QUOTES) ."</i> was added to ". mysql_affected_rows() ." accounts.</b>\n<br>\n<br>\n\n";
}

$results=@mysql_query("select * from ".mysql_prefix."keywords order by keyword");
echo "<form method=post>";
?>

Minimum Required Keywords: <input type='text' size='3' name='sysval[minkey]' value='<?php echo system_value('minkey');?>'><br>
Maximum Allowed Keywords: <input type='text' size='3' name='sysval[maxkey]' value='<?php echo system_value('maxkey');?>'><br>

<input type='submit' value='Save'>
</form>

<ul>
    <li><b>Preselected</b> keywords are already preselected in the join form during the signup process.</li>
    <li><b>Force</b> selects the keyword for all current members of the site at that moment.</li>
</ul>

<form method='POST'>

Add keyword: <input type='text' name='add'><input type='submit' value='Add'></form><br>
<br>

<table border='1' cellspacing='0' cellpadding='2'>

<?php
while ($row = mysql_fetch_array($results))
{
    if($bgcolor == 'class="row1"')
    {$bgcolor = 'class="row2"';}
    else
    {$bgcolor = 'class="row1"';}

    if($row['preselected'])
    {
        $preselected = 'Preselected';
        $newvalue = '0';
    }else{
        $preselected = 'Preselect';
        $newvalue = '1';
    }
    echo "<tr $bgcolor><td>$row[keyword]</td>
            <td><form method='POST'><input type=hidden name=delete value='".safeentities($row['keyword'])."'>
                <input type=submit value='Delete'></form>
            </td>
            
            <td><form method='POST'><input type=hidden name=preselect value='".safeentities($row['keyword'])."'>
                <input type=hidden name=newvalue value='$newvalue'>
                <input type=submit value='$preselected'></form>
            </td>
            
            <td><form method='POST'><input type=hidden name=forceselect value='".safeentities($row['keyword'])."'>
                <input type=submit value='Force'></form>
            </td>
        </tr>\n\n";
}
echo "</table>";
footer(); 

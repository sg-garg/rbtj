<?php
include("../conf.inc.php");
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
$title='Scheduler eMails sets';
//------------------------------------
// Full Screen preview for email set
//------------------------------------
if($_GET['fullscreen_preview'])
{
    $noheader = 1;
    admin_login();
    $emailset_title = mysql_real_escape_string($_GET['emailset_title']);
    list($header,$footer,$separator, $ishtml) = mysql_fetch_array(mysql_query("SELECT `header`, `footer`, `separator`, `html` FROM ".mysql_prefix."scheduler_emailsets WHERE title = '$emailset_title'"));
    if($ishtml != 'Y')
    {   
        $header = str_replace("<", "&lt;", $header);
        $header = str_replace(">", "&gt;", $header);
        $header = str_replace("\n", "<br>\n", $header);
        $footer = str_replace(">", "&gt;", $footer);
        $footer = str_replace("<", "&lt;", $footer);
        $footer = str_replace("\n", "<br>\n", $footer);
    }

    if(empty($header))
    {   
        $header = "<i>This email set does not have email header</i><br>\n";
    }

    if(empty($footer))
    {   
        $footer = "<i>This email set does not have email footer</i><br>\n";
    }

    echo $header;
    echo "<div style='border: 2px solid #F88; height: 20px; background-color: #FBB; text-align: center; vertical-align: middle;'>[ This space is for ads, dynamic height ]</div>";
    echo $footer;
    exit();
}


admin_login();
include("scheduler_menu.php");
?>
<h2>Email Sets Editor</h2>

<?php
//------------------------------------
// Admanager plugin import
//------------------------------------
if($_POST['action'] == 'Import' AND $_POST['plugin'] == 'admgr' AND $_POST['subaction'] == 'save')
{
    list($admgr_header, $admgr_footer, $admgr_separator) = mysql_fetch_array(mysql_query("SELECT `header`,`footer`,`splitter` FROM ".mysql_prefix."admgr_headers WHERE `name` = '". mysql_real_escape_string($_POST['headerfooter']) ."' LIMIT 1"));

    mysql_query("REPLACE INTO ".mysql_prefix."scheduler_emailsets 
                (`title`,`header`,`separator`,`footer`) VALUES 
                ('". mysql_real_escape_string($_POST['emailset_title']) ."','". mysql_real_escape_string($admgr_header) ."','". mysql_real_escape_string($admgr_separator)."','". mysql_real_escape_string($admgr_footer)."')");

    echo "<b>Set imported, now use <i>Edit</i> and <i>Preview</i> to verify/edit settings and result of import.</b>";
}

//------------------------------------
// Delete requested set
//------------------------------------
if($_GET['action'] == 'Delete')
{
    mysql_query("DELETE FROM ".mysql_prefix."scheduler_emailsets WHERE title = '".mysql_real_escape_string($_GET['emailset_title'])."'");
}

//------------------------------------
// Save modified set
//------------------------------------
if($_POST['action'] == 'save')
{
    $emailset_title = mysql_real_escape_string($_POST['emailset_title']);
    $footer = mysql_real_escape_string($_POST['footer']);
    $header = mysql_real_escape_string($_POST['header']);
    $charset = mysql_real_escape_string($_POST['charset']);
    $separator = mysql_real_escape_string($_POST['separator']);
    $ishtml = mysql_real_escape_string($_POST['ishtml']);
    $inbox = mysql_real_escape_string($_POST['inbox']);
    $frequenzy = (int)($_POST['frequenzy'] * 60);

    mysql_query("REPLACE INTO ".mysql_prefix."scheduler_emailsets 
            (`title`,`header`,`separator`,`footer`, `html`, `inbox`, `charset`, `frequenzy`) VALUES 
            ('$emailset_title','$header','$separator','$footer', '$ishtml', '$inbox', '$charset', '$frequenzy')")
    or die("Error saving email set!");
    echo "<b>Email set saved</b><br><br>\n\n";
}
//------------------------------------
// Create new set
//------------------------------------
if($_GET['action'] == 'Create')
{
    $emailset_title = mysql_real_escape_string($_GET['emailset_title']);

    mysql_query("REPLACE INTO ".mysql_prefix."scheduler_emailsets 
            (`title`,`header`,`separator`,`footer`, `html`, `inbox`, `charset`, `frequenzy`) VALUES 
            ('$emailset_title','','','', 'N', 'N', 'iso-8859-1', '1440')")
    or die("Error creating new email set!");
}

//-----------------------------------
// Check if admanager plugin is installed
//-----------------------------------
$table_list=mysql_list_tables($mysql_database);
$admgr_installed = 0;
for ($i = 0; $i < mysql_num_rows($table_list); $i++)
{
    $table1 = mysql_tablename($table_list, $i);
    if ($table1 == mysql_prefix."admgr_headers") 
    {
            $admgr_installed = 1;
    }
}

if($admgr_installed)
{
    echo "<ul><li>CashPlugins' Advertisement Manager plugin detected. You can <a href='scheduler_emailsets_editor.php?action=Import&amp;plugin=admgr'>import</a> its header/footers.</li></ul>";
}

//------------------------------------
// Pick available sets
//------------------------------------
//$query = mysql_query("SELECT * FROM ".mysql_prefix."scheduler_emailsets WHERE 1");
$query = mysql_query("SELECT * FROM `". mysql_prefix ."scheduler_emailsets` WHERE title != 'default' ORDER BY title ASC");

echo "<table border='1' cellpadding='2' cellspacing='0'>\n";
echo "<tr><td align='center'><b>Email set</b></td><td align='center'><b>Action</b></td></tr>\n";


echo "<tr><td><b>Default</b></td><td>

<form style='margin:0px' method='GET' action='". $_SERVER['PHP_SELF'] ."'>
<input type='hidden' name='emailset_title' value='default'>
<input type='submit' name='action' value='Edit'>
<input type='submit' name='action' value='Preview'>";

echo "</form></td><tr>\n";


while($row = mysql_fetch_array($query))
{
    echo "<tr><td>{$row['title']}</td><td>

    <form style='margin:0px' method='GET' action='{$_SERVER['PHP_SELF']}'>
    <input type='hidden' name='emailset_title' value='". htmlentities($row['title'], ENT_QUOTES) ."'>
    <input type='submit' name='action' value='Edit'>
    <input type='submit' name='action' value='Preview'>
    <input type='submit' name='action' value='Delete'>";

    echo "</form></td><tr>\n";
}
echo "<tr><td><form style='margin:0px' method='GET' action='". $_SERVER['PHP_SELF'] ."'><input type='text' style='width: 150px' name='emailset_title'></td><td><input type='submit' name='action' value='Create'></form></td><tr>\n";
echo "</table>\n";
echo "<br>\n\n";

//------------------------------------
// Preview emailset
//------------------------------------
if($_GET['action'] == 'Preview')
{
    $emailset_title = mysql_real_escape_string($_GET['emailset_title']);
    echo "<ul><li>Black border shows edge of the preview and is not part of the email set</li>\n";
    echo "<li>Red area is reserved for ads and is not part of the email set</li>";
    echo "<li>Click <a target='_preview' href='scheduler_emailsets_editor.php?fullscreen_preview=1&amp;emailset_title=".$_GET['emailset_title']."'>here</a> for fullscreen preview</li></ul>\n";
    list($header,$footer,$separator, $ishtml) = mysql_fetch_array(mysql_query("SELECT `header`, `footer`, `separator`, `html` FROM ".mysql_prefix."scheduler_emailsets WHERE title = '$emailset_title'"));
    if($ishtml != 'Y')
    {   
        $header = str_replace("<", "&lt;", $header);
        $header = str_replace(">", "&gt;", $header);
        $header = str_replace("\n", "<br>\n", $header);
        $footer = str_replace(">", "&gt;", $footer);
        $footer = str_replace("<", "&lt;", $footer);
        $footer = str_replace("\n", "<br>\n", $footer);
    }

    if(empty($header))
    {   
        $header = "<i>This email set does not have email header</i><br>\n";
    }

    if(empty($footer))
    {   
        $footer = "<i>This email set does not have email footer</i><br>\n";
    }

    echo "<div style='border: 2px solid black; margin: 0px 10px;'>";

    echo $header;
    echo "<div style='border: 2px solid #F88; height: 20px; background-color: #FBB; text-align: center; vertical-align: middle;'>[ This space is for ads, dynamic height ]</div>";
    echo $footer;

    echo "</div>";
}

//------------------------------------
// Email set to be edited
//------------------------------------
if($_GET['action'] == 'Edit')
{
    echo "<br><b>Default settings for ". htmlentities($_GET['emailset_title'], ENT_QUOTES) ."</b><br>";
    $emailset_title = mysql_real_escape_string($_GET['emailset_title']);
    list($header,$footer,$separator,$ishtml, $inbox, $charset, $frequenzy) = mysql_fetch_array(mysql_query("SELECT `header`, `footer`, `separator`, `html`, `inbox`, `charset`, `frequenzy` FROM ".mysql_prefix."scheduler_emailsets WHERE title = '$emailset_title'"));
    
    //------------------------------------
    // Show editor
    //------------------------------------
    echo "<form method='POST' action='". $_SERVER['PHP_SELF'] ."'>\n";
    echo "<input type='hidden' name='action' value='save'>";
    echo "<input type='hidden' name='emailset_title' value='". htmlentities($_GET['emailset_title'], ENT_QUOTES) ."'>";
    echo "<table width='100%'border='0'>\n";
    echo "<tr><td align='right'>Type:</td><td>\n";

    echo "<select name='ishtml'>\n";
    if($ishtml == 'Y')
    {
        $html = 'selected';
        $plain = '';
    }else
    {
        $html = '';
        $plain = 'selected';
    }

    echo "<option value='Y' $html>HTML</option>\n";
    echo "<option value='N' $plain>Plain text</option>\n";

    echo "</select></td></tr>";
    echo "<tr><td align='right'>Site inbox only:</td><td>\n";

    if($inbox == 'Y')
    {
        $inbox = 'checked';
    }else
    {
        $inbox = '';
    }

    echo "<input type='checkbox' name='inbox' value='Y' $inbox></td></tr>\n";

    echo "<tr><td align='right'>Charset:</td><td><input type='textbox' name='charset' style='width: 100px;' value='$charset'></td></tr>\n";
    echo "<tr><td align='right'>Frequenzy:</td><td><input type='textbox' name='frequenzy' style='width: 100px;' value='". number_format($frequenzy / 60,1) ."'></td></tr>\n";

    echo "<tr><td align='right'>Header:</td><td><textarea name='header' style='width: 500px; height: 300px;'>$header</textarea></td></tr>\n";
    // echo "<tr><td align='right'>Separator:</td><td><textarea name='separator' style='width: 500px; height: 100px;'>$separator</textarea></td></tr>\n";
    echo "<tr><td align='right'>Footer:</td><td><textarea name='footer' style='width: 500px; height: 300px;'>$footer</textarea></td></tr>\n";
    echo "</table>\n\n";
    echo "<center><input type=submit value='Save Changes'></form></center>\n\n";
}

//------------------------------------
// Header/footer import
//------------------------------------
if($_GET['action'] == 'Import')
{
    echo "<ul><li>Emailset with same title than imported will be overwritten.</li></ul>";

    if($_GET['plugin'] == 'admgr')
    {
        //------------------------------------
        // Something imported?
        //------------------------------------
        if($_GET['plugin'] == 'save')
        {
            list($admgr_header, $admgr_footer, $admgr_separator) = mysql_query("SELECT `header`,`footer`,`splitter` FROM ".mysql_prefix."admgr_headers WHERE `name` = '". mysql_real_escape_string($_POST['headerfooter']) ."' LIMIT 1");
            mysql_query("REPLACE INTO ".mysql_prefix."scheduler_emailsets 
                (`title`,`header`,`separator`,`footer`) VALUES 
                ('". mysql_real_escape_string($_POST['emailset_title']) ."','$admgr_header','$admgr_separator','$admgr_footer')");
        }
        //------------------------------------
        // Pick available sets
        //------------------------------------
        $query = mysql_query("SELECT * FROM ".mysql_prefix."admgr_headers WHERE 1");
        
        echo "<table border='1' cellpadding='2' cellspacing='0'>\n";
        echo "<tr><td align='center'><b>Header/footer</b></td><td align='center'><b>Import as</b></td></tr>\n";
        while($row = mysql_fetch_array($query))
        {
            echo "<tr><td>$row[name]</td><td>
        
            <form style='margin:0px' method='POST' action='". $_SERVER['PHP_SELF'] ."'>
            <input type='text' style='width:250px;' name='emailset_title' value='admgr_$row[name]'>
            <input type='hidden' name='subaction' value='save'>
            <input type='hidden' name='plugin' value='admgr'>
            <input type='hidden' name='headerfooter' value='".htmlentities($row['name'])."'>
            <input type='submit' name='action' value='Import'>";
        
            echo "</form></td><tr>\n";
        }
        echo "</table>\n";
        echo "<br>\n\n";
    }
}


footer(); 
?>

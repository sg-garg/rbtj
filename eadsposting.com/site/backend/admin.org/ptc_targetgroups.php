<?php
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
$title='PTC Targeting';
admin_login();
include("ptcadmgr_menu.php");
echo "<h2>PTC Target Groups Editor</h2>\n";
echo "<ul>";
//------------------------------------
// Save modified target group
//------------------------------------
if($_POST['action'] == 'Save')
{
    $targeting = '';
    if($_POST['countries'])
    {
        foreach($_POST['countries'] as $key => $country)
        {
            $targeting .= "|c:$country|";
        } 
    }
    $id = (int)($_POST['id']);

    mysql_query("UPDATE ".mysql_prefix."ptc_targetgroups SET targeting = '$targeting' WHERE id = $id")
    or die("Error saving targeting group!");
    echo "<li><b>Target group saved</b></li>\n\n";
}

//------------------------------------
// Delete requested group
//------------------------------------
if($_GET['action'] == 'Delete')
{
    mysql_query("DELETE FROM `".mysql_prefix."ptc_targetgroups` WHERE id = '". (int)($_GET['id'])."'");
    echo "<li><b>Target group deleted</b></li>\n\n";
}
//------------------------------------
// Create new targeting group
//------------------------------------
if($_GET['action'] == 'Create')
{
    $name = mysql_real_escape_string($_GET['name']);

    mysql_query("REPLACE INTO `".mysql_prefix."ptc_targetgroups` (`name`)VALUES ('$name');")
    or die("Error creating new email set!");
    echo "<li><b>Target group created</b></li>\n\n";
}
    echo "<li>Editing a target group does not affect existing ads</li></ul>\n\n";
//---------------------------------------
// Get existing groups
//---------------------------------------
$query = mysql_query("SELECT * FROM `".mysql_prefix."ptc_targetgroups` WHERE 1");
echo "<table border='1' cellpadding='2' cellspacing='0'>\n";
echo "<tr><th>Target group</th><th>Action</th></tr>\n";
while($row = mysql_fetch_array($query))
{
    echo "<tr><td>$row[name]</td><td>

    <form style='margin:0px' method='GET' action='". $_SERVER['PHP_SELF'] ."'>
    <input type='hidden' name='id' value='$row[id]'>
    <input type='submit' name='action' value='Edit'>
    <input type='submit' name='action' value='Delete'>";

    echo "</form></td><tr>\n";
}
echo "<tr><td><form style='margin:0px' method='GET' action='". $_SERVER['PHP_SELF'] ."'><input type='text' style='width: 150px' name='name'></td><td><input type='submit' name='action' value='Create'></form></td><tr>\n";
echo "</table>\n";
echo "<br>\n\n";
//------------------------------------
// Target group to be edited
//------------------------------------
if($_GET['action'] == 'Edit')
{
    $id = (int)$_GET['id'];
    list($targeting) = mysql_fetch_array(mysql_query("SELECT `targeting` FROM ".mysql_prefix."ptc_targetgroups WHERE id = $id"));
    
    //------------------------------------
    // Show editor
    //------------------------------------
    echo "<form method='POST' action='". $_SERVER['PHP_SELF'] ."'>\n";
    echo "<input type='hidden' name='id' value='$id'>";
    ?>

        <script type='text/javascript'>
        function show_table()
        {
            document.getElementById('targeting').style.display = 'block';
        }
        function hide_table()
        {
            document.getElementById('targeting').style.display = 'none';
        }
        function show_members_yes()
        {
            var e = document.getElementsByName('members_yes');
            for (var i = 0; i < e.length; i++) {
                e[i].style.display = 'block';
            }
        }
        function hide_members_yes()
        {
            var e = document.getElementsByName('members_yes');
            for (var i = 0; i < e.length; i++) {
                e[i].style.display = 'none';
            }
        }
        function show_members_no()
        {
            var e = document.getElementsByName('members_no');
            for (var i = 0; i < e.length; i++) {
                e[i].style.display = 'block';
            }
        }
        function hide_members_no()
        {
            var e = document.getElementsByName('members_no');
            for (var i = 0; i < e.length; i++) {
                e[i].style.display = 'none';
            }
        }

        </script>
        [<span class='fakelink' onClick="show_members_yes()">Show</span>/<span class='fakelink' onClick="hide_members_yes()">hide</span> countries with members] [<span class='fakelink' onClick="show_members_no()">Show</span>/<span class='fakelink' onClick="hide_members_no()">hide</span> countries without members] [<a href='countries.php' target='_blank'>Edit</a> country list]<br>
        <table id='targeting' border='0' cellpadding='1' cellspacing='0'>
        <tr>
        <td valign='top'>
        <?php 
        $getkeys=mysql_query("SELECT * from ".mysql_prefix."countries WHERE 1");
        $member_countries_query=mysql_query("SELECT ".mysql_prefix."countries.country from ".mysql_prefix."countries left join ".mysql_prefix."users on ".mysql_prefix."countries.country=".mysql_prefix."users.country where ".mysql_prefix."users.country is not null group by ".mysql_prefix."users.country");
        $member_countries = array();
        while($row = mysql_fetch_array($member_countries_query))
        {
            array_push($member_countries, $row[0]);
        }

        $countries = mysql_num_rows($getkeys);
        $per_column = (int)(($countries / 5) + 1);
        $line=0;
        $idx = 1;
        while($row=mysql_fetch_row($getkeys))
        {
            if(!empty($row[0]))
            {
                //-------------------------
                // Members from the country?
                //-------------------------
                if(in_array($row[0], $member_countries))
                {
                    $name = 'members_yes';
                }else{
                    $name = 'members_no';
                }
                //-------------------------
                // Country targeted in old setting?
                //-------------------------
                if (strpos($targeting, "|c:$row[0]|") === false) 
                {
                    $checked = '';
                }else{
                    $checked = ' checked';
                }
                //-------------------------
                // Country targeted in old setting?
                //-------------------------
                if (strpos($targeting, "|c:$row[0]|") === false) 
                {
                    $checked = '';
                }else{
                    $checked = ' checked';
                }
                echo "\n<div name='$name'><input id=\"$row[0]\"type='checkbox' $checked class='checkbox' name='countries[$idx]' value=\"$row[0]\">$row[0]<br></div>";
                $idx++;
                $line++;
                if ($line > $per_column){ echo "</td>\n<td valign='top'>"; $line=0;}
            }
        }?>
        </td>
        </tr>
        </table>
        <script type='text/javascript'>
            var e = document.getElementsByName('members_yes');
            for (var i = 0; i < e.length; i++) {
                e[i].style.display = 'block';
            }
            var e = document.getElementsByName('members_no');
            for (var i = 0; i < e.length; i++) {
                e[i].style.display = 'none';
            }
        </script>
    <?php

    echo "<center><input type=submit name='action' value='Save'></form></center>\n\n";
}
footer(); 
?>
<?php
include("../conf.inc.php");
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
$title='eMail Schedule';
admin_login();
include("scheduler_menu.php");
echo "<h2>Scheduled Email Editor</h2>";
$id = (int)$_POST['id'];

if ($_POST['action'] == "Save Changes")
{
    //------------------------------------
    // Keyword selected
    //------------------------------------
    if ($_POST['keyword'])
    {
        reset($_POST['keyword']);
        $interests='||';
        while (list($key, $value) = each($_POST['keyword']))
        {
            $value = strtolower(addslashes($value));
            $interests=$interests.$value.'||';
            if ($value)
            {
                if (substr($value,0,2)!="g:" and substr($value,0,2)!="c:" and substr($value,0,2)!="s:")
                {
                    $words=$words.$and." keywords like '%||$value||%'";
                    $and=" and";
                }
                else 
                {
                    if (substr($value,0,2)=="s:")
                    {
                        $value=str_replace("s:","",$value);
                        $states=$states.$sor." state='$value'";
                        $sor=" or";
                    }
                    if (substr($value,0,2)=="c:")
                    { 
                        $value=str_replace("c:","",$value);
                        $countries=$countries.$cor." country='$value'";
                        $cor=" or";
                    }
                    if (substr($value,0,2)=="g:")
                    {
                        $value=str_replace("g:","",$value);
                        if ($value=='inactive')
                        {
                            $mailinactive=1;
                            continue;
                        }
                        if ($value=='free'){$value='';}
                        $membership=$membership.$mor." account_type='$value'";
                        $mor=" or";
                    }
                }
            }
        }
    }

    //------------------------------------
    // form query to count receivers
    //------------------------------------
    if ($words or $countries or $states or $membership)
    {
        if ($words)
        {
            $words='and '.$words;
        }
        if ($states && $countries)
        {
            $states='and ('.$states.' or ';
            $countries=$countries.')';
        }
        else 
        {
            if ($states)
            {
                if ($sor){
                $states='and ('.$states.')';}
                else {
                $states='and '.$states;}
            }
            if ($countries)
            {
                if ($cor)
                {
                    $countries='and ('.$countries.')';
                }
                else 
                {
                    $countries='and '.$countries;
                }
            }
        }
        if ($membership)
        {
            if ($mor)
            {
                $membership='and ('.$membership.')';
            }
            else 
            {
                $membership='and '.$membership;
            }
        }
        $states=strtolower($states);
        $countries=strtolower($countries);
        $membership=strtolower($membership);

        if (!$mailinactive && defined('nocreditdays') && nocreditdays>0)
        {
            $activepart1 = "LEFT JOIN #MYSQLPREFIX#last_login ON (#MYSQLPREFIX#users.username=#MYSQLPREFIX#last_login.username) ";;
            $activepart2 = '`time`>#LASTACTIVE# and';
        }
        if ($inactives_only == 1 && defined('nocreditdays') && nocreditdays>0)
        {
            $activepart1="LEFT JOIN #MYSQLPREFIX#last_login ON (#MYSQLPREFIX#users.username=#MYSQLPREFIX#last_login.username) ";
            $activepart2=' (`time`<#LASTACTIVE# OR `time` is null ) AND ';
        }
         $query_for_receivers = "
                            SELECT count(*) FROM #MYSQLPREFIX#users
                            $activepart1
                            LEFT JOIN #MYSQLPREFIX#interests ON (#MYSQLPREFIX#users.username=#MYSQLPREFIX#interests.username) 
                            WHERE $activepart2 email_setting>=0 and account_type!='canceled' and vacation<'#CURDATE#' $words $states $countries $membership";

        if ($hicount<$numtosend && strtolower($numtosend)!='auto')
        {
            $numtosend=$hicount;
        }

    }
    if(empty($_POST['title']))
    {
        $_POST['title'] = "[no subject]";
    }
    $frequenzy = (int)($_POST['frequenzy'] * 60);
    $inboxonly = (int)$_POST['inboxonly'];
    $subject = mysql_real_escape_string($_POST['title']);
    $headerset = mysql_real_escape_string($_POST['headerset']);
    $content = mysql_real_escape_string($_POST['content']);
    $html = mysql_real_escape_string($_POST['html']);

    $query = "UPDATE ".mysql_prefix."scheduler_emails SET subject = '$subject', charset = '".addslashes($_POST['charset'])."', is_html = '$html', content = '$content', inboxonly = '$inboxonly', frequenzy = '$frequenzy', next_send = '$_POST[next_send]', headerset = '$headerset', keywords = '$interests', receivers_query = '".mysql_real_escape_string($query_for_receivers)."' WHERE id = '$id'";

    echo "<center>Saving the scheduled email: "; 
    $result = mysql_query($query); 
    if (!$result) 
    {
        $msg  = " <font color='red'><b>ERROR!</b></font><br><b>Invalid query:</b> " . mysql_error() . "<br><br><b>Query:</b> " . $query;
        die($msg);
    }else{
        echo " <font color='green'><b>done</b></font><br>";
    }

}



if($_POST['action'] == "Edit" OR $_POST['action'] == "Fetch Settings for this email set")
{
    $ad = mysql_fetch_array(mysql_query("SELECT * FROM ".mysql_prefix."scheduler_emails WHERE id = '$id' LIMIT 1"));
    $interests=$ad['keywords'];
    if(empty($_POST['headerset']))
    {
        $_POST['headerset'] = $ad['headerset'];
    }
    //------------------------------------
    // Show email edit form
    //------------------------------------
    echo "<form method='POST'>";

    echo "<input type='hidden' value='$id' name='id'>";

    echo "<table width='100%'border='0'>";
    echo "<tr><td align='right'>Email set:</td><td>";


    echo "<select name='headerset'>\n";

    $selected = '';
    if($_POST['headerset'] == 'default')
    {
        $selected = 'selected';
    }
    echo "<option value='default' $selected>default</option>\n";


    $headersets = mysql_query("SELECT * FROM `". mysql_prefix ."scheduler_emailsets` WHERE title != 'default' ORDER BY title ASC");
    while($headerset = mysql_fetch_array($headersets))
    {
        $selected = '';
        if($headerset['title'] == $_POST['headerset'])
        {
            $selected = 'selected';
        }
        echo "<option value='". htmlentities($headerset['title'], ENT_QUOTES) ."' $selected>". htmlentities($headerset['title'], ENT_QUOTES) ."</option>\n";
    }



    if($_POST['action'] == "Fetch Settings for this email set")
    {
        list($ishtml, $inbox, $charset, $frequenzy) = mysql_fetch_array(mysql_query("SELECT `html`, `inbox`, `charset`, `frequenzy` FROM ".mysql_prefix."scheduler_emailsets WHERE title = '". mysql_real_escape_string($_POST['headerset']) ."'"));
    }
    if($_POST['action'] == "Edit")
    {
        list($ishtml, $inbox, $charset, $frequenzy) = mysql_fetch_array(mysql_query("SELECT `is_html`, `inboxonly`, `charset`, `frequenzy` FROM ".mysql_prefix."scheduler_emails WHERE id = '$id'"));
    }
 

    echo "</select> <input type='submit' name='action' value='Fetch Settings for this email set'></td></tr>";

    echo "<tr><td align='right'>Send as</td><td>";

    echo "<select name='html'>\n";
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

    echo "</select>";

    echo " email.</td></tr>";


    echo "<tr><td align='right'>Character Set:</td><td><input type='text' name='charset' value='$charset'></td></tr>";
    echo "<tr><td align='right'>Send every </td><td><input type='text' name='frequenzy' value='". number_format($frequenzy / 60,1,'.','') ."'> hours</td></tr>";
    echo "<tr><td align='right'>Next send </td><td><input type='text' name='next_send' value='$ad[next_send]'></td></tr>";

    echo "<tr><td align='right'>Site inbox only:</td><td>";

    if($inbox == '1')
    {
        $inbox = 'checked';
    }else
    {
        $inbox = '';
    }

    echo "<input type='checkbox' name='inboxonly' value='1' $inbox></td></tr>\n";
    echo "<tr><td align='right'>Subject:</td><td><input maxlength='64' type='text' value='$ad[subject]' name='title' style='width: 500px;'></td></tr>";
    echo "<tr><td align='right' valign='top'>Content:</td><td><textarea name='content' style='width: 500px; height: 500px;'>$ad[content]</textarea></td></tr>";
    
    echo "<tr><td align='right'>Membership types:</td><td>";
        email_membertypes();
    echo "</td></tr>";

    echo "<tr><td align='right'>Targeting:</td><td>";
        email_targeting();
    echo "</td></tr>";

    echo "</table>";
    echo "<center><input type=submit value='Save Changes' name='action'></form></center>";
}



footer(); 
?>

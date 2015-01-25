<?php
if(allow_ad_removal == '1')
{
    //------------------------------------------
    // Email deletion from adstats.php
    //------------------------------------------
    if ($_POST['adtype'] == 'email')
    {
        if($_POST['confirmation_delete'] != $_POST['confirmation_user'])
        {
            $form_errors['confirmation_user_email'] = 1;
        }
        else
        {
            foreach($_POST['emailads'] as $ad => $delete)
            {
                if(!empty($delete))
                {
                    $delete = preg_replace("([^a-zA-Z0-9])", "", $delete);
                    list($ad_username) = mysql_fetch_array(mysql_query("SELECT id FROM ".mysql_prefix."email_ads WHERE emailid = '$delete'"));

                    if($_SESSION['username'] == $ad_username)
                    {
                        mysql_query("drop table ".mysql_prefix."paid_clicks_$delete");
                        mysql_query("delete from ".mysql_prefix."email_ads where emailid='$delete'");
                        //echo "Deleted: $delete<br>";
                    }
                    else
                    { 
                        exit("Unauthorized ad deletion attempt"); 
                    }

                }
            }
        }
    }
    
    //------------------------------------------
    // HTML ad deletion from adstats.php
    //------------------------------------------
    if ($_POST['adtype'] == 'html')
    {
        if($_POST['confirmation_delete'] != $_POST['confirmation_user'])
        {
            $form_errors['confirmation_user_html'] = 1;
        }
        else
        {
            foreach($_POST['htmlad'] as $ad => $delete)
            {
                if(!empty($delete))
                {
                    $delete = ereg_replace("[^a-zA-Z0-9]", "", $delete);
                    list($ad_username) = mysql_fetch_array(mysql_query("SELECT id FROM ".mysql_prefix."rotating_ads WHERE bannerid = '$delete'"));

                    if($_SESSION['username'] == $ad_username)
                    {
                        mysql_query("delete from ".mysql_prefix."rotating_ads where bannerid='$delete'");
                        //echo "Deleted: $delete<br>";
                    }
                    else
                    { 
                        exit($_SESSION['username'] .", unauthorized ad deletion attempt"); 
                    }
                }
            }
        }
    }
    
    //------------------------------------------
    // Banner ad deletion from adstats.php
    //------------------------------------------
    if ($_POST['adtype'] == 'banner')
    {
        if($_POST['confirmation_delete'] != $_POST['confirmation_user'])
        {
            $form_errors['confirmation_user_banner'] = 1;
        }
        else
        {
            foreach($_POST['banner'] as $ad => $delete)
            {
                if(!empty($delete))
                {
                    $delete = ereg_replace("[^a-zA-Z0-9]", "", $delete);
                    list($ad_username) = mysql_fetch_array(mysql_query("SELECT id FROM ".mysql_prefix."rotating_ads WHERE bannerid = '$delete'"));

                    if($_SESSION['username'] == $ad_username)
                    {
                        mysql_query("delete from ".mysql_prefix."rotating_ads where bannerid='$delete'");
                        //echo "Deleted: $delete<br>";
                    }
                    else
                    { 
                        exit($_SESSION['username'] .", unauthorized ad deletion attempt"); 
                    }
                }
            }
        }
    }
    
    //------------------------------------------
    // Popup ad deletion from adstats.php
    //------------------------------------------
    if ($_POST['adtype'] == 'popup')
    {
        if($_POST['confirmation_delete'] != $_POST['confirmation_user'])
        {
            $form_errors['confirmation_user_popup'] = 1;
        }
        else
        {
            foreach($_POST['popup'] as $ad => $delete)
            {
                if(!empty($delete))
                {
                    $delete = ereg_replace("[^a-zA-Z0-9]", "", $delete);
                    list($ad_username) = mysql_fetch_array(mysql_query("SELECT id FROM ".mysql_prefix."rotating_ads WHERE bannerid = '$delete'"));

                    if($_SESSION['username'] == $ad_username)
                    {
                        mysql_query("delete from ".mysql_prefix."rotating_ads where bannerid='$delete'");
                        //echo "Deleted: $delete<br>";
                    }
                    else
                    { 
                        exit($_SESSION['username'] .", unauthorized ad deletion attempt"); 
                    }
                }
            }
        }
    }
    
    //------------------------------------------
    // Popup ad deletion from adstats.php
    //------------------------------------------
    if ($_POST['adtype'] == 'ptc')
    {
        if($_POST['confirmation_delete'] != $_POST['confirmation_user'])
        {
            $form_errors['confirmation_user_ptc'] = 1;
        }
        else
        {
            foreach($_POST['ptcad'] as $ad => $delete)
            {
                if(!empty($delete))
                {
                    $delete = ereg_replace("[^a-zA-Z0-9]", "", $delete);
                    list($ad_username) = mysql_fetch_array(mysql_query("SELECT id FROM ".mysql_prefix."ptc_ads WHERE ptcid = '$delete'"));

                    if($_SESSION['username'] == $ad_username)
                    {
                        mysql_query("delete from ".mysql_prefix."paid_clicks where id='$delete'");
                        mysql_query("delete from ".mysql_prefix."ptc_ads where ptcid='$delete'");
                        //echo "Deleted: $delete<br>";
                    }
                    else
                    { 
                        exit($_SESSION['username'] .", unauthorized ad deletion attempt"); 
                    }
                }
            }
        }
    }
}
?>

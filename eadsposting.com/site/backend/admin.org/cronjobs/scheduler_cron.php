<?php


$cronjobs[]=array('classname'=>'cc_admin_scheduler');

class cc_admin_scheduler 
{

var $class_name='cc_admin_scheduler';
var $minutes=4;

function cronjob()
{

    $curdate=date("Y-m-d",unixtime);
    $somethingsent = false;
    //----------------------------------------------
    // Start by selecting emails to be created
    //----------------------------------------------
    $scheduler_query = mysql_query("SELECT * FROM ".mysql_prefix."scheduler_emails WHERE next_send <= NOW() AND status != 'Paused'  ORDER BY id ASC");
    
    while($scheduled_email = mysql_fetch_array($scheduler_query))
    {
        $sc_tmp = "";
        $sc_tmp2 = "";
        $sc_ads = array();
    
    //     echo "<hr><b>- Processing: $scheduled_email[subject] [ $scheduled_email[id] ]-</b><br>";
    
        //----------------------------------------------
        // Start by selecting emails to be created
        //----------------------------------------------
    //     echo "<b>- Detecting ads in it -</b><br>";
        $sc_tmp = explode("</PM>", $scheduled_email['content']);
        $sc_ads_count = 0;
        foreach($sc_tmp as $sc_ad)
        {
            list(,$sc_tmp2) = explode("<PM>", $sc_ad);
            if(!empty($sc_tmp2))
            {
                $sc_ads[$sc_ads_count] = $sc_tmp2;
                $sc_ads_count++;
            }
        }
    
        if($sc_ads_count == 0)
        {
    //         echo "No active ads!<br>";
            continue;
        }
    
        //----------------------------------------------
        // Remove expired ads
        //----------------------------------------------
        foreach($sc_ads as $sc_ad)
        {
    //         echo "$sc_ad is ";
            $sc_tmp = mysql_fetch_array(mysql_query('SELECT *,last_sent+0 AS ts_last_sent FROM ' . mysql_prefix . 'email_ads WHERE emailid='.$sc_ad.' LIMIT 1'));
    
            if (!$sc_tmp[0])
            {
    //             echo "invalid ad";
                $sc_ads_count--;
                $scheduled_email['content'] = str_replace("<PM>$sc_ad</PM>", "", $scheduled_email['content']);
            }
            else if (
                    ($sc_tmp['clicks'] >= $sc_tmp['run_quantity'] && $sc_tmp['run_type'] == 'clicks') or 
                    (mysqldate >= $sc_tmp['run_quantity'] && $sc_tmp['run_type'] == 'date')
                    )
            {
    //             echo "expired, removing from content";
                $sc_ads_count--;
                $scheduled_email['content'] = str_replace("<PM>$sc_ad</PM>", "", $scheduled_email['content']);
            } else 
            {
    //             echo "active";
            }
    //         echo "<br>";
    
        }
        if($sc_ads_count == 0)
        {
    //         echo "No active ads!<br>";
            continue;
        }
        //----------------------------------------------
        // Create header/separator/footer
        //----------------------------------------------
        if($scheduled_email['is_html'] == 'Y')
            $sep = "<br>";
        else
            $sep = "\n";
    //     echo "<b>- Process header/footer/separators -</b><br>";
        list($header, $separator, $footer) = mysql_fetch_array(mysql_query("SELECT `header`, `separator`, `footer` FROM ".mysql_prefix."scheduler_emailsets WHERE title = '$scheduled_email[headerset]'"));
        $scheduled_email['content'] = $sep . $header . $sep . $scheduled_email['content'] . $sep . $footer . $sep;
        $scheduled_email['content'] = str_replace("<SEPARATOR>", $separator, $scheduled_email['content']);
    
        //----------------------------------------------
        // Finalize query to calculate receivers
        //----------------------------------------------
    //     echo "<b>- Process receivers_query -</b><br>";
    
        $scheduled_email['receivers_query'] = str_replace("#MYSQLPREFIX#", mysql_prefix, $scheduled_email['receivers_query']);
        $scheduled_email['receivers_query'] = str_replace("#CURDATE#", $curdate, $scheduled_email['receivers_query']);
        $scheduled_email['receivers_query'] = str_replace("#LASTACTIVE#", date("YmdH",unixtime-(nocreditdays*86400)), $scheduled_email['receivers_query']);
    
    //     echo "<b>- Receivers query -</b><br>";
    //     echo "<font color='green'>$scheduled_email[receivers_query]</font><br>";
        list($scheduled_email['receivers']) = mysql_fetch_array(mysql_query($scheduled_email['receivers_query']));
    
        //----------------------------------------------
        // Finalize email content
        //----------------------------------------------
    //     echo "<b>- Final email content -</b><br>";
    //     echo "<font color='red'>\n<!-- ---------------- --><pre>\n\n\n$scheduled_email[content]\n\n\n</pre>\n<!-- ---------------- --></font>";
    
    
        //----------------------------------------------
        // Email data for massmailer
        //----------------------------------------------
    //     echo "<b>- Data for massmailer -</b><br>";
    // 
    //     echo "Subject = $scheduled_email[subject]<br>";
    //     echo "Charset = $scheduled_email[charset] <br>";
    //     echo "Is_html = $scheduled_email[is_html]<br>";
    //     echo "Keywords = $scheduled_email[keywords]<br>";
    //     echo "Inboxonly = $scheduled_email[inboxonly]<br>";
    //     echo "Receivers = $scheduled_email[receivers]<br>";
    // 
    //     echo "<b>- Saving email -</b><br>";
        echo date("H:i:s") . " sending email ".$scheduled_email['subject']." to massmailer...<br>\n";
        $somethingsent = true;
        mysql_query('INSERT INTO '.mysql_prefix.'mass_mailer SET charset="'.addslashes($scheduled_email['charset']).'",time="'.mysqldate.'",subject="'.addslashes($scheduled_email['subject']).'",keywords="'.$scheduled_email['keywords'].'",inboxonly="'.$scheduled_email['inboxonly'].'",is_html="'.$scheduled_email['is_html'].'",stop='.$scheduled_email['receivers'].',current="1",ad_text="'.addslashes($scheduled_email['content']).'"');
        mysql_query('UPDATE '.mysql_prefix.'scheduler_emails SET last_sent=NOW(), next_send = DATE_ADD(NOW(), INTERVAL frequenzy MINUTE) WHERE id ="'.$scheduled_email['id'].'"');
    
    
    }

    if(!$somethingsent)
    {
        echo date("H:i:s") . " no scheduled emails to send this time...<br>\n";
    }
    
    

}
}

return;
?>

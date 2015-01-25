<?php
$idx = 0;

function check_scheduled_status($id)
{
    list($scheduled_email) = mysql_fetch_array(mysql_query("SELECT content FROM ".mysql_prefix."scheduler_emails WHERE id ='$id'"));
    $sc_tmp = "";
    $sc_tmp2 = "";
    $sc_ads = array();

    //----------------------------------------------
    // Start by selecting emails
    //----------------------------------------------
    $sc_tmp = explode("</PM>", $scheduled_email);
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
        return "Expired";
    }

    //----------------------------------------------
    // Remove expired ads
    //----------------------------------------------
    foreach($sc_ads as $sc_ad)
    {
        $sc_tmp = mysql_fetch_array(mysql_query('SELECT *,last_sent+0 AS ts_last_sent FROM ' . mysql_prefix . 'email_ads WHERE emailid='.$sc_ad.' LIMIT 1'));

        if (!$sc_tmp[0])
        {
            $sc_ads_count--;
            $scheduled_email = str_replace("<PM>$sc_ad</PM>", "", $scheduled_email);
        }
        else if (
                 ($sc_tmp['clicks'] >= $sc_tmp['run_quantity'] && $sc_tmp['run_type'] == 'clicks') or 
                 (mysqldate >= $sc_tmp['run_quantity'] && $sc_tmp['run_type'] == 'date')
                )
        {
            $sc_ads_count--;
            $scheduled_email = str_replace("<PM>$sc_ad</PM>", "", $scheduled_email);
        }

    }
    if($sc_ads_count == 0)
    {
        return "Expired";
    }
    return "Active";
}

function email_targeting()
{
    global $idx, $interests;
    ?>
    (Do not select any targeting if you want to send to everyone in the selected membership type(s))
    <table border='0'>
    <tr>
    <td valign='top'>
        <b>Keywords:</b><br>
        <?php 
        $getkeys=@mysql_query("select keyword from ".mysql_prefix."keywords where keycount>0 order by keyword");
        while($row=@mysql_fetch_row($getkeys))
        {
            $line++;
            echo "\n<input type='checkbox' class='checkbox' name='keyword[$idx]' value=\"".safeentities($row[0])."\" ";
            interests($row[0],"checked");
            echo ">$row[0]<br>";$idx++;
            if ($line>30){ echo "</td><td valign=top><br>"; $line=0;}
        }
        ?>
        </td>
        <td valign='top'>
        <b>Countries:</b><br>
        <?php 
        $getkeys=@mysql_query("select ".mysql_prefix."countries.country from ".mysql_prefix."countries left join ".mysql_prefix."users on ".mysql_prefix."countries.country=".mysql_prefix."users.country where ".mysql_prefix."users.country is not null group by ".mysql_prefix."users.country");
        $line=0;
        while($row=@mysql_fetch_row($getkeys))
        {
            echo "\n<input type='checkbox' class='checkbox' name='keyword[$idx]' value=\"c:$row[0]\" ";
            interests("c:".$row[0],"checked");
            echo ">$row[0]<br>";$idx++; $line++;
            if ($line>30){ echo "</td><td valign=top><br>"; $line=0;}
        }?>
        </td>
        <td valign='top'>
        <b>States:</b><br>
        <?php $getkeys=@mysql_query("select ".mysql_prefix."states.state from ".mysql_prefix."states left join ".mysql_prefix."users on ".mysql_prefix."states.state=".mysql_prefix."users.state where ".mysql_prefix."users.state
        is not null group by ".mysql_prefix."users.state"); 
        $line=0;
        while($row=@mysql_fetch_row($getkeys))
        {
            echo "\n<input type='checkbox' class='checkbox' name='keyword[$idx]' value=\"s:$row[0]\" "; 
            interests("s:".$row[0],"checked");
            echo ">$row[0]<br>";$idx++; $line++;  
            if ($line>30){ echo "</td><td valign=top><br>"; $line=0;} 
        }?> 
    
        </td>
    </tr>
    </table>
    <? return;
}

function email_membertypes()
{
    global $idx, $interests;
    echo "\n<input type='checkbox' class='checkbox' name='keyword[$idx]' value=\"g:advertiser\" ";
    interests("g:advertiser","checked");
    echo ">Advertiser ";$idx++;
    echo "\n<input type='checkbox' class='checkbox' name='keyword[$idx]' value=\"g:suspended\" ";        
    interests("g:suspended","checked");
    echo ">Suspended ";$idx++;  
    echo "\n<input type='checkbox' class='checkbox' name='keyword[$idx]' value=\"g:free\" "; 
    if ($interests=='none')
        echo 'checked';
    else 
        interests("g:free","checked");

    echo ">Custom ";$idx++;    
    $getkeys=@mysql_query("select ".mysql_prefix."member_types.description from ".mysql_prefix."member_types left join ".mysql_prefix."users on ".mysql_prefix."member_types.description=".mysql_prefix."users.account_type where ".mysql_prefix."users.account_type is not null group by ".mysql_prefix."users.account_type");
    while($row=@mysql_fetch_row($getkeys))
    {
        echo "<input type='checkbox' class='checkbox' name='keyword[$idx]' value=\"g:$row[0]\" ";
        if ($interests=='none')
        {
            echo 'checked';
        } else 
        {
            interests("g:".$row[0],"checked");
        }
        echo ">$row[0] ";
        $idx++; 
    }
    if (defined('nocreditdays') && nocreditdays>0)
    {
        //---------------------------------
        // Active members only selection
        //---------------------------------
        echo "<br><br>\n<input type='radio' name='keyword[$idx]' value=''";
        if (!interests("g:inactive","return") AND !interests("g:inactives_only","return") AND $interests!='none')
        {
            echo 'checked'; 
        }
        echo ">Active members only (last login within ".nocreditdays." days) from the above selected groups";
    
        //---------------------------------
        // In+active members selection
        //---------------------------------
        echo "<br>\n<input type='radio' name='keyword[$idx]' value=\"g:inactive\" ";
        if ($interests=='none')
        {
            echo 'checked'; 
        }
        else  
        {
            interests("g:inactive","checked");
        }
        echo ">Both active and inactive members";
    
        //---------------------------------
        // Inactive members only selection
        //---------------------------------
        echo "<br>\n<input type='radio' name='keyword[$idx]' value=\"g:inactives_only\" ";
    
        if ($interests!='none')
        {
            interests("g:inactives_only","checked");
        }
        echo ">Inactive members only (last login over ".nocreditdays." days ago or more)";
    
        $idx++;
    }
    return;
}

?>
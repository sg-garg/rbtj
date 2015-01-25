<?php
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
if(!empty($_POST)) extract($_POST,EXTR_SKIP);
if(!empty($_GET)) extract($_GET,EXTR_SKIP);
$title='PTC Advertisement Manager';
admin_login();
include("ptcadmgr_menu.php");
echo "<h2>PTC Manager</h2>";
$description = mysql_real_escape_string($_POST['description']);
$html = mysql_real_escape_string($_POST['html']);
$id = substr(preg_replace("([^a-zA-Z0-9])", "", $id),0,16);
$category = mysql_real_escape_string($_POST['category']);
$alt_text = mysql_real_escape_string($_POST['alt_text']);
$text_ad = mysql_real_escape_string($_POST['text_ad']);
//-----------------------------------------------------------
// Process country targeting, group or custom
//-----------------------------------------------------------
$targeting = '';
if($_POST['targeting_type'] == 'custom')
{
    if($_POST['countries'])
    {
        foreach($_POST['countries'] as $key => $country)
        {
            $targeting .= "|c:$country|";
        } 
    }
}
else if($_POST['targeting_type'] == 'group')
{
    if($_POST['group'] == 'everyone')
    {
        $targeting = '';
    }else{
        list($targeting) = mysql_fetch_array(mysql_query("SELECT targeting FROM ".mysql_prefix."ptc_targetgroups WHERE id = ". (int)$_POST['group']." LIMIT 1"));
    }
}
else
{
    $targeting = '';
}

//-----------------------------------------------------------
// Process account type targeting
//-----------------------------------------------------------
$targeting_mtype = '';

if($_POST['accounttype'])
{
    foreach($_POST['accounttype'] as $key => $account_type)
    {
        $targeting_mtype .= "|g:$account_type|";
    } 
}



$value=$value*100000;
if ($vtype=='cash')
{
    $value=$value*admin_cash_factor;
}
if ($save==2 and $oldid)
{
    mysql_query("update ".mysql_prefix."ptc_ads set hrlock='$hrlock',id='$id',description='$description',image_url='$image_url',img_width='$img_width',img_height='$img_height',site_url='$site_url',html='$html',category='$category',run_quantity='$run_quantity',run_type='$run_type',text_ad='$text_ad',alt_text='$alt_text',value='$value',vtype='$vtype',timer='$timer', cheat_link='$cheat_link', targeting='$targeting', targeting_mtype='$targeting_mtype', turing='".(int)$turing."' where ptcid=$oldid");
}
if ($save==1)
{
    $searchphrase='';
    mysql_query("insert into ".mysql_prefix."ptc_ads set  hrlock='$hrlock',id='$id',description='$description',image_url='$image_url',site_url='$site_url',html='$html',category='$category',run_quantity='$run_quantity',run_type='$run_type',text_ad='$text_ad',alt_text='$alt_text',value='$value',vtype='$vtype',timer='$timer',img_width='$img_width',img_height='$img_height', cheat_link='$cheat_link', targeting='$targeting', targeting_mtype='$targeting_mtype', turing='".(int)$turing."', creation=NOW()");
}
if ($mode=='Delete')
{
    mysql_query("delete from ".mysql_prefix."paid_clicks where id='$ptcid'");
    mysql_query("delete from ".mysql_prefix."ptc_ads where ptcid='$ptcid'");
    mysql_query("optimize table ".mysql_prefix."paid_clicks");
}
?>

To place ads on your page use the following code: <b>&lt;?php action("get_ptc_ad_v2","PUT_GROUP_HERE,5,0,0,1");?&gt;</b><br>
Replace <i>PUT_GROUP_HERE</i> with the ad group name of the PTC advertisements you wish to display on that page<br>

<?php
if ($get=='search')
{
    $searchphrase="%".$searchphrase."%";
}
?>


<form action='ptcadmgr.php' method='POST'>Search PTC Ads Database: (leave blank to list all ads) 
    <input type='text' name='searchphrase'>
    <input type='hidden' name='get' value='search'>
    <input type='submit'  value='Search'><br>
    <!-- <a href='ptcadmgr.php#adform' target='_top'>Create a new ad campaign</a><br> -->
    <!-- <a href='oldptcads.php' target='_oldptcads'>List/Delete old paid to click ads</a> -->
</form>

<br>
<table class='centered' border='1' cellpadding='2' cellspacing='0'>
<?php
if (!$searchphrase)
{
    $searchphrase='*****************************';
}
$usersearchphrase=substr(preg_replace("([^a-zA-Z0-9])", "", $searchphrase),0,16);
if (!$usersearchphrase)
{
    $usersearchphrase='*****************************';
}
$getads = mysql_query("select * from ".mysql_prefix."ptc_ads where description!='#PAID-START-PAGE#' and (ptcid like '$searchphrase' or id like '$usersearchphrase' or category like '$searchphrase' or description like '$searchphrase' or ptcid=LAST_INSERT_ID()) order by category,id,description");

while($row=mysql_fetch_array($getads))
{
    $row['value']=$row['value']/100000;
    if ($row['vtype']=='cash')
    {
        $row['value']=$row['value']/admin_cash_factor;
    }
    $ctr="0";
    if($row['views'])
    {
        $ctr=number_format($row['clicks']/$row['views'],3)." to 1";
    }

    $row['time']=mytimeread($row['time']);

    if($row['creation'] == '0000-00-00 00:00:00')
    {
        $row['creation'] = 'n/a';
    }else{
        $row['creation']=mytimeread($row['creation']);
    }      

    if($bgcolor == ' class="row1" ')
        $bgcolor=' class="row2" ';
    else
        $bgcolor=' class="row1" ';

    if($row['hrlock'] > 0)
    {
        $lock = $row['hrlock'].'h';
    }else{
        $lock = "[inf]";
    }
    
    echo "
    <tr $bgcolor>
    <td rowspan='2'>
        <table border='0' cellpadding='2' cellspacing='0'width='100%'>
        <tr>
            <td width='75' align='right'><b>PTC ID:</b></td>
            <td>$row[ptcid]</td>
        </tr>
        <tr>
            <td align='right'><b>Username:</b></td>
            <td><a href='viewuser.php?userid=$row[id]' target='_user'>$row[id]</a></td>
        </tr>
        <tr>
            <td align='right'><b>Description:</b></td>
            <td><div style='white-space: nowrap; width:150px; overflow:hidden;' title='$row[description]'>$row[description]</div></td>
        </tr>
        <tr>
            <td align='right'><b>Group:</b></td>
            <td><div style='white-space: nowrap; width:150px; overflow:hidden;' title='$row[category]'>$row[category]</div></td>
        </tr>
        <tr>
            <td align=center colspan=2>
            <form action='ptcadmgr.php#adform' method='POST'>
            <input type='hidden' name='searchphrase' value='$searchphrase'>
            <input type='hidden' name='ptcid' value='{$row['ptcid']}'>
            <input type='submit' name='mode' value='Delete'>
            <input type='submit' name='mode' value='Edit'>
            <input type=submit name=mode value='Copy'>
            </form>
        </td>
        </tr>
        </table>

    </td>

    <td> <table border='0' cellspacing='0' cellpadding='1'>
            <tr><td align='right'><b>Expire:</b><td><td><div style='white-space: nowrap; width:70px; overflow:hidden;' title='$row[run_quantity]'>$row[run_quantity]</div></td></tr>
            <tr><td align='right'><b>Type:</b><td><td>$row[run_type]</td></tr>
            <tr><td align='right'><b>Lock:</b><td><td>$lock</td></tr>
          </table></td>

    <td> <table border='0' cellspacing='0' cellpadding='1'>
            <tr><td align='right'><b>Clicks:</b><td><td>$row[clicks]</td></tr>
            <tr><td align='right'><b>Views:</b><td><td>$row[views]</td></tr>
            <tr><td align='right'><b>CTR:</b><td><td>$ctr</td></tr>
          </table></td>

    <td> <table border='0' cellspacing='0' cellpadding='1'>
            <tr><td align='right'><b>Value:</b><td><td>$row[value]</td></tr>
            <tr><td align='right'><b>Type:</b><td><td>$row[vtype]</td></tr>
            <tr><td align='right'><b>Timer:</b><td><td>$row[timer]s</td></tr>
          </table></td>

    <td><b>Created:</b><br>{$row['creation']}<br><b>Last shown:</b><br>$row[time]</td>
  </tr>
    <tr $bgcolor>
    <td colspan='2' align='center'>
              <form target='_clickcontest' action='clickcontest.php' method='POST'>
                Select <input type='text' name='draw' size='3' maxlength='3' value='5'> contest winners
                <input type='hidden' name='id' value='{$row['ptcid']}'>
                <input type='hidden' name='type' value='ptc'> 
                <input type='submit' value='Pick'>
              </form>
    </td>
    <td colspan='2' align='center'>
            <form action='ptcclicklog.php' method='POST' target='_ptcclicklog'>
                <input type=hidden name=ptcid value='{$row['ptcid']}'>
                <input type=submit value='View Click Log/Rollback'>
            </form>
    </td>
    </tr>";
}
echo "</table>";
$count=mysql_num_rows($getads);
if ($_POST['get'] == 'search')
{
    echo "<center><b>".$count." record(s) found</b></center><br><br>";
}
$savemode=1;
$row='';
if ($mode=='Edit' or $mode=='Copy')
{
    $savemode=2;
    if ($mode=='Copy')
    {
        $savemode=1;
    }
    $row=mysql_fetch_array(mysql_query("select * from ".mysql_prefix."ptc_ads where ptcid='$ptcid'"));
}
if (!$mode)
{
    $mode='Create New';
}
if (!$row['run_type'])
{
    $row['run_type']='clicks';
    $row['vtype']='points';
    $row['hrlock']=24;
}

$row['value']=$row['value']/100000;
if ($row['vtype']=='cash')
{
    $row['value']=$row['value']/admin_cash_factor;
}
?>
<a name="adform"></a><form action="ptcadmgr.php" method="POST" name="form">
<input type=hidden name=searchphrase value='<?php echo $searchphrase;?>'>
<input type="hidden" name="save" value="<?php echo $savemode;?>">
<?php if ($savemode==2)
{
    echo "<input type=hidden name='oldid' value='{$row['ptcid']}'>";
} 
$input_width = '450px';
?>

<table class='centered' border='0' width='730'>
<tr>
    <th colspan='2'><h2><?php echo  $mode;?> PTC Advertisement</h2></th>
</tr>
<tr>
    <td width='150'>Username:</td>
    <td><input style='width: <?php echo $input_width;?>' type="text" name="id" value="<?php echo $row['id'];?>"></td>
</tr>
<tr>
    <td>Ad Description:</td>
    <td><input style='width: <?php echo $input_width;?>' type="text" name="description" value="<?php echo safeentities($row['description']);?>"></td>
</tr>
<tr>
    <td>Ad Group:</td>
    <?php
    $adgroups_query = mysql_query("SELECT category
                                    FROM `".mysql_prefix."ptc_ads`
                                    WHERE 1
                                    GROUP BY category
                                    ORDER BY category ASC");
    $adgroups = "<option value=''>Or pick existing group:</option>\n";
    while(list($adgroup) = mysql_fetch_array($adgroups_query))
    {
        if(!empty($adgroup))
        {
            $adgroups .= "<option value='$adgroup'>$adgroup</option>\n";
        }
    }
    ?>
    <td><input style='width:270px' type="text" name="category" id='adgroup' value="<?php echo $row['category'];?>">
    <?php
        echo "<select onChange=\"document.getElementById('adgroup').value=this.value\">\n$adgroups</select>\n";
    ?>
    </td>

</tr>
<tr>
    <td>Duration Type:</td>
    <td><input type=radio class=checkbox name=run_type <?php if ($row['run_type']=='ongoing'){ echo "checked";}?> value=ongoing>Never Expire<br>
        <input type=radio class=checkbox name=run_type <?php if ($row['run_type']=='date'){ echo "checked";}?> value=date>Expire by certain date<br>
        <input type=radio class=checkbox name=run_type <?php if ($row['run_type']=='clicks'){ echo "checked";}?> value=clicks>Expire after so many clicks<br>
        <input type=radio class=checkbox name=run_type <?php if ($row['run_type']=='views'){ echo "checked";}?> value=views>Expire after so many exposures
	</td>
</tr>
<tr>
    <td>Duration:</td>
    <td><input type="text" name="run_quantity" value='<?php echo  $row['run_quantity'];?>'> (if using date to expire use the format YYYYMMDDHHMMSS)</td>
</tr>

<tr>
    <td>Cheat Link:</td>
    <td><input type='radio' class='checkbox' name='cheat_link' <?php if ($row['cheat_link']==0){echo "checked";}?> value=0>No<br>
        <input type='radio' class=checkbox name=cheat_link  <?php if ($row['cheat_link']==1){echo "checked";}?> value=1>Yes
    </td>
</tr>

<tr>
    <td>Turing Numbers:</td>
    <td><input type='radio' class='checkbox' name='turing' <?php if ($row['turing']==0){echo "checked";}?> value=0>No<br>
        <input type='radio' class='checkbox' name='turing'  <?php if ($row['turing']==1){echo "checked";}?> value=1>Yes
    </td>
</tr>

<tr>
    <td>Value:</td>
    <td>
        <table border='0' cellpadding='0' cellspacing='0'>
        <tr>
        <td rowspan='2'>
            <input style='width: 80px' type='text' name='value' value='<?php echo number_format($row['value'],5);?>'>
        </td>
        <td>
            <input type=radio class=checkbox name=vtype <?php if ($row['vtype']=='points'){echo "checked";}?> value=points>Points
        </td>
        </tr>
        <tr>
        <td>
            <input type=radio class=checkbox name=vtype <?php if ($row['vtype']=='cash'){echo "checked";}?> value=cash>Cash
        </td>
        </tr>
        </table>        
</td>
</tr>
<tr>
    <td>Timer:</td>
    <td><input style='width: 110px'  type=text name=timer value='<?php echo $row['timer'];?>'> seconds</td>
</tr>
<tr>
    <td>Hours to lock the PTC after it is clicked</td>
    <td><input style='width: 110px'  type=text name=hrlock value='<?php echo $row['hrlock'];?>'> (enter 0 to lock the ad forever)</td>
</tr>
<tr>
    <td colspan=2><hr><h2>Banner Advertisement</h2></td>
</tr>
<tr>
    <td>Banner image URL:</td>
    <td><input style='width: <?php echo $input_width;?>' type=text size=40 name=image_url value='<?php echo $row['image_url'];?>'></td>
</tr>
<tr>
    <td>Image width:</td>
    <td><input style='width: 80px' type=text name=img_width value='<?php echo  $row['img_width'];?>'> px</td>
</tr>
<tr>
    <td>Image height:</td>
    <td><input style='width: 80px' type=text name=img_height value='<?php echo  $row['img_height'];?>'> px</td>
</tr>
<tr>
    <td>Site URL:</td>
    <td valign='top'><input style='width: <?php echo $input_width;?>' type=text name=site_url size=40 value='<?php echo $row['site_url'];?>'></td>
</tr>
<tr>
    <td colspan='2'>(To place the username in the url put <b>#USERNAME#</b> where you would like it to appear)</td>
</tr>
<tr>
    <td>Image Alt Text:</td>
    <td><input style='width: <?php echo $input_width;?>' type='text' name='alt_text' size='40' value='<?php echo  safeentities($row['alt_text']);?>'></td>
</tr>
<tr>
    <td>Text Ad:</td>
    <td><input style='width: <?php echo $input_width;?>' type='text' name='text_ad' size='40' value="<?php echo  safeentities($row['text_ad']);?>"></td>
</tr>
<tr>
    <td colspan='2' align='center'>
        <input type="submit" name="add" value="Save Ad">
    </td>
</tr>
<tr>
    <td colspan='2'>
    <hr>
	<h2>HTML Advertisement</h2>
    To place the username in the HTML advertisement put <b>#USERNAME#</b> where you would like it to appear.<br>
    HTML Ads can not be tracked as easily and require the use of a javascript popup to track the clicks not to mention the use of a timer is pointless as they could still close the advertisers page and get the points anyway<br>
    <center><textarea name="html" rows='12' cols='80'><?php echo safeentities($row['html']);?></textarea></center>
</td>
</tr>

<tr>
    <td colspan=2>
    <hr>
    <h2>Country Targeting</h2>
        <?php
        //------------------------------
        // Targeting groups
        //------------------------------
        $group_found = false;
        $group_box = "<select name='group'>\n";
        if(empty($row['targeting']))
        {
            $group_found = true;
            $selected = 'selected';
        }else{
            $selected = '';
        }
        $group_box .= "<option value='everyone' $selected>Everyone</option>\n";
        $query = mysql_query("SELECT id,name,targeting FROM `".mysql_prefix."ptc_targetgroups` WHERE 1");
        while(list($group_id,$group_name,$group_targeting) = mysql_fetch_array($query))
        {
            if(!empty($row['targeting']) AND $row['targeting'] == $group_targeting)
            {
                $group_found = true;
                $selected = 'selected';
            }else{
                $selected = '';
            }
            $group_box .= "<option value='$group_id' $selected>$group_name</option>\n";
        }
        $group_box .= "</select>\n";
        ?>
        <input type='radio' <?php if($group_found)echo 'checked';?> name='targeting_type' value='group'> predetermined target group <?php echo $group_box; ?><br>
        <input type='radio' <?php if(!$group_found)echo 'checked';?> name='targeting_type' value='custom'> custom targeting (choose below)<br>
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
            //var e = document.getElementsByName('members_yes');
            //for (var i = 0; i < e.length; i++) {
            //    e[i].style.display = 'block';
            //}
                    var e = document.getElementsByTagName('div');
                    var arr = new Array();
                    for(i = 0; i < e.length; i++) {
                        att = e[i].getAttribute("name");
                        if(att == 'members_yes') {
                            e[i].style.display = 'block';
                        }
                    }
        }
        function hide_members_yes()
        {
            //var e = document.getElementsByName('members_yes');
            //for (var i = 0; i < e.length; i++) {
            //    e[i].style.display = 'none';
            //}
                    var e = document.getElementsByTagName('div');
                    var arr = new Array();
                    for(i = 0; i < e.length; i++) {
                        att = e[i].getAttribute("name");
                        if(att == 'members_yes') {
                            e[i].style.display = 'none';
                        }
                    }
        }
        function show_members_no()
        {
            //var e = document.getElementsByName('members_no');
            //for (var i = 0; i < e.length; i++) {
            //    e[i].style.display = 'block';
            //}
                    var e = document.getElementsByTagName('div');
                    var arr = new Array();
                    for(i = 0; i < e.length; i++) {
                        att = e[i].getAttribute("name");
                        if(att == 'members_no') {
                            e[i].style.display = 'block';
                        }
                    }
        }
        function hide_members_no()
        {
            //var e = document.getElementsByName('members_no');
            //for (var i = 0; i < e.length; i++) {
            //    e[i].style.display = 'none';
            //}
                    var e = document.getElementsByTagName('div');
                    var arr = new Array();
                    for(i = 0; i < e.length; i++) {
                        att = e[i].getAttribute("name");
                        if(att == 'members_no') {
                            e[i].style.display = 'none';
                        }
                    }
        }

        </script>
        <center>[<span class='fakelink' onClick="show_members_yes()">Show</span>/<span class='fakelink' onClick="hide_members_yes()">hide</span> countries with members] [<span class='fakelink' onClick="show_members_no()">Show</span>/<span class='fakelink' onClick="hide_members_no()">hide</span> countries without members] [<a href='countries.php' target='_blank'>Edit</a> country list]</center>
        <table id='targeting' border='0' cellpadding='1' cellspacing='0'>
        <tr>
        <td valign='top'>
        <?php 
        $getkeys=mysql_query("SELECT * from ".mysql_prefix."countries WHERE 1");
        $member_countries_query=mysql_query("SELECT ".mysql_prefix."countries.country from ".mysql_prefix."countries left join ".mysql_prefix."users on ".mysql_prefix."countries.country=".mysql_prefix."users.country where ".mysql_prefix."users.country is not null group by ".mysql_prefix."users.country");
        $member_countries = array();
        while(list($country) = mysql_fetch_array($member_countries_query))
        {
            array_push($member_countries, $country);
        }

        $countries = mysql_num_rows($getkeys);
        $per_column = (int)(($countries / 6) + 1);
        $line=0;
        $idx = 1;
        //-------------------------
        // Loop thru countries
        //-------------------------
        while(list($country)=mysql_fetch_row($getkeys))
        {
            if(!empty($country))
            {
                //-------------------------
                // Members from the country?
                //-------------------------
                if(in_array($country, $member_countries))
                {
                    $name = 'members_yes';
                }else{
                    $name = 'members_no';
                }
                //-------------------------
                // Old targeting?
                //-------------------------
                if(!empty($row['targeting']))
                {
                    if (strpos($row['targeting'], "|c:$country|") === false) 
                    {
                        $checked = '';
                    }else{
                        $checked = ' checked';
                    }
                }else{
                    $checked = '';
                }
                echo "\n<div name='$name'><input id=\"$country\"type='checkbox' $checked class='checkbox' name='countries[$idx]' value=\"$country\">$country<br></div>";
                $idx++;
                $line++;
                if ($line > $per_column){ echo "</td>\n<td valign='top'>"; $line=0;}
            }
        }
        if(!empty($row['targeting']))
        {
            $show_countries = 'show_members_yes()';
        }else{
            $show_countries = 'hide_members_yes()';
        }?>
        </td>
        </tr>
        </table>
        <script type='text/javascript'>
            <?php echo $show_countries;?>;
            hide_members_no();
        </script>
    </td>
</tr>
<tr>
    <td colspan=2>
    <hr>
    <h2>Account Type Targeting</h2> Do not select anything to target all account types.<br>
        <table id='type_targeting' border='0' cellpadding='1' cellspacing='0'>
        <tr>
        <td>
        <?php
        $idx=0;

        echo "\n<input type='checkbox' class='checkbox' name='accounttype[$idx]' value='free'"; 
        if (strpos($row['targeting_mtype'], "|g:free|") !== false)  {
            echo ' checked';
        }
        echo ">Free member </td>";
        $idx++;   
        
        echo "\n<td colspan='5'> <input type='checkbox' class='checkbox' name='accounttype[$idx]' value='advertiser'";
        if (strpos($row['targeting_mtype'], "|g:advertiser|") !== false)  {
            echo ' checked';
        }
        echo ">Advertiser ";
        $idx++;

 

        ?>
        </td>
        </tr>
        <tr>
        <td>
        <?php
        $getkeys=@mysql_query("SELECT description FROM ".mysql_prefix."member_types");

        $types = mysql_num_rows($getkeys);
        $per_column = (int)(($types / 6) + 1);
        $line=0;

        while(list($member_type)=@mysql_fetch_array($getkeys))
        {
            echo "\n  <input type='checkbox' class='checkbox' name='accounttype[$idx]' value='".htmlentities($member_type, ENT_QUOTES)."' ";
            if (strpos($row['targeting_mtype'], "|g:$member_type|") !== false)  {
                echo ' checked';
            }
            echo ">$member_type<br>";
            $idx++; 
            $line++;

            if ($line > $per_column){ echo "</td>\n<td valign='top'>"; $line=0;}
        }
        ?>
        </td>
        </tr>
        </table>
</tr>

    <?php 
    if ($mode!='Create New')
    { 
        echo "<tr>
        <td colspan='2'>
            <hr>
            <h2>Preview</h2>\n";
        $width='';
        $height='';
        if ($row['img_width'])
        {
            $width="width='$row[img_width]'";
        }
        if ($row['img_height'])
        {
            $height="height='$row[img_height]'";
        }
        if ($row['image_url'])
        { 
            echo "<table border=0 cellpadding=0 cellspacing=0 bgcolor='#ffffff'><tr><td><a href='". htmlentities($row['site_url']) ."' target='_blank'><img src='".htmlentities($row['image_url'])."' alt='$row[alt_text]' $width $height border=0></a></td></tr></table>";
        }
        echo "<table border=0 cellpadding=0 cellspacing=0 $width $height><tr><td><a href=$row[site_url] target=_blank>$row[text_ad]</a></td></tr><table>";
        echo "<table border=0 cellpadding=0 cellspacing=0><tr><td>$row[html]</td></tr></table>";
    }
    ?>
</td>
</tr>
</table>
<center><input type="submit" name="add" value="Save Ad"></center>
</form>

<?php footer(); ?>
<?php
include("functions.inc.php"); 
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');

$title='Auto/Manual Surf Settings';
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
<form method=post>
<center>Auto/Manual Surf #1</center>
Debit <input type=text name=sysval[autosurfdb] value='<?php echo system_value("autosurfdb");?>' size=4> point(s) from the members account for each exposure they purchase with points<br> 
Credit <input type=text name=sysval[autosurfcr] value='<?php echo system_value("autosurfcr");?>' size=4> point(s) to the members account each time they view a site<br> 
Credit <input type=text name=sysval[autosurfcash] value='<?php echo system_value('autosurfcash');?>' size=4> in cash to the members account each time they view a site<br> 
Debit <input type=text name=sysval[autosurfcdb] value='<?php echo system_value('autosurfcdb');?>' size=4> in cash from the members acount for each exposure they purchase with cash<br>
Wait <input type=text name=sysval[autosurfwait] value='<?php echo system_value("autosurfwait");?>' size=4> second(s) of view time before crediting for the view<br> 
Show turing numbers every <input type=text name=sysval[asturingwait] value='<?php echo system_value("asturingwait");?>' size=4> minute(s) (has no effect if running as manual surf)<br>
Do you wish the the next URL to load automaticly once the view time has expired? (Autosurf) <select name=sysval[autosurfauto]><option value='yes' <?php if (system_value("autosurfauto")=='yes'){ echo "selected";}?>>Yes<option value='no' <?php if (system_value("autosurfauto")=='no'){ echo "selected";}?>>No</select><br>
<hr>
<center>Auto/Manual Surf #2</center>
Debit <input type=text name=sysval[autosurfdb2] value='<?php echo system_value("autosurfdb2");?>' size=4> point(s) from the members account for each exposure they purchase with points<br>
Credit <input type=text name=sysval[autosurfcr2] value='<?php echo system_value("autosurfcr2");?>' size=4> point(s) to the members account each time they view a site<br>
Credit <input type=text name=sysval[autosurfcash2] value='<?php echo system_value('autosurfcash2');?>' size=4> in cash to the members account each time they view a site<br>
Debit <input type=text name=sysval[autosurfcdb2] value='<?php echo system_value('autosurfcdb2');?>' size=4> in cash from the members acount for each exposure they purchase with cash<br>
Wait <input type=text name=sysval[autosurfwait2] value='<?php echo system_value("autosurfwait2");?>' size=4> second(s) of view time before crediting for the view<br>
Show turing numbers every <input type=text name=sysval[asturingwait2] value='<?php echo system_value("asturingwait2");?>' size=4> minute(s) (has no effect if running as manual surf)<br>
Do you wish the the next URL to load automaticly once the view time has expired? (Autosurf) <select name=sysval[autosurfauto2]><option value='yes' <?php if (system_value("autosurfauto2")=='yes'){ echo "selected";}?>>Yes<option value='no' <?php if (system_value("autosurfauto2")=='no'){ echo "selected";}?>>No</select><br>
<hr>
<center>Auto/Manual Surf #3</center>
Debit <input type=text name=sysval[autosurfdb3] value='<?php echo system_value("autosurfdb3");?>' size=4> point(s) from the members account for each exposure they purchase with points<br>
Credit <input type=text name=sysval[autosurfcr3] value='<?php echo system_value("autosurfcr3");?>' size=4> point(s) to the members account each time they view a site<br>
Credit <input type=text name=sysval[autosurfcash3] value='<?php echo system_value('autosurfcash3');?>' size=4> in cash to the members account each time they view a site<br>
Debit <input type=text name=sysval[autosurfcdb3] value='<?php echo system_value('autosurfcdb3');?>' size=4> in cash from the members acount for each exposure they purchase with cash<br>
Wait <input type=text name=sysval[autosurfwait3] value='<?php echo system_value("autosurfwait3");?>' size=4> second(s) of view time before crediting for the view<br>
Show turing numbers every <input type=text name=sysval[asturingwait3] value='<?php echo system_value("asturingwait3");?>' size=4> minute(s) (has no effect if running as manual surf)<br>
Do you wish the the next URL to load automaticly once the view time has expired? (Autosurf) <select name=sysval[autosurfauto3]><option value='yes' <?php if (system_value("autosurfauto3")=='yes'){ echo "selected";}?>>Yes<option value='no' <?php if (system_value("autosurfauto3")=='no'){ echo "selected";}?>>No</select><br>
<hr>
<center>Auto/Manual Surf #4</center>
Debit <input type=text name=sysval[autosurfdb4] value='<?php echo system_value("autosurfdb4");?>' size=4> point(s) from the members account for each exposure they purchase with points<br>
Credit <input type=text name=sysval[autosurfcr4] value='<?php echo system_value("autosurfcr4");?>' size=4> point(s) to the members account each time they view a site<br>
Credit <input type=text name=sysval[autosurfcash4] value='<?php echo system_value('autosurfcash4');?>' size=4> in cash to the members account each time they view a site<br>
Debit <input type=text name=sysval[autosurfcdb4] value='<?php echo system_value('autosurfcdb4');?>' size=4> in cash from the members acount for each exposure they purchase with cash<br>
Wait <input type=text name=sysval[autosurfwait4] value='<?php echo system_value("autosurfwait4");?>' size=4> second(s) of view time before crediting for the view<br>
Show turing numbers every <input type=text name=sysval[asturingwait4] value='<?php echo system_value("asturingwait4");?>' size=4> minute(s) (has no effect if running as manual surf)<br>
Do you wish the the next URL to load automaticly once the view time has expired? (Autosurf) <select name=sysval[autosurfauto4]><option value='yes' <?php if (system_value("autosurfauto4")=='yes'){ echo "selected";}?>>Yes<option value='no' <?php if (system_value("autosurfauto4")=='no'){ echo "selected";}?>>No</select><br>
<center><hr>Global Auto/Manual Surf Settings</center>
<br>
Turing number size (height &amp; width): 
<input type=text size=3 name=sysval[asturingsize] value=<?php echo system_value('asturingsize');?>><br>
Automatically tag URLs as approved when they are submitted by a member? <select name=sysval[approveautosurf]><option value='0' <?php if (system_value("approveautosurf")!='1'){ echo "selected";}?>>No<option value='1' <?php if (system_value("approveautosurf")=='1'){ echo 'selected';}?>>Yes</select><br> 
Allow members to see the same advertiser's sites more then once a day? <select name=sysval[no_unique_limit]><option value='no' <?php if (system_value("no_unique_limit")=='no'){ echo "selected";}?>>No<option value='yes' <?php if (system_value("no_unique_limit")=='yes'){ echo "selected";}?>>Yes</select><br> 
<br><input type=submit value='Save Settings'></form>  
<?php
footer(); 
?>
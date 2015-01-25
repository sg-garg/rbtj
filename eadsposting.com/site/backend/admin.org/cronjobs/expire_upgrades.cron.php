<?php
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');

$cronjobs[]=array('classname'=>'cc_admin_expire_upgrades');

class cc_admin_expire_upgrades
{
    var $class_name='cc_admin_expire_upgrades';
    var $minutes=5;
    
    function cronjob()
    {
        $expire_query = mysql_query("SELECT * FROM `".mysql_prefix."users` WHERE upgrade_expires <= NOW() AND upgrade_expires != '0000-00-00 00:00:00' AND upgrade_expires != '00000000000000' AND account_type != 'suspended' AND account_type != 'canceled'");
        while($erow = mysql_fetch_array($expire_query))
        {
            echo date("H:i:s") . " ". $erow['username'] ."'s upgrade '". $erow['account_type'] ."' expired<br>\n";
            cronjob_query("update " . mysql_prefix . "users set account_type = '', upgrade_expires='0000-00-00 00:00:00', free_refs = '0', commission_amount = '0' WHERE username='". $erow['username'] ."' limit 1");
            $note = gmdate(timeformat) ." - Upgrade '".mysql_real_escape_string($erow['account_type'])."' expired";
            append_note("$note", "$erow[username]");
        }
    }
}

return;
?>

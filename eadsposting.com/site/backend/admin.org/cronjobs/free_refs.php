<?
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');

$cronjobs[]=array('classname'=>'cc_admin_free_refs');

class cc_admin_free_refs {

var $class_name='cc_admin_free_refs';
var $minutes=5;

function cronjob(){

if (!@mysql_num_rows(cronjob_query('select username from '.mysql_prefix.'free_refs limit 1'))) { 
    $result=cronjob_query('select username,free_refs from '.mysql_prefix.'users where free_refs>0 AND account_type != "canceled" AND account_type != "suspended"'); 
    while ($row=@mysql_fetch_array($result))
        for ($i=1;$i<=$row['free_refs'];$i++) 
        cronjob_query('insert into '.mysql_prefix.'free_refs set username="'.$row[username].'"'); 

} 
}
}
cronjob_query('optimize table '.mysql_prefix.'free_refs');

if (@mysql_num_rows(cronjob_query('describe '.mysql_prefix.'free_refs'))<=0){
    cronjob_query('drop table '.mysql_prefix.'free_refs');
    @mysql_query("CREATE TABLE ".mysql_prefix."free_refs (
    username char(16) not null,
    key username(username)
    ) TYPE=MyISAM");
}


return;
?>

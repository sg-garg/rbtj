<? 
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');

$noheader=1;
admin_login();
if (!ini_get('safe_mode'))
set_time_limit(0);
$cur_time=date("Y-m-d H:i");
header("Content-disposition: filename=$mysql_database.emails.dat");
                                        header("Content-type: application/octetstream");
                                        header("Pragma: no-cache");
                                        header("Expires: 0");
$result=@mysql_query("select email from ".mysql_prefix."users where account_type!='canceled'");
while($row=@mysql_fetch_row($result)){
echo $row[0]."\r\n";
}

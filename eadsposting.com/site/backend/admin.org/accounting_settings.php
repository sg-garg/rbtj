<? 
include("functions.inc.php");
if (md5(domain.'89yoliogp9suiogbnkhjo94u984yu7895t4yi5goi34h5908y34958yihkjfhskdfh98y495uhkjehrkh943y85ihuktrnkwjhfo84987598347508hf,jshdkfhkjh4938y598345hkjhdkfjh983459yijhkf89649786593745jkhkds9f878979345').md5(domain.'8y4ukhih8gfkjnkjnbi88yrti98hykjbdk09ulfj;okdpf0ua098dyfkabkdfnkihioy80489yhiyugu6t8T8yt*O&T&*^TGKJUTO*&RT&UFUKYTG*(YU{))I("POJKJHJ<HGUK&T*I&TTURDFJHo;iy98676467587yluhjkugy8it8igjklgil8y9oli')!=serialnumber)
exit('Invalid Serial Number');
$title='Accounting Settings';
admin_login();

if ($_POST['sysval']){
@mysql_query("update ".mysql_prefix."users set rebuild_stats_cache=1");
postsysval();
}?>
<form method=post><table border=0>
<?
edit_sysval('Miscellaneous Transaction Descriptions');
edit_sysval('MassPay/QuickPay','paydesc');
edit_sysval('Paid Mail Earnings','pmdescription');
edit_sysval('Paid To Click Earnings','ptcdescription');
edit_sysval('Paid Start Page Earnings','pspdescription');
edit_sysval('Paid To Chat Earnings','pchatdescription');
edit_sysval('Auto/Manual Surf Earnings','ascdescription');
edit_sysval('Spend on Auto/Manual Surf ad credits','asddescription');
edit_sysval('Points to Cash Convertion','convertpoints');
edit_sysval('Minimum Payout');
edit_sysval('Members reach cash payout at','payout');
edit_sysval('PayPal Settings');
edit_sysval('PayPal ID','paypalid');
?>
<tr><td align=right>Only post commissions from IPN notifications:</td><td> <select name=sysval[ipn]><option value=no <? if (system_value("ipn")=='no') echo 'selected';?>>No<option value=yes <? if (system_value("ipn")=='yes') echo 'selected';?>>Yes</selected></td></tr>
<?
edit_sysval('Convert every 1 unit of cash to ','ipntopoints',' points when funding the paying members account<br> (set to 0 to disable conversion and fund the account in cash)');
edit_sysval('You can have each CashCrusader site post commissions to its own accounting database, or have it post to one central CashCrusader database. This is handy if you have many CashCrusader sites for different services and want to post the commissions to one central CashCrusader site.');
edit_sysval('Database Name','accounting_db');
edit_sysval('Accounting Table Name','accounting_tbl');
edit_sysval('You can have both points and cash signup bonuses active at the same time.<br>Place 0 in the value to disable signup bonuses');
edit_sysval('Cash Signup Bonus','cashsignbonus');
edit_sysval('Points Signup Bonus','pointsignbonus');
edit_sysval('Description','sbdescription');
edit_sysval('You can have both points and cash referral bonuses active at the same time.<br>Place 0 in the value to disable referral bonuses');
edit_sysval('Cash Referral Bonus','cashreferbonus');
edit_sysval('Points Referral Bonus','pointreferbonus');
edit_sysval('Description','rbdescription');
edit_sysval('Set commission rates for sales transactions posted manualy or using IPN<br>Example: if your site pays 15% for direct referral sales, 10% for second level referral sales and 5% for third level referral sales you would enter: 15,10,5');
edit_sysval('Sales Commissions','sales_comm');
edit_sysval('Description','sales_desc');
edit_sysval('Set the percentage amount you would like to credit uplines when their downline clicks on an ad or signs up for a site<br>Example: if your site pays 15% for direct referral clicks, 10% for second level referral clicks and 5% for third level referral clicks you would enter: 15,10,5');
edit_sysval('Cash clicks','cashclicks');
edit_sysval('Point clicks','pointclicks');
edit_sysval('Description','dldescription');
edit_sysval('Paid to Chat Settings<br>Set amounts to credit active chatters for ads seen');
edit_sysval('Cash','pchatcash');
edit_sysval('Points','pchatpoints');
edit_sysval('Account Activity Settings<br>To prevent accounts being considered inactive put 0');
edit_sysval('When crediting uplines do not credit accounts that have not logged on in ','nocreditdays',' days');
edit_sysval('Only credit upline if they are ','nocreditclicks','% as active as the downline member that clicked');
?>
</table> 
<input type=submit value='Save Changes'></form>
<? footer();

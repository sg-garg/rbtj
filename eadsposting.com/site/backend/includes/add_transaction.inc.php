<?php
if (!defined('version')){exit;} 

$a = get_args('No Description|0|points|notunique|no',$args);
$a[1] = $a[1] * 100000;
if ($a[2]=='cash')
{
    $a[1]=$a[1]*100;
}

$a[1] = number_format($a[1],0,'',''); // Circumvent PHP5 bug http://bugs.php.net/bug.php?id=43053
$a[0] = mysql_real_escape_string($a[0]);

if ($a[3]=='unique')
{
    $addtran = @mysql_query('UPDATE ' . mysql_prefix . 'accounting SET amount=amount+'.$a[1].' WHERE type="'.$a[2].'" AND username = "'.$_SESSION['username'].'" AND description="'.$a[0].'" LIMIT 1'); 
}

if (!@mysql_affected_rows($addtran) && $a[3]=='unique')
{
    $addtran=@mysql_query('INSERT INTO ' . mysql_prefix . 'accounting set transid="'.maketransid($_SESSION['username']).'",username = "'.$_SESSION['username'].'",unixtime=0,description="'.$a[0].'",amount='.$a[1].',type="'.$a[2].'"');
}else
if (!@mysql_affected_rows($addtran))
{
    @mysql_query('INSERT INTO ' . mysql_prefix . 'accounting SET transid="'.maketransid($_SESSION['username']).'",username = "'.$_SESSION['username'].'",unixtime='.unixtime.',description="'.$a[0].'",amount='.$a[1].',type="'.$a[2].'"');
}

if (!empty($a[4]) AND $a[4] != 'no')
{
    creditulclicks($_SESSION['username'],$a[1],$a[2]); 
}

return 1; 
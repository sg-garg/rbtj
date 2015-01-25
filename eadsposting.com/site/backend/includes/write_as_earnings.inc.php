<?php
if (!defined('version')){exit;}

$autosurfcash = @constant('autosurfcash'.$_GET['ID']) * 100000 * $_SESSION['ascount'] * admin_cash_factor;

$autosurfpcr = @constant('autosurfcr'.$_GET['ID']) * 100000 * $_SESSION['ascount'];
$_SESSION['ascount'] = 0;

@mysql_query('update '.mysql_prefix.'last_login set as_start_ad="'. $_SESSION['as_start_ad'].'" where username="'.$_SESSION['username'].'"');

if($autosurfcash)
{
    if (!$_SESSION['ascashtransid'] or !$_SESSION['ascash'])
        list($_SESSION[ascashtransid], $ascash)=@mysql_fetch_array(@mysql_query('select transid,amount from ' . mysql_prefix . 'accounting WHERE type="cash" and username = "' . $_SESSION['username'] . '" and description="' . ascdescription . '" limit 1'));
    
    if ($_SESSION[ascashtransid] and $_SESSION[ascash])
    {
        $_SESSION['ascash'] = $_SESSION['ascash'] + $autosurfcash;
	$_SESSION['ascash'] = number_format($_SESSION['ascash'],0,'',''); // Circumvent PHP5 bug http://bugs.php.net/bug.php?id=43053
         @mysql_query ('update ' . mysql_prefix . 'accounting set amount=' . $_SESSION[ascash] . ' where transid="' . $_SESSION[ascashtransid] . '"');
    }
    else
    {
        $autosurfcash = number_format($autosurfcash,0,'',''); // Circumvent PHP5 bug http://bugs.php.net/bug.php?id=43053
        @mysql_query ('UPDATE ' . mysql_prefix . 'accounting SET amount=amount+' . $autosurfcash . ' WHERE type="cash" and username = "' . $_SESSION['username'] . '" and description="' . ascdescription . '" limit 1');
        if (!mysql_affected_rows())
		{
            @mysql_query ('INSERT INTO ' . mysql_prefix . 'accounting set transid="' . maketransid($_SESSION['username']) . '",username = "' . $_SESSION['username'] . '",unixtime=0,description="' . ascdescription . '",amount=' . $autosurfcash . ',type="cash"');
		}
    }

    if ($autosurfcash > 0)
        creditulclicks($_SESSION['username'], $autosurfcash, 'cash');

}

if($autosurfpcr)
{
    if (!$_SESSION[aspointstransid] or !$_SESSION[aspoints])
        list($_SESSION[aspointstransid], $_SESSION[aspoints])=@mysql_fetch_array(

    @mysql_query('select transid,amount from ' . mysql_prefix . 'accounting WHERE type="points" and username = "' . $_SESSION['username'] . '" and description="' . ascdescription . '" limit 1'));

    if ($_SESSION[aspointstransid] and $_SESSION[aspoints])
    {
        $_SESSION[aspoints] = $_SESSION[aspoints] + $autosurfpcr;
	    $_SESSION[aspoints] = number_format($_SESSION[aspoints],0,'',''); // Circumvent PHP5 bug http://bugs.php.net/bug.php?id=43053
        @mysql_query ('update ' . mysql_prefix . 'accounting set amount=' . $_SESSION[aspoints] . ' where transid="' . $_SESSION[aspointstransid] . '"');
    }
    else
    {
	    $autosurfpcr = number_format($autosurfpcr,0,'',''); // Circumvent PHP5 bug http://bugs.php.net/bug.php?id=43053
        @mysql_query ('UPDATE ' . mysql_prefix . 'accounting SET amount=amount+' . $autosurfpcr . ' WHERE type="points" and username = "' . $_SESSION['username'] . '" and description="' . ascdescription . '" limit 1');
        if (!mysql_affected_rows())
            @mysql_query ('INSERT INTO ' . mysql_prefix . 'accounting set transid="' . maketransid($_SESSION['username']) . '",username = "' . $_SESSION['username'] . '",unixtime=0,description="' . ascdescription . '",amount=' . $autosurfpcr . ',type="points"');
    }
    if ($autosurfpcr > 0)
        creditulclicks($_SESSION['username'], $autosurfpcr, 'points');
}

return 1;
?>

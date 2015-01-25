<?php
if (!defined('version')){exit;}

if ($keys)
{            
    $keys='where keyword="' . $keys . '"';
}
    
$getkeys = @mysql_query('select * from ' . mysql_prefix . 'keywords ' . $keys. ' order by keyword');

while ($row=@mysql_fetch_array($getkeys))
{        
    echo $f . $row['keyword'] . $m . $row['keycount'] . $e;
}

return 1;
?>

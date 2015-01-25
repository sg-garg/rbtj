<?
if (!defined('version')){
exit;}
        $ralphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
	$alphabet = $ralphabet.$ralphabet; 

	for( $i=0; $i < strlen ( mysqldate ); $i++ )
	{
	    $cur_pswd_ltr = substr(mysqldate,$i,1);
	    $pos_alpha_ary[] = substr(strstr($alphabet,$cur_pswd_ltr),0,strlen($ralphabet));
	}

	$i  = 0;
	$n  = 0;
	$nn = strlen ( mysqldate );
	$c  = strlen ( $strtoencrypt );

	$encrypted_string = '';

	while ( $i < $c )
	{
	    $encrypted_string .=  substr ( $pos_alpha_ary[$n],
				  strpos ( $ralphabet, substr($strtoencrypt, $i, 1 ) ),
				  1 );
 
	    $n++;

	    if ( $n == $nn )
	    {
		$n = 0;
	    }
	    $i++;
	}

	return substr(mysqldate.$encrypted_string.mt_rand(10000000000000000,99999999999999999),0,32);

?> 


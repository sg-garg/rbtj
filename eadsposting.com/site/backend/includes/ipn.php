<?php
if (!defined('version'))
   exit;

        header ("Status: 200 OK");

        $postipn='cmd=_notify-validate';

        foreach ($_POST as $ipnkey => $ipnval)
            {

            if (!preg_match(
                "/^[_0-9a-z-]{1,30}$/i", $ipnkey) || !strcasecmp($ipnkey, 'cmd'))
                {
                unset ($ipnkey);

                unset ($ipnval);
                }

            if (@$ipnkey != '')
                {
                @$_PAYPAL[$ipnkey]=$ipnval;

                unset ($_POST);
                $postipn.='&' .@$ipnkey . '=' . urlencode(@$ipnval);
                }
            }

        $error=0;
        @set_time_limit (60);
        $domain="www.paypal.com";
        $socket=@fsockopen($domain, 80, $errno, $errstr, 30);
        $header="POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header.="User-Agent: PHP/" . phpversion(). "\r\n";
        $header.="Referer: " . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] .@$_SERVER['QUERY_STRING'] . "\r\n";
        $header.="Server: " . $_SERVER['SERVER_SOFTWARE'] . "\r\n";
        $header.="Host: " . $domain . ":80\r\n";
        $header.="Content-Type: application/x-www-form-urlencoded\r\n";
        $header.="Content-Length: " . strlen($postipn). "\r\n";
        $header.="Accept: */*\r\n\r\n";

        if (!$socket && !$error)
            {
            $ipnget
                =file('http://' . $domain . ':80/cgi-bin/webscr?' . $postipn);

            $response=$ipnget[0];
            }
        else
            {
            @fputs($socket, $header . $postipn . "\r\n\r\n");

            while (!feof($socket))
                {
                $response=fgets($socket, 1024);
                }
            }

        $response=trim($response);

        if (!strcmp($response, "VERIFIED"))
            {
            $mc_gross=$_PAYPAL['mc_gross'] * 100000 * admin_cash_factor;

            if ($_PAYPAL['payment_status'] == "Completed")
                {
                $comm=sales_comm;

                $desc=sales_desc;

                    @mysql_connect($mysql_hostname, $mysql_user,
                                   $mysql_password);

                @mysql_select_db ($mysql_database);
                if (ipn=='no' || ipntopoints>0){
                $posttype='cash';
                $postamount=$mc_gross;
                if (ipntopoints>0){
                $posttype='points';
                $postamount=$mc_gross*ipntopoints/admin_cash_factor;
                }
				$postamount = number_format($postamount,0,'',''); // Circumvent PHP5 bug http://bugs.php.net/bug.php?id=43053

                
                @mysql_query ('insert into ' . mysql_prefix . 'accounting set transid="'.maketransid($_PAYPAL['custom']).'",username="'.$_PAYPAL[custom].'",description="PayPal '.$_PAYPAL[txn_id].'",unixtime="0",type="'.$posttype.'",amount="'.$postamount.'"');
                } else {
					$commission=explode(',',$comm);
					$thissale=$mc_gross/100*$commission[0];
					array_shift($commission);
					$comm=join(',',$commission);
					if ($thissale)
					{
						$thissale = number_format($thissale,0,'',''); // Circumvent PHP5 bug http://bugs.php.net/bug.php?id=43053
						@mysql_query('insert into ' . mysql_prefix . 'accounting set transid="'.maketransid($_PAYPAL['custom']).'",username="'.$_PAYPAL[custom].'",description="'.$desc.'",unixtime="'.unixtime.'",type="cash",amount="'.$thissale.'"');
					}
                }
                if ($comm)
                    {
                    creditul($_PAYPAL['custom'], $mc_gross, "cash", $comm, $desc);
                    }
                }
            }

        @fclose ($socket);
        exit;
?>

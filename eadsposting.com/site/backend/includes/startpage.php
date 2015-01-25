<?
if (!defined('version'))
   exit;
        $secondpart=trim(substr($_GET[SP], 8, strlen($_GET[SP]) - 8));

        if ($_COOKIE[startpagetime]<unixtime && ($_GET[SP] == substr(
            md5($secondpart . $mysql_password), 0, 8). $secondpart or $_GET[SP]
                == substr(md5($secondpart . key), 0,
                          8). $secondpart))
            {
            $ptcad=@mysql_fetch_array(
                @mysql_query("select * from " . mysql_prefix . "ptc_ads where description='#PAID-START-PAGE#' limit 1"));

            if (!$ptcad[site_url])
                {
                include (pages_dir .pages. 'invalid_paid_mail.php');

                exit;
                }
   $ptcad[site_url]   
                        =str_replace("#USERNAME#", $secondpart, $ptcad[site_url]);   

            setcookie('startpageurl',
                      $ptcad[site_url], unixtime + ($ptcad[hrlock] * 60 * 60), '/');
            setcookie('startpagetime', unixtime + ($ptcad[hrlock] * 60 * 60), unixtime + ($ptcad[hrlock] * 60 * 60),'/');
           $clickcheck=@mysql_fetch_array(
                @mysql_query("select * from " . mysql_prefix . "paid_clicks where id=$ptcad[ptcid] and username='$secondpart' limit 1"));

            if ($clickcheck[username]!=$secondpart)
                {

                @mysql_query ("insert into " . mysql_prefix . "paid_clicks set username='$secondpart',id='$ptcad[ptcid]',value='$ptcad[value]',vtype='$ptcad[vtype]'");

                if ($ptcad[value])
                    {
                    $update
                        =@mysql_query("UPDATE " . mysql_prefix . "accounting SET amount=amount+$ptcad[value] WHERE type='$ptcad[vtype]' and username = '$secondpart' and description='".pspdescription."' limit 1");

                    if (!mysql_affected_rows())
                        $update
                            =@mysql_query('INSERT INTO ' . mysql_prefix . 'accounting set transid="'.maketransid($secondpart).'",username = "'.$secondpart.'",unixtime=0,description="'.pspdescription.'",amount='.$ptcad[value].',type="'.$ptcad[vtype].'"');

                    if ($ptcad[value] > 0)
                        creditulclicks(
                            $secondpart, $ptcad[value], $ptcad[vtype]);
                    }
                }
            }

        if (!$ptcad[site_url])
            $ptcad[site_url]=$_COOKIE[startpageurl];

        header ('Location: ' . $ptcad[site_url]);
        exit;
?>

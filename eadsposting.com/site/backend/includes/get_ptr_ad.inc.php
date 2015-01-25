<?
if (!defined('version')){
exit;}
global $_GET,$_SERVER,
            $_SESSION;
        $pasteinst=trim($pasteinst);

        if (!$_GET[startpos])
            {
            $_GET[startpos]=0;
            }

        if (!$_SESSION[ptrlist])
            {
            $getclicks
                =@mysql_query('select * from ' . mysql_prefix . 'review_ads where category like "'.$get.'" and ((run_quantity>reviews  and run_type="2") or (run_quantity>views and run_type="3") or run_type="0" or (run_type="1" and run_quantity>='.mysqldate.'))');

            while ($setclicks=@mysql_fetch_array($getclicks))
                {
                list ($userchk)=@mysql_fetch_row(
                    @mysql_query('select username from ' . mysql_prefix . 'paid_reviews_'.$setclicks[id].' where username="'.$_SESSION[username].'"'));

                if ($userchk == $_SESSION[username])
                    {
                    $_SESSION[ptrlist]['ID'.$setclicks[id]]=1;
                    }
                }

            }
			
if ($_SESSION[ptrlist]){
	     foreach($_SESSION[ptrlist] as $keyvalue => $value) 
            {
            $keyvalue=substr($keyvalue,2,strlen($keyvalue)-2);
            $clicklist=$clicklist . 'id!="'.$keyvalue.'" and ';

          } } 
        $getrow
            =@mysql_query('select * from ' . mysql_prefix . 'review_ads where category like "'.$get.'" and '.$clicklist.' ((run_quantity>reviews  and run_type="2") or (run_quantity>views and run_type="3") or run_type="0" or (run_type="1" and run_quantity>='.mysqldate.')) order by vtype,value desc limit '.$_GET[startpos].',5');
        $backpos=$_GET[startpos];

        while ($row=@mysql_fetch_array($getrow))
            {
            $_GET[startpos]++;


                if ($row[image_url])
                    {
                    $width='';

                    $height='';

                    if ($row[img_width])
                        {
                        $width="width=$row[img_width]";
                        }

                    if ($row[img_height])
                        {
                        $height="height=$row[img_height]";
                        }

                    $row[site_url]
                        =str_replace("#USERNAME#", $_SESSION[username], $row[site_url]);
                    echo
                        '<table border=0 cellpadding=0 cellspacing=0 bgcolor=ffffff><form method=post><tr><td><a href=' . runner_url . '?REDIRECT=' . rawurlencode(
                        $row[site_url]). '&hash='.md5($row['site_url'].key).' target=_ptr><img src=' . runner_url . '?REDIRECT=' . rawurlencode($row[image_url]). '&hash='.md5($row['image_url'].key).' alt="' . $row[alt_text] . '" ' . $width . ' ' . $height . ' border=0></a></td></tr><tr><td align=center><input type=hidden value=' . $row[id] . ' name=ptrconfirm><input type=hidden name=pasteinstcode value='.md5($pasteinst).strlen($pasteinst).'><textarea name=review cols=30 rows=8>' . $pasteinst . '</textarea><br>
						'. $rate. '<input type=radio class=checkbox  name=rating value=1>1 <input type=radio class=checkbox  name=rating value=2>2 <input type=radio class=checkbox  name=rating value=3 checked>3 <input type=radio class=checkbox  name=rating value=4>4 <input type=radio class=checkbox  name=rating value=5>5<br>
						<br><input type=submit value="' . $submitbutton . '"></td></tr></form></table>';
                    }
                else
                    {
                    $row[html]=str_replace("#USERNAME#", $_SESSION[username], $row[html]);

                    echo "<table border=0 cellpadding=0 cellspacing=0><tr><td>$row[html]</td></tr><form method=post><tr><td align=center><input type=hidden name=pasteinstcode value=".md5($pasteinst).strlen($pasteinst)."><input type=hidden value=$row[id] name=ptrconfirm><textarea name=review rows=8 cols=30>$pasteinst</textarea><br>$rate<input type=radio class=checkbox  name=rating value=1>1 <input type=radio class=checkbox  name=rating value=2>2 <input type=radio class=checkbox  name=rating value=3 checked>3 <input type=radio class=checkbox  name=rating value=4>4 <input type=radio class=checkbox  name=rating value=5>5<br><input type=submit value='$submitbutton'></td></tr></form></table>";
                    }
										

                $mdgroup="#" . substr(md5($row[category]), 0, 8). "#";
                $typemsg=$points;
                $amount=$row[value] / 100000;

                if ($row[vtype] == 'cash')
                    {
                    $typemsg=$cash;

                    $amount=$amount / $factor;
                    }

                echo $message . " " . $amount . " " . $typemsg . "<br><br>";
                @mysql_query ("update " . mysql_prefix . "review_ads set views=views+1 where id='$row[id]'");
                }

        if (!$typemsg)
            {
            echo $notfound . "<br>";
            }
        else
            {
            echo "<br>";

            $page=$backpos / 5 + 1;

            if ($backpos - 5 >= 0)
                {
                $backpos=$backpos - 5;

                echo '<a href='.$_SERVER[PHP_SELF].'?startpos='.$backpos.'>'.$back.'</a>';
                }

            if ($_GET[startpos] / 5 == intval($_GET[startpos] / 5))
                {
                echo ' <a href='.$_SERVER[PHP_SELF].'?startpos='.$_GET[startpos].'>'.$forward.'</a>';
                }

            echo "<br><br><b>$page</b><br> ";
            }
        
return 1;
?>


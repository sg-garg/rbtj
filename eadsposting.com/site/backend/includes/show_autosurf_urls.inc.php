<?
if (!defined('version')){
exit;}

    global $aserror;
    $ID=intval($ID);
    if ($ID>4 || $ID<2)
    $ID='';


        if ($aserror){
        echo '<tr><td align=center colspan=5>'.$E.'</td></tr>'; 
}
        $results
            =@mysql_query('select * from ' . mysql_prefix . 'autosurf'.$ID.' where username="' . $_SESSION[username] . '" order by url');
$cashex=show_autosurf_cash_exposure_count($ID,1);
$pointex=show_autosurf_point_exposure_count($ID,1);
        while ($row=@mysql_fetch_array($results))
            {
            if ($row[runad] > 1)
                {
                $row[time]=$row[runad];
                }

            if ($row[time] == 0)
                {
                $row[time]='00000000000000';
                }

            $shorturl=substr($row[url], 0, 30);

            if ($shorturl != $row[url])
                {
                $shorturl=$shorturl . '...';
                }

            if ($row[active])
                {
                $activelinktext=$deactivate;

                $activelink='dactautosurf';
                $row[active]=$active;
                }
            else
                {
                $activelinktext=$activate;

                $activelink='actautosurf';
                $row[active]=$inactive;
                }

            if ($row[approved])
                {
                $row[approved]=$approved;
                }
            else
                {
                $row[approved]=$noapproved;
                }

            $activelink=$_SERVER[PHP_SELF]. '?' . $activelink . '=' . rawurlencode($row[url]).'&autosurfid='.$ID;
            echo
                $L . '<a href=' . runner_url . '?REDIRECT=' . rawurlencode(
                $row[url]). '&hash='.md5($row['url'].key).' target=_autosurfurl>' . $shorturl . '</a>' . $M . '<nobr><p align=right>' . $row[hits] . '/' . $row[quantity] . '</p></nobr>' . $M . mytimeread(
                $row[time]). $M . '<center>' . $row[active] . $row[approved] . '</center>' . $M . '<center><a href=' . $activelink . '>' . $activelinktext . '</a><a href=' . $_SERVER[PHP_SELF]. '?delautosurf=' . rawurlencode($row[url]) .'&autosurfid='.$ID. '>' . $D . '</a>';
if ($pointex || $cashex){
echo $B . '<table border=0 cellpadding=0 cellspacing=0><form method=post><tr><td align=center><input type=hidden name=autosurfid value='.$ID.'><input type=hidden name=asurl value=' .rawurlencode($row[url]) . '><input type=text size=4 value=0 name=asorder><br><select name=ashow>';
if ($pointex){
echo '<option value=points>' . $P;}
if ($cashex){
echo '<option value=cash>' . $C ;}
echo '</select><br><input type=submit value="' . safeentities($S) . '"></td></tr></form></table>';}
echo $R;
            }


return 1;
?>

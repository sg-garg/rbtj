<?
if (!defined('version')){
exit;}
    global  $checkstop, $auto;

    if (!$_GET['AS'])
    {
        return;
    }
    if ($_GET[AC])
    {
        $color='bordercolor='.$countedcolor;
    }
    else {$color='bordercolor='.$notcountedcolor;}
    if (!$checkstop)
    {
        $_GET[LI]=rawurlencode($_GET[LI]);

        $modelink='<a href=' . runner_url . '?AS=' . $_GET['AS'] . '&ID='.$_GET['ID'].'&MO=start'.add_session().' target=mainframe>' . $start . '</a>';

        if (!$_GET[MO] or $_GET[MO] == 'start')
        {
            $modelink='<a href=' . runner_url . '?AS=' . $_GET['AS'] .'&ID='.$_GET['ID'].'&MO=stop&LI=' . $_GET[LI] .add_session().' target=mainframe>' . $stop . '</a>';
        }

        if ($auto == 'no')
        {
            $modelink='';
        }

        echo '<table border=0 cellspacing=1 cellpadding=0><tr><form name="counter"><td><table border=1 cellspacing=0 cellpadding=0 '.$color.'><tr><td align=center><input type="text" size="2" name="timer" value=' . $_GET[TI] . '></td></tr></table></td><td align=center>&nbsp;' . $modelink . ' <a href=' . runner_url . '?AS=' . $_GET['AS'] . '&ID='.$_GET['ID'].add_session().' target=mainframe>' . $next . '</a> <a href=' . runner_url . '?AS=' . $_GET['AS'] .  '&ID='.$_GET['ID'].'&MO=stop&LI=' . $_GET[LI] . '&NW=1'.add_session().' target=mainframe>' . $nw . '</a></td></tr><tr><td colspan=5 align=center><a href=' . runner_url . '?AS=' . $_GET['AS'] . '&ID='.$_GET['ID']. '&EX=1'.add_session().' target=_top>' . $logoff . '</a> <a href=' . runner_url . '?AS=' . $_GET['AS'] .  '&ID='.$_GET['ID'].'&LI='.$_GET[LI].'&AB=1'.add_session().' target=mainframe>' . $abuse . '</a></td></tr></form></table>';
    }
    else
    {
        $_SESSION['img1']['AS']=rand(10, 99);
        $_SESSION['img2']['AS']=rand(10, 99);
        $pick[$_SESSION['img1']['AS'] . $_SESSION['img2']['AS']]=$_SESSION['img1']['AS'] . $_SESSION['img2']['AS'];

        while ($pickcount < 4)
        {
            $nextpick=rand(10, 99). rand(10, 99);
            if (!$pick[$nextpick])
            {
                $pick[$nextpick]=$nextpick;
                $pickcount++;
            }
        }

        echo '<table border=0 cellpadding=1 cellspacing=0><tr><form name="counter"><td><table border=1 cellspacing=0 cellpadding=0 '.$color.'><tr><td align=center><input type="text" size="2" name="timer" value=' . $_GET[TI] . '></tr></td></table></td><td align=center>' . $number . '</td><td align=center><img height='.asturingsize.' width='.asturingsize.' src=' . runner_url . '?TN=1&KE=AS'.add_session().'><img height='.asturingsize.' width='.asturingsize.' src=' . runner_url . '?TN=2&KE=AS'.add_session().'></td></tr><tr><td align=center colspan=4><font face=arial size=1>';
        sort($pick);
        foreach($pick as $key => $value)
        {
            echo '<a href=' . runner_url . '?AS=' . $_GET['AS'] .  '&ID='.$_GET['ID'].'&KE=AS&PI=' . $value .add_session().' target=mainframe>' . $value . '</a> ';
        }

        echo '</font></td></tr></form></table>';
    }

return 1;
?>

<?php
if (!defined('version'))
  exit;

//----------------------------------------
// Process arguments for the Timer links action
//----------------------------------------
//action('timer_links','abuse,Report Abuse,timer_link'
$args = explode(',', $args);
$link = $args[0];
if(empty($args[1]))
{
    $text = 'Report Abuse';
}else
{
    $text = $args[1];
}
if(empty($args[2]))
{
    $style = '';
}else
{
    $style = "class='{$args[2]}'";
}

//----------------------------------------
// Section for abuse link
//----------------------------------------
if($link == 'abuse')
{
    echo "<a $style href='". pages_url ."abuse_report.php' target='_blank'>$text</a>";
    return;
}

//----------------------------------------
// Section for new window link
//----------------------------------------
if($link == 'open_in_new_window')
{
    echo "<a $style href='". htmlentities($_SESSION['abuse_report']['url'], ENT_QUOTES) ."' target='_blank'>$text</a>";
    return;
}

?>
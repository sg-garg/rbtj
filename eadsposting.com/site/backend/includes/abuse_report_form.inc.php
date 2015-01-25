<?php
if (!defined('version'))
  exit;

if(empty($_SESSION['abuse_report']['ad']))
{
    echo "To report abuse, please click <i>Report abuse</i> link in timer bar.";
    return;
}

//----------------------------------------
// Process arguments for the abuse form action
//----------------------------------------
//action('abuse_report_form','abuse_report,60,20,Report:,Reason:,Submit,Thank you. Your report has been sent.')
$args = explode(',', $args);


if(empty($args[0]))
{
    $style = '';
}else
{
    $style = " id='$args[0]' ";
}

$cols = (int)$args[1];
if($cols < 1)
{
    $cols = 60;
}

$rows = (int)$args[2];
if($rows < 1)
{
    $rows = 20;
}
if(empty($args[3]))
{
    $report = 'Report:';
}else
{
    $report = $args[3];
}

if(empty($args[4]))
{
    $reason = 'Reason:';
}else
{
    $reason = $args[4];
}

if(empty($args[5]))
{
    $submit = 'Submit';
}else
{
    $submit = $args[5];
}

if(empty($args[6]))
{
    $thanks = 'Thank you. Your report has been sent.';
}else
{
    $thanks = $args[6];
}

//----------------------------------------
// Process submission
//----------------------------------------
if($_SESSION['username'] == $_POST['abuse_report_by'])
{
    switch($_POST['type'])
    {
        case 'email':   
            $type = 'email';
            break;
        case 'PTC':   
            $type = 'PTC';
            break;
        default:
            return;
            exit;
            break;
    }
    $ad = preg_replace('([^0-9])', '', $_POST['ad']);
    $url = mysql_real_escape_string($POST['url']);
    $reason = mysql_real_escape_string($_POST['reason']);

    mysql_query("INSERT INTO `". mysql_prefix ."abuse` (
                    `time` ,
                    `username` ,
                    `type` ,
                    `ad` ,
                    `reason`
                )
                VALUES (
                    CURRENT_TIMESTAMP ,
                    '{$_SESSION['username']}',
                    '$type',
                    '$ad',
                    '$reason'
                );") or die(mysql_error());

    echo $thanks;
    return;
}

?>
<form method='POST' action='<?php echo $_SERVER['PHP_SELF'];?>'>
<input type='hidden' name='abuse_report_by' value='<?php echo $_SESSION['username'];?>'>
<input type='hidden' name='type' value='<?php echo $_SESSION['abuse_report']['type'];?>'>
<input type='hidden' name='ad' value='<?php echo $_SESSION['abuse_report']['ad'];?>'>
<input type='hidden' name='url' value='<?php echo htmlentities($_SESSION['abuse_report']['url'], ENT_QUOTES);?>'>
<table border='0' <?php echo $style;?>>
<tr>
    <td><?php echo $report;?></td><td><?php echo htmlentities($_SESSION['abuse_report']['url'], ENT_QUOTES);?></td>
</tr>
<tr>
    <td><?php echo $reason;?></td><td><textarea cols='<?php echo $cols;?>' rows='<?php echo $rows;?>' name='reason'></textarea></td>
</tr>
<tr>
    <td colspan='2' align='center'><input type='submit' value='<?php echo $submit;?>'></td>
</tr>
</table>
</form>

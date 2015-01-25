<?php
require_once("functions.inc.php");
$title='Set Admin Password';
admin_login();
if(isset($_POST['setpassword']))
{
    if ($_POST['setpassword'] != $_POST['setconfirmpassword'])
    {
        echo "Error Passwords did not match";
    }
    else
    {
        $setmade=1;
        mysql_query("replace into ".mysql_prefix."system_values set name='admin password',value='".password($_POST['setpassword'])."'"); 
        echo "Admin password has been updated. DO NOT FORGET IT!"; 
        footer();
    }
}
if (!$setmade)
{
    echo "
    <b>Password Change:</b><br>
    Only use secure passwords to prevent unauthorized accesses to your site. Random password is recommended.<br>
    <br><form method='POST'>
    <table border='0' cellpadding='0' cellspacing='0'>
        <tr>
            <td align='right'>
                Enter New Password:
            </td>
            <td>
                <input type='password' name='setpassword'>
            </td>
        </tr>
        <tr>
            <td align='right'>
                Confirm New Password:
            </td>
            <td>
                <input type='password' name='setconfirmpassword'>
            </td>
        </tr>
    </table>
    <input type='submit' style='margin-left: 30px' value='Change Password'>
    </form><br>
    <b>Random password generator:</b><br>\n\n";

    
    if(isset($_POST['pwrandom']))
    {
        $pw = '';
        $length = mt_rand(12,24);
        $characters = array();

        //Numbers
        for($i = 48; $i <= 57; $i++)
        {
            array_push($characters, chr($i));
        }

        //ALPHABETS
        for($i = 65; $i <= 90; $i++)
        {
            array_push($characters, chr($i));
        }

        //alphabets
        for($i = 97; $i <= 122; $i++)
        {
            array_push($characters, chr($i));
        }

        //Some extras
        array_push($characters, '-');
        array_push($characters, '_');
        array_push($characters, '=');
        array_push($characters, '!');
        array_push($characters, '(');
        array_push($characters, ')');

        for($i = 1; $i <= $length; $i++)
        {
            $pw .= $characters[array_rand($characters)];
        }
        echo "Suggested random password:<br><span style='font-size: 160%; font-weight: bold; margin-left: 60px'>". htmlentities($pw, ENT_QUOTES). "</span><br>";
    }
    echo "<br>
    <form method='POST'>
    <input type='submit' style='margin-left: 30px' name ='pwrandom' value='Suggest Password For Me'>
    </form>";


}
footer();

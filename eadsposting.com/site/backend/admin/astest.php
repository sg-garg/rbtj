<?php include ("functions.inc.php");?>
<frameset framespacing = 0 frameborder = 1 border = 1 rows = "80,1*">
    <frame name = top src = <?php echo scripts_url."admin/astestok.php";?> scrolling = no>
    <frame name = adframe
               src = "<?php echo runner_url.'?REDIRECT='.rawurlencode($_GET['testurl']).'&hash='.md5($_GET['testurl'].key);?>"
               style = "mso-linked-frame:auto">
</frameset>

<noframes>
    <body>
       
            This page uses frames, but your browser doesn't support them.

    </body>
</noframes>

</frameset>